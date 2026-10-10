<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->orderByDesc('id');

        $q = $request->query('q');
        if (is_string($q) && $q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $role = $request->query('role');
        if ($role === 'admin') {
            $query->where('admin', true);
        } elseif ($role === 'user') {
            $query->where('admin', false);
        }

        $status = $request->query('status');
        if ($status === 'blocked') {
            $query->where('blocked', true);
        } elseif ($status === 'active') {
            $query->where('blocked', false);
        }

        $users = $query->paginate(25)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'q' => $q,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'admin' => 'nullable|boolean',
            'blocked' => 'nullable|boolean',
            'therapist' => 'nullable|boolean',
            'device_type' => 'nullable|in:ios,android',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'admin' => (bool) ($validated['admin'] ?? false),
            'blocked' => (bool) ($validated['blocked'] ?? false),
            'therapist' => (bool) ($validated['therapist'] ?? false),
        ];

        if (array_key_exists('device_type', $validated)) {
            $payload['device_type'] = $validated['device_type'];
        }

        if (is_string($validated['password'] ?? null) && $validated['password'] !== '') {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('admin.users.edit', $user)->with('status', 'Saved.');
    }
}
