<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $thanks = 'Thank you! Your message has been sent. We will call you back soon.';

        // Honeypot: real visitors never fill this hidden field.
        if (filled($request->input('website'))) {
            return redirect()->to(route('contact') . '#contact-form')->with('success', $thanks);
        }

        $phone = preg_replace('/\D/', '', (string) $request->input('phone'));
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        $request->merge([
            'phone' => $phone,
            'name' => trim(preg_replace('/\s+/', ' ', (string) $request->input('name'))),
            'email' => strtolower(trim((string) $request->input('email'))) ?: null,
            'subject' => trim(strip_tags((string) $request->input('subject'))) ?: null,
            'message' => trim(strip_tags((string) $request->input('message'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[\pL][\pL\pM\s.\'\-]*$/u'],
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/', 'not_regex:/^(\d)\1{9}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'subject' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.regex' => 'Use letters only. Numbers and symbols are not allowed.',
            'phone.required' => 'Please enter your mobile number.',
            'phone.regex' => 'Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.',
            'phone.not_regex' => 'Please enter a real mobile number.',
            'email.email' => 'Please enter a valid email address.',
            'message.required' => 'Please write your message.',
            'message.min' => 'Please write a little more (at least 10 characters).',
        ]);

        ContactMessage::create($data);

        return redirect()->to(route('contact') . '#contact-form')->with('success', $thanks);
    }
}