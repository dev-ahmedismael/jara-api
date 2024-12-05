<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\User\BasicInfoRequest;
use App\Http\Requests\Tenant\User\PasswordRequest;
use App\Http\Requests\Tenant\User\ProfilePictureRequest;
use App\Http\Requests\Tenant\User\SupervisorRequest;
use App\Models\Jara\Customer;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->with('roles', 'media')->get();
        return response()->json($users);
    }

    public function store(SupervisorRequest $request)
    {
        if (User::where('email', $request->input('email'))->exists()) {
            return response()->json(['message' => 'البريد الإلكتروني الذي أدخلته مستخدم من قبل.'], 422);
        }
        $user = User::create([
            'name' => $request->input('name'),
            'job' => $request->input('job'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        $request_role = $request->input('role');
        $imagePath = 'default_user_pic.png';

        if (Storage::disk('media')->exists($imagePath)) {
            $user->addMediaFromDisk($imagePath, 'media')->preservingOriginal()
                ->toMediaCollection('user_pic');
        } else {
            Log::error("Default image not found in media disk.");
        }
        if ($request_role) {
            try {
                $role = Role::findByName($request_role);

                $user->roles()->attach($role);

                $user->syncPermissions($role->permissions());

            } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
                return response()->json(['message' => 'الدور المحدد غير موجود.'], 404);
            }
        }
        $tenant = tenant();
        tenancy()->end();

        Customer::create([
            'name' => $request->input('name'),
            'job' => $request->input('job'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'tenant_id' => $tenant->id,
            'type' => 'employee',
        ]);
        tenancy()->initialize($tenant->id);
        return response()->json($user);
    }

    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user->load('roles.permissions', 'media'));
    }

    public function update(SupervisorRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->input('name'),
            'job' => $request->input('job'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => $request->has('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        if ($request->hasFile('user_pic')) {
            $user->clearMediaCollection('user_pic');                               // Clear old image
            $user->addMediaFromRequest('user_pic')->toMediaCollection('user_pic'); // Add new image
        }

        $role = $request->input('role');
        if ($role) {
            $role = Role::find($role);
            if ($role) {
                $user->roles()->sync([$role->id]);

                foreach ($role->permissions as $permission) {
                    $user->givePermissionTo($permission);
                }
            } else {
                return response()->json(['message' => 'الدور المحدد غير موجود.'], 404);
            }
        }

        return response()->json([
            'message' => 'تم تحديث المستخدم بنجاح.',
            'user' => $user->load(['roles', 'permissions']),
        ], 200);
    }


    public function destroy(string $id)
    {
        $currentTenant = tenant();

        $user = User::find($id);
        tenancy()->end();

        $jara_customer = Customer::where('email', $user->email)->first();
        if ($jara_customer) {
            if ($jara_customer->type === 'owner') {
                return response()->json(['message' => 'لا يمكن حذف مالك الموقع'], 422);
            }
            if ($jara_customer->type === 'employee') {
                Customer::destroy($jara_customer->id);
            }
        }
        tenancy()->initialize($currentTenant->id);

        User::destroy($id);
        $users = User::latest()->with('roles', 'media')->get();

        return response()->json($users, 200);
    }

    public function show_authenticated(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->load(['media', 'roles.permissions']);

        }

        return response()->json(['user' => $user], 200);
    }

    public function update_profile_picture(ProfilePictureRequest $request)
    {
        $user = $request->user();
        $user->addMediaFromRequest('image')->toMediaCollection('user_pic');
        return response()->json(['user' => $user], 200);
    }

    public function delete_profile_picture(Request $request)
    {
        $user = $request->user();

        $imagePath = 'default_user_pic.png';
        $user->addMediaFromDisk($imagePath, 'media')->preservingOriginal()
            ->toMediaCollection('user_pic');

        return response()->json(['user' => $user], 200);
    }

    public function update_basic_info(BasicInfoRequest $request)
    {
        $user = $request->user();
        $tenant_id = tenant()->id;

        // Check email in central database
        $email_exists_central = tenancy()->central(function () use ($request, $tenant_id) {
            $customer = Customer::where('email', $request->input('email'))->first();

            // If the email exists and belongs to a different tenant
            return $customer && $customer->tenant_id !== $tenant_id;
        });

        // Check email in tenant context
        $email_exists_tenant = User::where('email', $request->input('email'))
            ->where('id', '!=', $user->id)
            ->exists();

        // If the email is already in use, return an error
        if ($email_exists_central || $email_exists_tenant) {
            return response()->json(['message' => 'البريد الإلكتروني مستخدم من قبل.'], 422, [], JSON_UNESCAPED_UNICODE);
        }

        // Update customer in central context
        tenancy()->central(function () use ($request, $user) {
            $customer = Customer::where('email', $user->email)->first();

            if ($customer) {
                $customer->update($request->only('name', 'email', 'phone', 'job'));
            }
        });

        // Update user in tenant context
        $user->update($request->only('name', 'email', 'phone', 'job'));

        // Return the updated user
        return response()->json(['user' => $user], 200);
    }


    public function update_password(PasswordRequest $request)
    {
        $user = auth()->user();

        // Check if the old password matches the stored hashed password
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return response()->json(['message' => 'كلمة المرور القديمة غير صحيحة.'], 401);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح.'], 200);
    }


}
