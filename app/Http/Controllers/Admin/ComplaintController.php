<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::with(['client', 'salon'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhereHas('client', function ($c) use ($search) {
                      $c->where('name', 'like', '%' . $search . '%');
                  });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved'    => Complaint::where('status', 'resolved')->count(),
            'closed'      => Complaint::where('status', 'closed')->count(),
            'escalated'   => Complaint::where('status', 'escalated')->count(),
            'rejected'    => Complaint::where('status', 'rejected')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['client', 'salon', 'appointment', 'owner']);
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|string',
            'resolution_notes' => 'nullable|string',
        ]);

        $complaint->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'admin_actioned_at' => now(),
            'admin_id' => Auth::id(),
        ]);

        return redirect()->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Complaint updated successfully.');
    }

    public function respond(Request $request, Complaint $complaint)
    {
        $request->validate([
            'admin_response' => 'required|string|min:5',
        ]);

        $complaint->update([
            'admin_response'    => $request->admin_response,
            'admin_actioned_at' => now(),
            'admin_id'          => Auth::id(),
            'status'            => 'closed',
        ]);

        try {
            NotificationHelper::sendToUser(
                $complaint->client_id,
                $complaint->salon_id,
                'complaint',
                [
                    'title'   => '🛡️ Admin Responded to Your Complaint',
                    'message' => 'Admin has reviewed and responded to your complaint: ' . $complaint->subject,
                    'link'    => route('client.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Admin complaint respond notification failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Response sent and complaint closed.');
    }

    public function close(Complaint $complaint)
    {
        $complaint->update([
            'admin_actioned_at' => now(),
            'admin_id'          => Auth::id(),
            'status'            => 'closed',
        ]);

        try {
            NotificationHelper::sendToUser(
                $complaint->client_id,
                $complaint->salon_id,
                'complaint',
                [
                    'title'   => '✅ Complaint Closed by Admin',
                    'message' => 'Your escalated complaint "' . $complaint->subject . '" has been closed by admin.',
                    'link'    => route('client.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Admin complaint close notification failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint closed.');
    }
}