<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(20)->withQueryString();
        $counts = ContactMessage::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');

        return view('admin.contact-messages.index', compact('messages', 'counts'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('admin.contact-messages.show', ['msg' => $contactMessage]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted.');
    }
}