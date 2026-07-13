<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Salon;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OwnerComplaintController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    public function index(Request $request)
    {
        $salon = $this->getOwnerSalon();
        if (!$salon) {
            return redirect()->route('owner.salons.create')
                ->with('error', 'Please create your salon first.');
        }

        $query = Complaint::where('salon_id', $salon->id)
            ->with(['client', 'appointment', 'appointment.service'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $complaints = $query->paginate(20)->withQueryString();

        $counts = [
            'total' => Complaint::where('salon_id', $salon->id)->count(),
            'pending' => Complaint::where('salon_id', $salon->id)->where('status', 'pending')->count(),
            'in_progress' => Complaint::where('salon_id', $salon->id)->where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('salon_id', $salon->id)->where('status', 'resolved')->count(),
            'closed' => Complaint::where('salon_id', $salon->id)->where('status', 'closed')->count(),
            'escalated' => Complaint::where('salon_id', $salon->id)->where('status', 'escalated')->count(),
            'rejected' => Complaint::where('salon_id', $salon->id)->where('status', 'rejected')->count(),
        ];

        return view('owner.complaints.index', compact('complaints', 'counts'));
    }

    public function show(Complaint $complaint)
    {
        $salon = $this->getOwnerSalon();
        if ($complaint->salon_id !== $salon->id) {
            abort(403);
        }

        $complaint->load(['client', 'appointment', 'appointment.service', 'appointment.stylist', 'replies.user']);

        return view('owner.complaints.show', compact('complaint'));
    }

    public function reply(Request $request, Complaint $complaint)
    {
        $salon = $this->getOwnerSalon();
        if ($complaint->salon_id !== $salon->id) {
            abort(403);
        }

        $request->validate([
            'owner_reply' => 'required|string|min:5',
        ]);

        $complaint->update([
            'owner_reply' => $request->owner_reply,
            'owner_replied_at' => now(),
            'status' => 'in_progress',
        ]);

        // ✅ Client ko notification
        try {
            NotificationHelper::sendToUser(
                $complaint->client_id,
                $salon->id,
                'complaint',
                [
                    'title' => '💬 Owner Replied to Your Complaint',
                    'message' => 'Owner has replied to your complaint: ' . $complaint->subject,
                    'link' => route('client.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Complaint reply notification failed: ' . $e->getMessage());
        }

        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Reply sent to client.');
    }

    public function markInProgress(Complaint $complaint)
    {
        $salon = $this->getOwnerSalon();
        if ($complaint->salon_id !== $salon->id) {
            abort(403);
        }

        if (!$complaint->isPending()) {
            return redirect()->back()->with('error', 'Only pending complaints can be marked in progress.');
        }

        $complaint->update(['status' => 'in_progress']);

        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Complaint marked as In Progress.');
    }

    public function resolve(Complaint $complaint)
    {
        $salon = $this->getOwnerSalon();
        if ($complaint->salon_id !== $salon->id) {
            abort(403);
        }

        if (!$complaint->isInProgress()) {
            return redirect()->back()->with('error', 'Only in-progress complaints can be resolved.');
        }

        $complaint->update(['status' => 'resolved']);

        // ✅ Client ko notification
        try {
            NotificationHelper::sendToUser(
                $complaint->client_id,
                $salon->id,
                'complaint',
                [
                    'title' => '✅ Complaint Resolved',
                    'message' => 'Your complaint "' . $complaint->subject . '" has been resolved. Please review and accept or escalate.',
                    'link' => route('client.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Complaint resolve notification failed: ' . $e->getMessage());
        }

        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Complaint marked as Resolved. Client has been notified.');
    }

    public function reject(Request $request, Complaint $complaint)
    {
        $salon = $this->getOwnerSalon();
        if ($complaint->salon_id !== $salon->id) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $complaint->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        // ✅ Client ko notification
        try {
            NotificationHelper::sendToUser(
                $complaint->client_id,
                $salon->id,
                'complaint',
                [
                    'title' => '❌ Complaint Rejected',
                    'message' => 'Your complaint "' . $complaint->subject . '" was rejected. Reason: ' . $request->rejection_reason,
                    'link' => route('client.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Complaint reject notification failed: ' . $e->getMessage());
        }

        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Complaint rejected.');
    }
}