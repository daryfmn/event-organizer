@extends('layouts.app')

@section('title', 'My Profile')

@php
    $parts = explode(' ', $user->name);
    $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1).mb_substr($parts[1] ?? '', 0, 1));
@endphp

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">My Profile</div>
            <div class="page-sub">Your account details and password</div>
        </div>
    </div>

    <div class="settings-grid">

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="card-header-text">
                        <span class="card-title">Personal info</span>
                        <span class="card-desc">How you appear across the application</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="avatar-upload">
                        <div class="avatar-lg">{{ $initials }}</div>
                        <div>
                            <div class="field-hint" style="margin-top:0;">Your initials are generated from your name</div>
                        </div>
                    </div>

                    <div class="role-row">
                        <span class="role-row-label">Role — set by an admin, contact them to change it</span>
                        <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required />
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="job_title">Job title</label>
                            <input id="job_title" type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}" placeholder="e.g. Project Officer" />
                            @error('job_title')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row full" style="margin-bottom:0;">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="text" name="email" value="{{ old('email', $user->email) }}" required />
                            @error('email')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn-primary sm" type="submit">Save changes</button>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="card-header-text">
                        <span class="card-title">Change password</span>
                        <span class="card-desc">Use a separate save for security</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row full">
                        <div class="form-group">
                            <label for="current_password">Current password</label>
                            <input id="current_password" type="password" name="current_password" />
                            @error('current_password')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom:0;">
                        <div class="form-group">
                            <label for="password">New password</label>
                            <input id="password" type="password" name="password" />
                            @error('password')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm new password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" />
                        </div>
                    </div>
                    <div class="field-hint">Minimum 8 characters. Only fill this in when you want to change your password.</div>
                </div>
                <div class="card-footer">
                    <button class="btn-primary sm" type="submit">Update password</button>
                </div>
            </div>
        </form>

    </div>
@endsection
