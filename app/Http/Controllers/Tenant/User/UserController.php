<?php

namespace App\Http\Controllers\Tenant\User;

use App\Http\Controllers\Controller;
use App\Models\Central\Customer\Customer;
use App\Models\Tenant\User\User;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::with('roles')->filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $users], 200);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ], [
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'phone.unique' => 'رقم الجوال مستخدم من قبل.',
            'password.min' => 'استخدم كلمة مرور قوية مكونة من 6 أرقام على الأقل.'
        ]);

        $tenant = tenant();

        tenancy()->end();

        $customer = Customer::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);

        $customer->tenants()->attach($tenant->id, ['role' => 'user']);

        \tenancy()->initialize($tenant->id);

        $user_data = $request->except(['password']);
        $user_data['password'] = bcrypt($request->password);

        $user = User::create($user_data);
        $user->assignRole($request->role);

        return response()->json(['message' => 'تم إنشاء المستخدم بنجاح', 'data' => $user->load('roles')], 201);
    }

    public function show(Request $request, string $id) {
        $user = User::findOrFail($id);
        $user->load('roles');
        return response()->json(['data' => $user], 200);
    }

    public function update(Request $request, string $id) {
        $user = User::findOrFail($id);
        $tenant = tenant();
        \tenancy()->end();
        $customer = Customer::where('email', $user->email)->first();
        \tenancy()->initialize($tenant->id);
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|string|email|max:255|unique:users,email,{$id}",
            'phone' => "sometimes|string|max:255|unique:users,phone,{$id}",
            'password' => 'nullable|string|min:6',
        ], [
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'phone.unique' => 'رقم الجوال مستخدم من قبل.',
            'password.min' => 'استخدم كلمة مرور قوية مكونة من 6 أرقام على الأقل.'
        ]);

        $data = $request->except(['password']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        if($request->filled('role')) {
            $user->assignRole($request->input('role'));
        }

        if ($customer) {
            $customer->update([
                'name' => $request->input('name', $customer->name),
                'email' => $request->input('email', $customer->email),
                'phone' => $request->input('phone', $customer->phone),
            ]);
        }

        return response()->json([
            'message' => 'تم تحديث المستخدم بنجاح',
            'data' => $user->load('roles')
        ], 200);
    }

    public function destroy(Request $request, string $id) {
        $user = User::findOrFail($id);
        $tenant = tenant();
        \tenancy()->end();
        $customer = Customer::where('email', $user->email)->first();

        \tenancy()->initialize($tenant->id);

        if ($customer) {
            $customer_tenant = $customer->tenants()->where('tenant_id', $tenant->id)->first();

             if ($customer_tenant && $customer_tenant->pivot->role === 'customer') {
                return response()->json(['message' => 'عفواً، لا يمكن حذف حساب المالك.'], 403);
            }

           $customer->tenants()->detach($tenant->id);
        }

         if (!$customer || $customer->tenants()->count() === 0) {
            $user->delete();
            $customer?->delete();
             return response()->json(['message'=>'تم حذف المستخدم بنجاح.']);
        }
         $customer->delete();
         $user->delete();

        return response()->json(['message' => 'تم حذف الحساب بنجاح.'], 200);
    }
}
