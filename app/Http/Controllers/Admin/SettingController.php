<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const KEYS = ['name', 'tagline', 'phone', 'email', 'address', 'hours'];

    public function edit()
    {
        return view('admin.settings.edit', ['keys' => self::KEYS]);
    }

    public function update(Request $r)
    {
        $data = $r->validate([
            'name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'address' => ['nullable', 'string', 'max:300'],
            'hours' => ['nullable', 'string', 'max:200'],
        ]);

        foreach (self::KEYS as $k) {
            Setting::updateOrCreate(['key' => $k], ['value' => $data[$k] ?? null]);
        }

        return back()->with('success', 'Settings saved.');
    }
}