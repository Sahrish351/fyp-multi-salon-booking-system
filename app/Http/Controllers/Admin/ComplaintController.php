<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Helpers\NotificationHelper;
use App\Mail\OwnerNotificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'total'               => Complaint::count(),
            'pending'             => Complaint::where('status', 'pending')->count(),
            'in_progress'         => Complaint::where('status', 'in_progress')->count(),
            'resolved'            => Complaint::where('status', 'resolved')->count(),
            'closed'              => Complaint::where('status', 'closed')->count(),
            'escalated'           => Complaint::where('status', 'escalated')->count(),
            'rejected'            => Complaint::where('status', 'rejected')->count(),
            'awaiting_owner'      => Complaint::where('status', 'awaiting_owner')->count(),
            'owner_replied_admin' => Complaint::where('status', 'owner_replied_admin')->count(),
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

   
    public function askOwner(Request $request, Complaint $complaint)
    {
        if (!$complaint->canAdminAskOwner()) {
            return redirect()->back()->with('error', 'You can only ask the owner on an escalated complaint.');
        }

        $request->validate([
            'admin_question'  => 'required|string|min:5',
            'deadline_hours'  => 'required|in:24,48,72',
        ]);

        $complaint->update([
            'admin_question'    => $request->admin_question,
            'admin_question_at' => now(),
            'owner_deadline_at' => now()->addHours((int) $request->deadline_hours),
            'admin_id'          => Auth::id(),
            'status'            => 'awaiting_owner',
        ]);

        try {
            $complaint->loadMissing(['owner', 'salon']);

            if ($complaint->owner_id) {
                NotificationHelper::sendToUser(
                    $complaint->owner_id,
                    $complaint->salon_id,
                    'complaint',
                    [
                        'title'   => '❓ Admin Needs Your Input on a Complaint',
                        'message' => 'Admin has asked for your statement on complaint: ' . $complaint->subject,
                        'link'    => route('owner.complaints.show', $complaint->id),
                    ]
                );

                $ownerEmail = $complaint->owner->email ?? null;

                if ($ownerEmail) {
                    $emailSubject = "Admin Needs Your Response: Complaint #" . $complaint->id;
                    $emailBody = "Hello " . $complaint->owner->name . ",<br><br>" .
                                 "The client was not satisfied with your previous response and escalated this complaint to admin.<br>" .
                                 "Before making a final decision, admin would like to hear your side.<br><br>" .
                                 "<strong>Complaint:</strong> {$complaint->subject}<br><br>" .
                                 "<strong>Admin's Question:</strong><br>{$request->admin_question}<br><br>" .
                                 "<strong>Please respond within:</strong> {$request->deadline_hours} hours.<br><br>" .
                                 "Please log in to your dashboard to submit your statement.";

                    Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
                }
            }
        } catch (\Exception $e) {
            Log::warning('Admin ask-owner notification/email failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Question sent to owner. Waiting for their statement.');
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
            $complaint->loadMissing(['client', 'salon', 'owner']);

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

            $clientEmail = $complaint->client->email ?? null;

            if ($clientEmail) {
                $emailSubject = "Admin Response to Your Escalated Complaint: #" . $complaint->id;
                $emailBody = "Hello " . $complaint->client->name . ",<br><br>" .
                             "Our admin team has reviewed your escalated complaint and provided a response.<br><br>" .
                             "<strong>Complaint:</strong> {$complaint->subject}<br>" .
                             "<strong>Salon:</strong> " . ($complaint->salon->name ?? '-') . "<br>" .
                             "<strong>Admin's Response:</strong><br>{$request->admin_response}<br><br>" .
                             "This complaint has now been closed. Please log in to your dashboard for full details.";

                Mail::to($clientEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }

            
            if ($complaint->owner_id) {
                NotificationHelper::sendToUser(
                    $complaint->owner_id,
                    $complaint->salon_id,
                    'complaint',
                    [
                        'title'   => '🛡️ Admin Made a Final Decision',
                        'message' => 'Admin has given a final decision on complaint: ' . $complaint->subject,
                        'link'    => route('owner.complaints.show', $complaint->id),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::warning('Admin complaint respond notification/email failed: ' . $e->getMessage());
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
            $complaint->loadMissing(['client', 'salon', 'owner']);

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

            $clientEmail = $complaint->client->email ?? null;

            if ($clientEmail) {
                $emailSubject = "Your Complaint Has Been Closed: #" . $complaint->id;
                $emailBody = "Hello " . $complaint->client->name . ",<br><br>" .
                             "Our admin team has reviewed your escalated complaint and closed it.<br><br>" .
                             "<strong>Complaint:</strong> {$complaint->subject}<br>" .
                             "<strong>Salon:</strong> " . ($complaint->salon->name ?? '-') . "<br><br>" .
                             "If you have any further questions, please contact our support team.";

                Mail::to($clientEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }

            if ($complaint->owner_id) {
                NotificationHelper::sendToUser(
                    $complaint->owner_id,
                    $complaint->salon_id,
                    'complaint',
                    [
                        'title'   => '✅ Complaint Closed by Admin',
                        'message' => 'Complaint "' . $complaint->subject . '" has been closed by admin.',
                        'link'    => route('owner.complaints.show', $complaint->id),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::warning('Admin complaint close notification/email failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint closed.');
    }
}