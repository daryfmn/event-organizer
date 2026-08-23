<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('settings.edit', [
            'settings' => CompanySetting::current() ?? new CompanySetting([
                'organizing_fee_percent' => 10,
                'ppn_percent' => 11,
            ]),
            'staff' => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:200'],
            'address' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'bank_branch' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:200'],
            'organizing_fee_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'ppn_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $settings = CompanySetting::current() ?? new CompanySetting;
        $settings->fill($data)->save();

        return redirect()->route('settings.edit')->with('updated', 'Settings saved.');
    }
}
