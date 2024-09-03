<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek kredensial
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Dapatkan pengguna yang terautentikasi
        $user = Auth::user();

        // Buat token personal untuk mobile
        $token = $user->createToken('authToken')->plainTextToken;

        // Respons dengan token
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token, // Token ini yang perlu disimpan di mobile
        ]);
    }
}
