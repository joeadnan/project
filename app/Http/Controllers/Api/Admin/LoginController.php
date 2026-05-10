<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * Handle Login Admin
     */
    public function index(Request $request): JsonResponse
    {
        // Gunakan Validator agar response error konsisten berupa JSON
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only(['email', 'password']);

        // Pastikan guard 'api_admin' sudah terdaftar di config/auth.php
        if (!$token = auth()->guard('api_admin')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api_admin')->user(),
            'token'   => $token
        ], 200);
    }
    
    /**
     * Get Authenticated User
     */
    public function getUser(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api_admin')->user()
        ]);
    }

    /**
     * Refresh Token
     */
    public function refreshToken(): JsonResponse
    {
        try {
            // Refresh token dari request header otomatis
            /** @var \Tymon\JWTAuth\JWTGuard $guard */
            $guard = auth()->guard('api_admin');
            $newToken = $guard->refresh();
            
            return response()->json([
                'success' => true,
                'token'   => $newToken,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak bisa di-refresh',
            ], 401);
        }
    }

    /**
     * Logout
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->guard('api_admin')->logout();
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout'
            ], 500);
        }
    }
}
