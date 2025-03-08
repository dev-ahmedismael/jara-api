<?php

namespace App\Http\Controllers\Central\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Authentication\LoginRequest;
use App\Models\Central\Customer\Customer;
use App\Models\Central\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentralAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'], 401);
            }

            $user = Auth::user();

            if ($user && User::where('id', $user->id)->exists()) {
                return response()->json([
                    'user' => $user,
                    'message' => 'تم تسجيل الدخول بنجاح.',
                ]);
            }

            return response()->json(['message' => 'عفواَ، ليس لديك تصريح بالدخول.'], 401);
    }

    public function logout(Request $request)
    {
        //                Auth::logout();
        //                $cookie = Cookie::forget('laravel_session');
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح.']);
    }
}
