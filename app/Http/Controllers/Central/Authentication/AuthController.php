<?php

namespace App\Http\Controllers\Central\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Authentication\LoginRequest;
use App\Http\Requests\Central\Authentication\RegisterRequest;
use App\Models\Central\Customer\Customer;
use App\Models\Central\Tenant\Tenant;
use App\Models\Tenant\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Database\Models\Domain;

class AuthController extends Controller
{
    //Register
    public function register(RegisterRequest $request)
    {
        $this->check_email($request);

        try {
            // Create customer
            $customer = Customer::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
            ]);

            // Create tenant
            $tenant = Tenant::create([
                'website_type' => $request->input('website_type'),
                'license_type' => $request->input('license_type'),
                'license_name' => $request->input('license_name'),
                'license_number' => $request->input('license_number'),
            ]);

            $customer->tenants()->attach($tenant->id, ['role' => 'customer']);

            tenancy()->initialize($tenant->id);
//                 if ($request->hasFile('license_document')) {
//                    $tenant->addMedia($request->file('license_document'))
//                        ->toMediaCollection('license_documents');
//                }

                $user = User::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                ]);

            tenancy()->end();
            return response()->json([
                'message' => 'تم إنشاء حسابك بنجاح.',
                'tenant_id' => $tenant->id
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'حدث خطأ أثناء التسجيل. الرجاء المحاولة مرة أخرى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function check_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if (Customer::where('email', $request->input('email'))->exists()) {
            abort(400, 'البريد الإلكتروني الذي أدخلته مستخدم بالفعل.');
        } else {
            return response()->json(['message' => 'البريد الإلكتروني متاح.'], 200);
        }
    }

    public function check_domain(Request $request)
    {
        $request->validate(
            [
                'domain' => 'required|string'
            ]
        );
        if (Domain::where('domain', $request->input('domain'))->exists()) {
            return response()->json(['message' => 'الرابط الذي أدخلته مستخدم بالفعل.'], 400);
        } else {
            return response()->json(['message' => 'الرابط متاح.'], 200);
        }
    }

    public function login(LoginRequest $request)
    {
        $customer = Customer::where('email', $request->input('email'))->first();

        if (!$customer) {
            return response()->json(['message' => 'البريد الإلكتروني الذي أدخلته غير مسجل لدينا.'], 401);
        }

        // Fetch the first tenant associated with the customer
        $customer_tenant = $customer->tenants()->with('domains')->first();

        if (!$customer_tenant) {
            return response()->json(['message' => 'لم يتم العثور على مستأجر لهذا الحساب.'], 404);
        }

         return $customer_tenant->run(function () use ($request, $customer_tenant) {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'], 401);
            }

            $user = Auth::user();

            return response()->json([
                'user' => $user,
                'message' => 'تم تسجيل الدخول بنجاح.',
                'tenant_id' => $customer_tenant->id,
                'domain' => optional($customer_tenant->domains()->first())->domain, // Avoids error if no domain
            ]);
        });
    }

    public function logout(Request $request)
    {
        //        Auth::logout();
        //        $cookie = Cookie::forget('laravel_session');
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح.']);
    }
}
