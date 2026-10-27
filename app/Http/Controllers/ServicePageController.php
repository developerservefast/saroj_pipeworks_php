<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServicePageController extends Controller
{
    public function index(Request $request)
    {
        return view('services.index', [
            'services' => Service::where('is_active', true)->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString(),
            'allServices' => Service::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'selected' => $request->query('service'),
        ]);
    }

    public function store(Request $request)
    {
        $digits = preg_replace('/\D/', '', (string) $request->input('phone'));
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            $digits = substr($digits, 2);
        } elseif (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
        $request->merge(['phone' => $digits]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[\p{L}][\p{L}\p{M}\s.\x27\-]*$/u'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['integer', Rule::exists('services', 'id')->where('is_active', true)],
        ], [
            'name.required' => 'Please enter your name.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.regex' => 'Use letters only. Numbers and symbols are not allowed.',
            'phone.required' => 'Please enter your mobile number.',
            'phone.regex' => 'Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.',
            'email.email' => 'Please enter a valid email address.',
            'address.required' => 'Please enter your address.',
            'address.min' => 'Please enter your full address (at least 10 characters).',
            'service_ids.required' => 'Please select at least one service.',
            'service_ids.min' => 'Please select at least one service.',
        ]);

               \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $serviceRequest = ServiceRequest::create(collect($data)->except('service_ids')->all());
            $serviceRequest->services()->attach(array_unique($data['service_ids']));
        });

        return redirect()->route('services.index')
            ->with('success', 'Thank you! We have received your request and will call you soon.');
    }
}
