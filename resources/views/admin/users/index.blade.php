@extends('admin.layout')

@section('title', 'Users')

@section('content')
    <div class="card" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row" style="gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 2; min-width: 180px;">
                <input class="input" type="text" name="q" placeholder="Search name/email" value="{{ request('q', $q ?? '') }}" style="width:100%; box-sizing: border-box;">
            </div>
            <div style="flex: 1; min-width: 130px;">
                <select name="role" class="input" style="width:100%; box-sizing: border-box;">
                    <option value="">All roles</option>
                    <option value="admin" @selected(request('role', $role ?? '') === 'admin')>Admins</option>
                    <option value="user" @selected(request('role', $role ?? '') === 'user')>Users</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 130px;">
                <select name="status" class="input" style="width:100%; box-sizing: border-box;">
                    <option value="">All status</option>
                    <option value="active" @selected(request('status', $status ?? '') === 'active')>Active</option>
                    <option value="blocked" @selected(request('status', $status ?? '') === 'blocked')>Blocked</option>
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn" type="submit">Filter</button>
                <a class="btn secondary" href="{{ route('admin.users.index') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="card">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 8px;">ID</th>
                    <th style="text-align: left; padding: 8px;">Name</th>
                    <th style="text-align: left; padding: 8px;">Email</th>
                    <th style="text-align: left; padding: 8px;">Role</th>
                    <th style="text-align: left; padding: 8px;">Status</th>
                    <th style="text-align: left; padding: 8px;">Device</th>
                    <th style="text-align: left; padding: 8px;"></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td style="padding: 8px;">{{ $user->id }}</td>
                    <td style="padding: 8px;">{{ $user->name }}</td>
                    <td style="padding: 8px;">{{ $user->email }}</td>
                    <td style="padding: 8px;">
                        @if ($user->admin)
                            <span class="badge green">admin</span>
                        @else
                            <span class="badge">user</span>
                        @endif
                    </td>
                    <td style="padding: 8px;">
                        @if ($user->blocked)
                            <span class="badge red">blocked</span>
                        @else
                            <span class="badge green">active</span>
                        @endif
                    </td>
                    <td style="padding: 8px;">{{ $user->device_type ?? '-' }}</td>
                    <td style="padding: 8px;"><a href="{{ route('admin.users.edit', $user) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        <table>

        <div style="margin-top: 12px;">
            {{ $users->links('admin.pagination') }}
        </div>
    </div>
@endsection