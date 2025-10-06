<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Send password reset token (by email) or generate token for phone.
     */
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        if (!$request->email && !$request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Provide email or phone.'
            ], 422);
        }

        // Case 1: email using Laravel broker (sends email)
        if ($request->email) {
            $status = Password::sendResetLink(['email' => $request->email]);

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reset link sent to your email.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __($status)
            ], 400);
        }

        // Case 2: phone - generate token and store in password_resets table
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Phone not found.'
            ], 404);
        }

        $plainToken = Str::random(64);

        // store hashed token in password_resets table, reuse email column to hold phone
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->phone],
            [
                'token' => Hash::make($plainToken),
                'created_at' => now()
            ]
        );

        // TODO: هنا ترسل $plainToken عبر SMS إلى المستخدم (باستخدام موفر SMS)
        // لأغراض الاختبار نرجع التوكن في الاستجابة (لا تفعل هذا في الإنتاج)
        return response()->json([
            'success' => true,
            'message' => 'Reset token generated for phone (send via SMS in production).',
            'data' => [
                'phone' => $request->phone,
                'token' => $plainToken
            ]
        ]);
    }

    /**
     * Reset password using email+token (Laravel) or phone+token (custom).
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8'
        ]);

        if (!$request->email && !$request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Provide email or phone.'
            ], 422);
        }

        // Case 1: reset via email using Laravel's Password broker
        if ($request->email) {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->password = Hash::make($request->password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __($status)
            ], 400);
        }

        // Case 2: reset via phone
        $phone = $request->phone;
        $token = $request->token;

        $record = DB::table('password_resets')->where('email', $phone)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or phone.'
            ], 400);
        }

        // check token and expiry (60 minutes)
        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->diffInMinutes(now()) > 60) {
            // expired
            DB::table('password_resets')->where('email', $phone)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Token expired.'
            ], 400);
        }

        if (!Hash::check($token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token.'
            ], 400);
        }

        // update user's password
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // delete used token
        DB::table('password_resets')->where('email', $phone)->delete();

        event(new PasswordReset($user));

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }
}
