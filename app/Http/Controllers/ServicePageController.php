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
            'services' => Service::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'selected' => $request->query('service'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'address' => ['required', 'string', 'max:1000'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['integer', Rule::exists('services', 'id')->where('is_active', true)],
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
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
