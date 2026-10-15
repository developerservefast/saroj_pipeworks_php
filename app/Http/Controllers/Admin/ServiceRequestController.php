<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = ServiceRequest::with('services')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20)->withQueryString();

        $counts = ServiceRequest::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');

        return view('admin.service-requests.index', compact('requests', 'counts'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load('services');

        return view('admin.service-requests.show', ['req' => $serviceRequest]);
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(ServiceRequest::STATUSES))],
            'admin_notes' => ['nullable', 'string'],
        ]);

        if ($data['status'] === 'contacted' && ! $serviceRequest->contacted_at) {
            $data['contacted_at'] = now();
        }

        $serviceRequest->update($data);

        return back()->with('success', 'Request updated.');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();

        return redirect()->route('admin.service-requests.index')->with('success', 'Request deleted.');
    }
}
