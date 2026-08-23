<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:200', Rule::unique('users', 'email')->ignore($request->user()->id)],
        ]);

        $request->user()->fill($data)->save();

        return redirect()->route('profile.edit')->with('updated', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($request->input('current_password'), $request->user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        $request->user()->fill(['password' => $request->input('password')])->save();

        return redirect()->route('profile.edit')->with('updated', 'Password changed.');
    }
}
