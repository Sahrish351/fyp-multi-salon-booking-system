<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Salon;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::where('status', 'escalated')
            ->orWhere('status', 'closed')
            ->with(['client', 'salon', 'appointment', 'appointment.service'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(20)->withQueryString();

        $counts = [
            'total' => Complaint::where('status', 'escalated')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'counts'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['client', 'salon', 'owner', 'appointment', 'appointment.service', 'appointment.stylist', 'replies.user']);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function respond(Request $request, Complaint $complaint)
    {
        if ($complaint->status !== 'escalated') {
            return redirect()->back()->with('error', 'Only escalated complaints can be reviewed by Admin.');
        }

        $request->validate([
            'admin_response' => 'required|string|min:10',
        ]);

        $complaint->update([
            'admin_response' => $request->admin_response,
            'admin_actioned_at' => now(),
            'status' => 'closed',
        ]);

        // ✅ Client ko notification
        try {
            NotificationHelper::sendToUser(
                $complaint->client_id,
                $complaint->salon_id,
                'complaint',
                [
                    'title' => '📋 Admin Reviewed Your Complaint',
                    'message' => 'Admin has reviewed and closed your complaint: ' . $complaint->subject,
                    'link' => route('client.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Admin response notification failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Complaint closed by Admin.');
    }

    public function close(Complaint $complaint)
    {
        if ($complaint->status !== 'escalated') {
            return redirect()->back()->with('error', 'Only escalated complaints can be closed by Admin.');
        }

        $complaint->update([
            'admin_response' => 'Closed by Admin without additional response.',
            'admin_actioned_at' => now(),
            'status' => 'closed',
        ]);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint closed.');
    }
}