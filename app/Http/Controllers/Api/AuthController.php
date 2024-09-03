<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return response()->json(['message' => 'Invalid fields', 'errors' => $validator->errors()], 422);

        if (!Auth::attempt($validator->validate())) {
            return response()->json([
                'message' => 'Email or Password Incorect.'
            ], 401);
        }

        $token = Auth::user()->createToken('access_token')->plainTextToken;

        return response()->json([
            'user' => Auth::user(),
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        Auth::guard('sanctum')->user()->tokens()->delete();

        return response()->json(['message' => 'Logout Success']);
    }
}
