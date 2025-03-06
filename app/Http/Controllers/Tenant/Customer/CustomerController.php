<?php

namespace App\Http\Controllers\Tenant\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Customer\CustomerRequest;
use App\Models\Tenant\Customer\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Stancl\Tenancy\Database\Models\Domain;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $customers], 200);
    }

    public function store(CustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Customer created successfully', 'data' => $customer], 201);
    }

    public function show(Customer $customer, string $id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json(['data' => $customer], 200);
    }

    public function update(CustomerRequest $request, Customer $customer, string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->validated());
        return response()->json(['message' => 'تم تحديث بيانات المستخدم بنجاح.', 'data' => $customer]);
    }

    public function destroy(Customer $customer, string $id)
    {
        Customer::destroy($id);
        return response()->json(['message' => 'تم حذف المستخدم بنجاح.']);
    }

//    Authentication

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:customers,phone',
            'email' => 'required|email|max:255|unique:customers,email',
            'password' => 'required|string|min:6',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'تم إنشاء الحساب بنجاح.', 'token' => $token, 'customer' => $customer]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'], 401);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'تم تسجيل الدخول بنجاح.', 'token' => $token, 'customer' => $customer]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح.']);
    }
}
