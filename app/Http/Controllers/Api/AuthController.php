<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $token = $user->createToken('MyApp')->plainTextToken;

        return $this->successResponse([
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ], 'تم التسجيل بنجاح', 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('بيانات تسجيل الدخول غير صحيحة', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('MyApp')->plainTextToken;

        return $this->successResponse([
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ], 'تم تسجيل الدخول بنجاح');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse(null, 'تم تسجيل الخروج بنجاح', 200);
    }
}
