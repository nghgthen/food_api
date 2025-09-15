<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Đăng ký tài khoản
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('FoodAppToken')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Thông tin đăng nhập không đúng'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('FoodAppToken')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }
}
