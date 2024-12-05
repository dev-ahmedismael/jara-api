<?php

namespace App\Http\Controllers\Jara;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Auth\LoginRequest;
use App\Http\Requests\Tenant\Auth\RegisterRequest;
use App\Models\Jara\Customer;
use App\Models\Jara\Tenant;
use App\Models\Tenant\SiteBuilder\PolicyPage;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Stancl\Tenancy\Database\Models\Domain;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Start transaction
        DB::beginTransaction();

        //         Check if domain exists
        if (Domain::where('domain', $request->input('domain'))->exists()) {
            return response()->json(['message' => 'الدومين مستخدم من قبل مستخدم آخر.'], 422);
        }
        if (Customer::where('email', $request->input('email'))->where('type', 'owner')->exists()) {
            return response()->json(['message' => 'البريد الإلكتروني الذي أدخلته مستخدم من قبل.'], 422);
        }
        //        Check if email exists


        try {
            // Creating tenant
            $tenant = Tenant::create([
                'type' => 'consultations',
                'company_name_ar' => $request->input('company_name_ar'),
                'company_name_en' => $request->input('company_name_en'),
                'license_number' => $request->input('license_number'),
            ]);

            $tenant->domains()->create(['domain' => $request->input('domain')]);

            // Creating a customer
            Customer::create([
                'name' => $request->input('name'),
                'job' => $request->input('job'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'tenant_id' => $tenant->id,
                'type' => 'owner'
            ]);

            tenancy()->initialize($tenant->id);


            if (\App\Models\Jara\User::where('email', $request->input('email'))->exists()) {
                return response()->json(['message' => 'البريد الإلكتروني الذي أدخلته مستخدم من قبل.'], 422);
            }

            if ($request->hasFile('license_document')) {
                $tenant->addMediaFromRequest('license_document')->toMediaCollection('license_document');
            }

            // Creating user
            $user = User::create([
                'name' => $request->input('name'),
                'job' => $request->input('job'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $imagePath = 'default_user_pic.png';

            if (Storage::disk('media')->exists($imagePath)) {
                $user->addMediaFromDisk($imagePath, 'media')->preservingOriginal()
                    ->toMediaCollection('user_pic');
            } else {
                Log::error("Default image not found in media disk.");
            }


            // Commit transaction
            DB::commit();

            //            Creating Permissions
            $permissions = [['name' => 'الإحصائيات والتقارير', 'guard_name' => 'sanctum'], ['name' => 'الإستشارات والخدمات', 'guard_name' => 'sanctum'], ['name' => 'الرسائل', 'guard_name' => 'sanctum'], ['name' => 'الإستشارات الواردة', 'guard_name' => 'sanctum'], ['name' => 'رموز الإستجابة السريعة', 'guard_name' => 'sanctum'], ['name' => 'أكواد الخصم', 'guard_name' => 'sanctum'], ['name' => 'تصميم الموقع', 'guard_name' => 'sanctum'], ['name' => 'إعدادات الموقع', 'guard_name' => 'sanctum'], ['name' => 'متجر تطبيقات جرة', 'guard_name' => 'sanctum'], ['name' => 'المقالات', 'guard_name' => 'sanctum'], ['name' => 'المشرفين والصلاحيات', 'guard_name' => 'sanctum'], ['name' => 'الإعدادات', 'guard_name' => 'sanctum'],];
            Permission::insert($permissions);

            //            Create Policies Pages
            $pages = [
                [
                    'status' => false,
                    'title' => 'الشروط والأحكام',
                    'content' => ''
                ], [
                    'status' => false,
                    'title' => 'سياسة الخصوصية',
                    'content' => ''
                ], [
                    'status' => false,
                    'title' => 'سياسة الإستبدال والإسترجاع',
                    'content' => ''
                ],
            ];

            foreach ($pages as $page) {
                PolicyPage::create($page);
            }

            // Generate authentication token and set cookie
            $token = $user->createToken('auth_token')->plainTextToken;
            $cookie = cookie('auth_token', $token, 60 * 24 * 7);

            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح.',
                'user' => $user,
                'tenant_id' => $tenant->id,
                'auth_token' => $token,
            ])->withCookie($cookie);

        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollback();

            // Log error
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json(['message' => 'حدث خطأ أثناء التسجيل.'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $customer = Customer::where('email', $request->input('email'))->first();
            $tenant_id = $customer->tenant_id;
            tenancy()->initialize($tenant_id);
            $tenant = Tenant::find($tenant_id);
            $domain = $tenant->domains()->first();
            $user = User::where('email', $request->input('email'))->first();

            //            Check if email is registered
            if (!$user) {
                return response()->json(['message' => 'البريد الإلكتروني الذي أدخلته غير مسجل لدينا على النظام.'], 422);
            }

            if (!Hash::check($request->input('password'), $user->password)) {
                return response()->json(['message' => 'كلمة المرور التي أدخلتها غير صحيحة.'], 401);
            }

            // Generate a personal access token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // Create the cookie with the token
            $cookie = cookie('auth_token', $token, 60 * 24 * 7);

            // Return the user data along with the token in the cookie
            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح.',
                'user' => $user,
                'domain' => $domain,
                'auth_token' => $token
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            // Log error
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json(['message' => 'البريد الإلكتروني / كلمة المرور غير صحيحة.'], 500);
        }
    }

    public function logout(Request $request)
    {
        //        //         Revoke the current user's token
        //        $user = $request->user();
        //        $customer = Customer::where('email', $user->email)->first();
        //        $tenant_id = $customer->tenant_id;
        //        tenancy()->initialize($tenant_id);
        //        $user->currentAccessToken()->delete();

        // Return response without the 'auth_token' cookie
        return response()->json(['message' => 'Logged out successfuly!']);
    }
}
