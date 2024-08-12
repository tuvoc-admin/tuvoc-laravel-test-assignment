<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function user(Request $request){
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('web')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'success', 
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'success' => true,
            'logout' => true,
        ]);
    }
}
