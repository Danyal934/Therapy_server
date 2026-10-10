<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class NotificationTimeController extends Controller
{
    public function updateTime(Request $request)
    {
        $request->validate([
            'notification_time' => 'nullable|date_format:H:i',
            'fcm_token' => 'required|string|max:4096',
            'device_type' => 'nullable|in:ios,android',
        ]);

        // ✅ Get the authenticated user from the token
        $user = User::find(Auth::id());

        // If token invalid or missing
        if (! $user) {
            return response()->json(['message' => __('messages.unauthorized')], 401);
        }

        if (Schema::hasColumn('users', 'fcm_token')) {
            $user->fcm_token = $request->fcm_token;
        }

        if (Schema::hasColumn('users', 'device_type') && $request->filled('device_type')) {
            $user->device_type = $request->device_type;
        }

        if (Schema::hasColumn('users', 'notification_time') && $request->filled('notification_time')) {
            $user->notification_time = $request->notification_time;
        }

        $user->save();

        return response()->json(['message' => __('messages.notification_saved')]);
    }
}
