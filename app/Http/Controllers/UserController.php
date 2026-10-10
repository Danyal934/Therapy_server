<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected function isAdmin(?User $user): bool
    {
        return (bool) ($user?->admin ?? false);
    }

    public function index()
    {
        $user = request()->user();
        if (! $this->isAdmin($user)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        $users = User::all();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();
        if (! $this->isAdmin($actor)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'admin' => 'boolean',
            'subscription' => 'nullable|string',
            'therapist' => 'boolean',
            'premium_access' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'admin' => $request->admin ?? false,
            'subscription' => $request->subscription,
            'therapist' => $request->therapist ?? false,
            'premium_access' => $request->premium_access ?? false,
        ]);

        return response()->json([
            'message' => __('messages.user_created_admin'),
            'user' => $user,
        ], 201);
    }

    // public function show(User $user)
    // {
    //     if (!$user) {
    //         return response()->json([
    //             'message' => 'User not found'
    //         ], 404);
    //     }
    //     return response()->json([
    //         'user' => $user
    //     ]);
    // }

    public function show(Request $request, $id)
    {
        $actor = $request->user();
        if (! $actor) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        if (! $this->isAdmin($actor) && (int) $actor->id !== (int) $id) {
            return response()->json([
                'message' => __('messages.unauthorized'),
            ], 403);
        }

        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => __('messages.user_not_found'),
            ], 404);
        }

        return response()->json([
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $actor = $request->user();
        if (! $actor) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        if (! $this->isAdmin($actor) && (int) $actor->id !== (int) $user->id) {
            return response()->json([
                'message' => __('messages.unauthorized'),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [];

        // Update name if provided
        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists('profile_images/'.$user->profile_image)) {
                Storage::disk('public')->delete('profile_images/'.$user->profile_image);
            }

            $image = $request->file('profile_image');
            $imageName = uniqid().'.'.$image->extension();
            $image->storeAs('profile_images', $imageName, 'public');

            $data['profile_image'] = $imageName;
        }

        // Apply updates only if data exists
        if (! empty($data)) {
            $user->update($data);
            $user = $user->fresh();
        }

        // Add full URL for profile image
        if ($user->profile_image) {
            $user->profile_image_url = url('storage/profile_images/'.$user->profile_image);
        }

        return response()->json([
            'message' => __('messages.user_updated'),
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        $actor = request()->user();
        if (! $this->isAdmin($actor)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => __('messages.user_deleted'),
        ]);
    }

    public function blockUser(User $user)
    {
        $actor = request()->user();
        if (! $this->isAdmin($actor)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        $user->update(['blocked' => true]);

        return response()->json([
            'message' => __('messages.user_blocked'),
            'user' => $user,
        ]);
    }

    public function unblockUser(User $user)
    {
        $actor = request()->user();
        if (! $this->isAdmin($actor)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        $user->update(['blocked' => false]);

        return response()->json([
            'message' => __('messages.user_unblocked'),
            'user' => $user,
        ]);
    }
}
