<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
  public function register(Request $request)
{
    $fields = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    try {
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token
        ], 201);

    } catch (\Exception $e) {
        $errorId = now()->format('YmdHis') . rand(1000, 9999);

        \Log::error("[$errorId] Registration failed: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => false,
            'code' => 500,
            'message' => "Registration failed. Please contact Administrator with error $errorId.",
        ], 500);
    }
}


public function login(Request $request)
{
    $fields = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    try {
        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token,
        ], 200);

    } catch (\Exception $e) {
        $errorId = now()->format('YmdHis') . rand(1000, 9999);

        \Log::error("[$errorId] Login failed: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => false,
            'code' => 500,
            'message' => "Login failed. Please contact Administrator with error $errorId.",
        ], 500);
    }
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
