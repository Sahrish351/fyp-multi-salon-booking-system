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

        //  Stats for summary cards
        $stats = [
            'total' => Complaint::count(),
            'open' => Complaint::where('status', 'open')->count(),
            'in_review' => Complaint::where('status', 'in_review')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
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

    // ADD THIS METHOD – Update Status
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:open,in_review,resolved,closed'
        ]);

        $complaint->update(['status' => $request->status]);

        return back()->with('success', 'Complaint status updated successfully.');
    }

    // ADD THIS METHOD – Delete Complaint
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }
}