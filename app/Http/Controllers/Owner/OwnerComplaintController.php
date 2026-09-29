<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Models\Complaint;
use App\Models\Salon;
use App\Models\User;
use App\Helpers\NotificationHelper;
use App\Mail\OwnerNotificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'total'               => Complaint::where('salon_id', $salon->id)->count(),
            'pending'             => Complaint::where('salon_id', $salon->id)->where('status', 'pending')->count(),
            'in_progress'         => Complaint::where('salon_id', $salon->id)->where('status', 'in_progress')->count(),
            'resolved'            => Complaint::where('salon_id', $salon->id)->where('status', 'resolved')->count(),
            'closed'              => Complaint::where('salon_id', $salon->id)->where('status', 'closed')->count(),
            'escalated'           => Complaint::where('salon_id', $salon->id)->where('status', 'escalated')->count(),
            'rejected'            => Complaint::where('salon_id', $salon->id)->where('status', 'rejected')->count(),
            'awaiting_owner'      => Complaint::where('salon_id', $salon->id)->where('status', 'awaiting_owner')->count(),
            'owner_replied_admin' => Complaint::where('salon_id', $salon->id)->where('status', 'owner_replied_admin')->count(),
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
 
        try {
            $complaint->loadMissing('client');
 
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
 
            $clientEmail = $complaint->client->email ?? null;
 
            if ($clientEmail) {
                $emailSubject = "Update on Your Complaint #" . $complaint->id;
                $emailBody = "Hello " . $complaint->client->name . ",<br><br>" .
                             "The salon owner has replied to your complaint.<br><br>" .
                             "<strong>Complaint:</strong> {$complaint->subject}<br>" .
                             "<strong>Salon:</strong> {$salon->name}<br>" .
                             "<strong>Owner's Reply:</strong><br>{$request->owner_reply}<br><br>" .
                             "Please log in to your dashboard to view the full conversation and next steps.";
 
                Mail::to($clientEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }
        } catch (\Exception $e) {
            Log::warning('Complaint reply notification/email failed: ' . $e->getMessage());
        }
 
        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Reply sent to client.');
    }
 
    public function replyToAdmin(Request $request, Complaint $complaint)
    {
        $salon = $this->getOwnerSalon();
        if ($complaint->salon_id !== $salon->id) {
            abort(403);
        }
 
        if (!$complaint->canOwnerReplyToAdmin()) {
            return redirect()->back()->with('error', 'There is no pending question from admin on this complaint.');
        }
 
        $request->validate([
            'owner_statement' => 'required|string|min:5',
        ]);
 
        $complaint->update([
            'owner_statement'    => $request->owner_statement,
            'owner_statement_at' => now(),
            'status'             => 'owner_replied_admin',
        ]);
 
        $owner = Auth::user();
 
        // 1) In-app notification (alag try, taake mail is se na ruke)
        try {
            app(AdminNotificationController::class)->notifyAdmins(
                'Owner Responded to Complaint',
                $owner->name . ' submitted a statement for complaint #' . $complaint->id . ': ' . $complaint->subject,
                route('admin.complaints.show', $complaint->id)
            );
        } catch (\Exception $e) {
            Log::warning('Owner reply-to-admin notification failed: ' . $e->getMessage());
        }
 
        // 2) Email (alag try)
        try {
            $admins = User::where('role', 'admin')->get();
 
            $emailSubject = "Owner Responded: Complaint #" . $complaint->id;
            $emailBody = "The salon owner has submitted their statement regarding an escalated complaint.<br><br>" .
                         "<strong>Owner:</strong> {$owner->name}<br>" .
                         "<strong>Complaint:</strong> {$complaint->subject}<br><br>" .
                         "<strong>Owner's Statement:</strong><br>{$request->owner_statement}<br><br>" .
                         "Please log in to the admin panel to review both sides and give a final decision.";
 
            foreach ($admins as $admin) {
                if ($admin->email) {
                    Mail::to($admin->email)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
                }
            }
        } catch (\Exception $e) {
            Log::warning('Owner reply-to-admin email failed: ' . $e->getMessage());
        }
 
        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Your statement has been sent to admin.');
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
 
        try {
            $complaint->loadMissing('client');
 
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
 
            $clientEmail = $complaint->client->email ?? null;
 
            if ($clientEmail) {
                $emailSubject = "Your Complaint Has Been Resolved: #" . $complaint->id;
                $emailBody = "Hello " . $complaint->client->name . ",<br><br>" .
                             "The salon owner has marked your complaint as resolved.<br><br>" .
                             "<strong>Complaint:</strong> {$complaint->subject}<br>" .
                             "<strong>Salon:</strong> {$salon->name}<br><br>" .
                             "Please log in to your account to review the resolution. You can either accept it and close the complaint, " .
                             "or escalate it to our admin team if you are not satisfied.";
 
                Mail::to($clientEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }
        } catch (\Exception $e) {
            Log::warning('Complaint resolve notification/email failed: ' . $e->getMessage());
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
 
        try {
            $complaint->loadMissing('client');
 
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
 
            $clientEmail = $complaint->client->email ?? null;
 
            if ($clientEmail) {
                $emailSubject = "Your Complaint Was Rejected: #" . $complaint->id;
                $emailBody = "Hello " . $complaint->client->name . ",<br><br>" .
                             "The salon owner has reviewed and rejected your complaint.<br><br>" .
                             "<strong>Complaint:</strong> {$complaint->subject}<br>" .
                             "<strong>Salon:</strong> {$salon->name}<br>" .
                             "<strong>Reason:</strong> {$request->rejection_reason}<br><br>" .
                             "If you believe this decision is incorrect, you can escalate this complaint to our admin team from your dashboard.";
 
                Mail::to($clientEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }
        } catch (\Exception $e) {
            Log::warning('Complaint reject notification/email failed: ' . $e->getMessage());
        }
 
        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Complaint rejected.');
    }
}
 