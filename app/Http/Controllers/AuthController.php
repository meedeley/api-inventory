<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function daftar(Request $request)
    {
        $validated = Validator::make($request->all(),  [
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed|min:3"
        ]);

        if ($validated->fails()) {
            return response()->json([
                "status" => "sukses membuat data",
                "data" => $validated->errors()
            ]);
        }

        $user = User::query()->create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password
        ]);

        return response()->json([
            "status" => "sukses melakukan registrasi",
            "data" => $user
        ]);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validated->errors()
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah'
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        
        return response()->json([
            "status" => "logout",
            "message" => "sukses logout",
        ]);
    }
}
