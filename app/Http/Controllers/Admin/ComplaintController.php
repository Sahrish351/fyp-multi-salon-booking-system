<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::with(['client', 'salon'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->latest()
            ->paginate(20);
        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('client', 'salon', 'appointment', 'replies.user');
        return view('admin.complaints.show', compact('complaint'));
    }

    public function reply(Request $request, Complaint $complaint)
    {
        $request->validate(['message' => 'required|string']);
        ComplaintReply::create([
            'complaint_id' => $complaint->id,
            'user_id'      => Auth::id(),
            'message'      => $request->message,
            'sender_type'  => 'admin',
        ]);
        $complaint->update(['status' => 'in_review']);
        return back()->with('success', 'Reply sent.');
    }

    public function resolve(Complaint $complaint)
    {
        $complaint->update(['status' => 'resolved']);
        return back()->with('success', 'Complaint resolved.');
    }
}