<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        // Add "type" to distinguish normal vs social signup
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'type' => 'required|in:normal,social',
            'password' => $request->type === 'normal' ? 'required|string|min:8|confirmed' : 'nullable',
            'fcm_token' => 'nullable|string|max:4096',
            'device_type' => 'nullable|in:ios,android',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->type === 'normal'
                ? Hash::make($request->password)
                : Hash::make(uniqid()), // generate random password for social
            'subscription' => $request->subscription ?? null,
            'therapist' => $request->therapist ?? false,
            'premium_access' => false,
        ];

        if (Schema::hasColumn('users', 'type')) {
            $data['type'] = $request->type;
        }
        if (Schema::hasColumn('users', 'fcm_token') && $request->filled('fcm_token')) {
            $data['fcm_token'] = $request->fcm_token;
        }
        if (Schema::hasColumn('users', 'device_type') && $request->filled('device_type')) {
            $data['device_type'] = $request->device_type;
        }

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => __('messages.user_created'),
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function signin(Request $request)
    {
        // Require type for signin as well
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'type' => 'required|in:normal,social',
            'password' => $request->type === 'normal' ? 'required' : 'nullable',
            'fcm_token' => 'nullable|string|max:4096',
            'device_type' => 'nullable|in:ios,android',
            'force_logout_other_devices' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->type === 'normal') {
            // Password login
            if (! Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => __('messages.invalid_credentials'),
                ], 401);
            }
        } else {
            // Social login
            $user = User::where('email', $request->email)->first();
            if (! $user) {
                return response()->json([
                    'message' => __('messages.user_not_found'),
                ], 404);
            }
        }

        $user = User::where('email', $request->email)->first();

        // Blocked check
        if ($user->blocked ?? false) {
            return response()->json([
                'message' => __('messages.account_blocked'),
            ], 403);
        }

        // Check if user has existing tokens
        $existingTokens = $user->tokens();
        if ($existingTokens->count() > 0 && !$request->boolean('force_logout_other_devices')) {
            return response()->json([
                'message' => 'Already logged in on another device. Do you want to logout from other devices and login here?',
                'already_logged_in' => true,
            ], 409);
        }

        // Revoke all existing tokens if force logout is requested
        if ($request->boolean('force_logout_other_devices')) {
            $user->tokens()->delete();
        }

        $updates = ['last_login' => now()];
        if (Schema::hasColumn('users', 'type')) {
            $updates['type'] = $request->type;
        }
        if (Schema::hasColumn('users', 'fcm_token') && $request->filled('fcm_token')) {
            $updates['fcm_token'] = $request->fcm_token;
        }
        if (Schema::hasColumn('users', 'device_type') && $request->filled('device_type')) {
            $updates['device_type'] = $request->device_type;
        }

        $user->update($updates);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => __('messages.login_success'),
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => __('messages.logout_success'),
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user(); // authenticated user

        // Verify current password
        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => __('messages.incorrect_current_password'),
            ], 400);
        }

        // Update to new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => __('messages.password_changed'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        $existing = DB::table('password_resets')->where('email', $request->email)->first();
        if ($existing && isset($existing->created_at)) {
            $createdAt = Carbon::parse($existing->created_at);
            if ($createdAt->isAfter(Carbon::now()->subSeconds(60))) {
                return response()->json(['message' => 'Too many requests'], 429);
            }
        }

        $token = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store reset token
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        if ($user) {
            Mail::raw("Use this token to reset your password: $token", function ($msg) use ($request) {
                $msg->to($request->email)->subject('Password Reset Request');
            });
        }

        return response()->json(['message' => __('messages.password_reset_token_sent')]);
    }

    // Step 2: Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return response()->json(['message' => __('messages.invalid_token')], 400);
        }

        $createdAt = isset($record->created_at) ? Carbon::parse($record->created_at) : null;
        if (! $createdAt || $createdAt->isBefore(Carbon::now()->subMinutes(15))) {
            return response()->json(['message' => __('messages.invalid_token')], 400);
        }

        $tokenMatches = false;
        if (is_string($record->token ?? null) && Hash::check($request->token, $record->token)) {
            $tokenMatches = true;
        } elseif (($record->token ?? null) === $request->token) {
            $tokenMatches = true;
        }

        if (! $tokenMatches) {
            return response()->json(['message' => __('messages.invalid_token')], 400);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json(['message' => __('messages.user_not_found')], 404);
        }
        $user->update(['password' => Hash::make($request->password)]);

        // Delete token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => __('messages.password_reset_success')]);
    }
}
