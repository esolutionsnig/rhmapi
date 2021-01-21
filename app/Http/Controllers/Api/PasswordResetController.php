<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response([
                'message' => 'Password reset failed',
                'data' => "We can't find a user with that e-mail address.",
            ], Response::HTTP_NOT_FOUND);
        } else {
            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => str_random(60),
                ]
            );
        }

        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
            return response([
                'message' => 'Password reset initiated',
                'data' => "We have e-mailed your password reset link!",
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();if (!$passwordReset) {
            return response([
                'message' => 'Password reset failed',
                'data' => "This password reset token is invalid.",
            ], Response::HTTP_NOT_FOUND);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'Password reset failed',
                'data' => 'This password reset token is invalid.',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string',
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email],
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Password reset failed',
                'data' => 'This password reset token is invalid.',
            ], Response::HTTP_NOT_FOUND);
        } else {
            $user = User::where('email', $passwordReset->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Password reset failed',
                    'data' => "We can't find a user with that e-mail address.",
                ], Response::HTTP_NOT_FOUND);
            } else {
                $user->password = bcrypt($request->password);
                $user->save();
                $passwordReset->delete();
                $user->notify(new PasswordResetSuccess($passwordReset));return response()->json($user);
            }
        }
    }
}
