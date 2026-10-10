@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
    <div class="card">
        <div class="row" style="justify-content: space-between; align-items: center;">
            <div>
                <div class="muted">User #{{ $user->id }}</div>
                <div style="font-size: 20px; font-weight: 700;">{{ $user->email }}</div>
            </div>
            <a class="btn secondary" href="{{ route('admin.users.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" style="margin-top: 12px;">
            @csrf
            @method('PUT')

            <!-- Name and Email row - equal widths -->
            <div class="row" style="gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Name</label>
                    <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Checkboxes and Device Type row -->
            <div class="row" style="gap: 24px; margin-bottom: 16px; align-items: flex-end; flex-wrap: wrap;">
                <!--<label class="row" style="gap: 8px; align-items: center;">-->
                <!--    <input type="checkbox" name="admin" value="1" @checked(old('admin', $user->admin))>-->
                <!--    <span>Admin</span>-->
                <!--</label>-->
                <label class="row" style="gap: 8px; align-items: center;">
                    <input type="checkbox" name="blocked" value="1" @checked(old('blocked', $user->blocked))>
                    <span>Blocked</span>
                </label>
                <label class="row" style="gap: 8px; align-items: center;">
                    <input type="checkbox" name="therapist" value="1" @checked(old('therapist', $user->therapist))>
                    <span>Therapist</span>
                </label>
                <div style="min-width: 140px;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Device Type</label>
                    <select name="device_type" class="input" style="width: 100%; box-sizing: border-box;">
                        <option value="">-</option>
                        <option value="android" @selected(old('device_type', $user->device_type) === 'android')>android</option>
                        <option value="ios" @selected(old('device_type', $user->device_type) === 'ios')>ios</option>
                    </select>
                </div>
            </div>

            <!-- Password fields row -->
            <div class="row" style="gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">New Password</label>
                    <input class="input" type="password" name="password" style="width:100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Confirm Password</label>
                    <input class="input" type="password" name="password_confirmation" style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Save button row -->
            <div class="row" style="margin-top: 8px;">
                <button class="btn" type="submit">Save</button>
            </div>
        </form>
    </div>
@endsection