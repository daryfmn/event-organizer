@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Settings</div>
            <div class="page-sub">Company info and quotation defaults</div>
        </div>
    </div>

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')
        <div class="settings-grid">

            <div class="card">
                <div class="card-header">
                    <div class="card-header-text">
                        <span class="card-title">Company profile</span>
                        <span class="card-desc">Appears on every quotation</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_name">Company name</label>
                            <input id="company_name" type="text" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required />
                            @error('company_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone', $settings->phone) }}" placeholder="+62 21 ..." />
                            @error('phone')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="text" name="email" value="{{ old('email', $settings->email) }}" placeholder="info@..." />
                            @error('email')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group"></div>
                    </div>
                    <div class="form-row full" style="margin-bottom:0;">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address">{{ old('address', $settings->address) }}</textarea>
                            @error('address')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn-primary sm" type="submit">Save changes</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-header-text">
                        <span class="card-title">Bank details</span>
                        <span class="card-desc">Used for the payment section on quotations</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bank_name">Bank name</label>
                            <input id="bank_name" type="text" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}" />
                            @error('bank_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="bank_branch">Bank branch</label>
                            <input id="bank_branch" type="text" name="bank_branch" value="{{ old('bank_branch', $settings->bank_branch) }}" />
                            @error('bank_branch')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom:0;">
                        <div class="form-group">
                            <label for="account_number">Account number</label>
                            <input id="account_number" type="text" name="account_number" value="{{ old('account_number', $settings->account_number) }}" />
                            @error('account_number')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="account_name">Account name</label>
                            <input id="account_name" type="text" name="account_name" value="{{ old('account_name', $settings->account_name) }}" />
                            @error('account_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn-primary sm" type="submit">Save changes</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-header-text">
                        <span class="card-title">Quotation defaults</span>
                        <span class="card-desc">Starting values for every new quotation</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row" style="margin-bottom:0;">
                        <div class="form-group">
                            <label for="organizing_fee_percent">Default organizing fee (%)</label>
                            <input id="organizing_fee_percent" type="number" step="any" min="0" max="100" name="organizing_fee_percent" value="{{ old('organizing_fee_percent', $settings->organizing_fee_percent) }}" required />
                            @error('organizing_fee_percent')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ppn_percent">Default PPN / tax (%)</label>
                            <input id="ppn_percent" type="number" step="any" min="0" max="100" name="ppn_percent" value="{{ old('ppn_percent', $settings->ppn_percent) }}" required />
                            @error('ppn_percent')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-hint">
                        Changing these only affects new quotations. Existing quotations keep the percentage they were created with, so past totals never shift.
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn-primary sm" type="submit">Save changes</button>
                </div>
            </div>

        </div>
    </form>

    <div class="card" style="margin-top:20px; max-width:820px;">
        <div class="card-header">
            <div class="card-header-text">
                <span class="card-title">Staff accounts</span>
                <span class="card-desc">People who can log in to this application</span>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Job title</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staff as $member)
                    <tr>
                        <td>
                            <div class="staff-cell">
                                @php
                                    $parts = explode(' ', $member->name);
                                    $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1).mb_substr($parts[1] ?? '', 0, 1));
                                @endphp
                                <div class="avatar-sm">{{ $initials }}</div>
                                <span class="font-medium">{{ $member->name }}</span>
                            </div>
                        </td>
                        <td><div class="text-muted">{{ $member->email }}</div></td>
                        <td><span class="role-badge role-{{ $member->role }}">{{ ucfirst($member->role) }}</span></td>
                        <td><div class="text-muted">{{ $member->job_title ?: '—' }}</div></td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="text-muted">No staff accounts.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
