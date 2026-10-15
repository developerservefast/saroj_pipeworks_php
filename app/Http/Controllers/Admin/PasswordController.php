<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('admin.password.edit');
    }

    public function update(Request $r)
    {
        $r->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:10', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'The current password is not correct.',
            'password.different' => 'The new password must be different from the current one.',
        ]);

        $r->user()->update(['password' => Hash::make($r->password)]);

        return back()->with('success', 'Password changed.');
    }
}