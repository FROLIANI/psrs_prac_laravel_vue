<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => 'Reset link sent to your email.',
                ]);
            }

            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'User not found with this email.',
            ], 404);
        } catch (\Exception $e) {
            $errorId = now()->format('YmdHis') . rand(1000, 9999);
            Log::error("[$errorId] Password reset email failed: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => "Failed to send reset link. Contact support with error $errorId.",
            ], 500);
        }
    }

   public function resetPassword(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $authHeader = $request->header('Authorization');
    $token = null;

    if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
        $token = substr($authHeader, 7);
    }

    if (!$token) {
        return response()->json([
            'status' => false,
            'code' => 401,
            'message' => 'Missing or invalid token.',
        ], 401);
    }

    try {
        $status = Password::reset(
            [
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'Password has been reset successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'code' => 400,
            'message' => 'Invalid token or email.',
        ], 400);

    } catch (\Exception $e) {
        $errorId = now()->format('YmdHis') . rand(1000, 9999);
        Log::error("[$errorId] Password reset failed: " . $e->getMessage());

        return response()->json([
            'status' => false,
            'code' => 500,
            'message' => "Failed to reset password. Contact support with error $errorId.",
        ], 500);
    }
}

}
