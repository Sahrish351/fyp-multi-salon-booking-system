<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Models\Complaint;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerNotificationEmail;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $clientId = Auth::id();

        $query = Complaint::where('client_id', $clientId)
            ->with(['salon', 'appointment', 'appointment.service'])
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(15)->withQueryString();

        $counts = [
            'total'       => Complaint::where('client_id', $clientId)->count(),
            'pending'     => Complaint::where('client_id', $clientId)->where('status', 'pending')->count(),
            'in_progress' => Complaint::where('client_id', $clientId)->where('status', 'in_progress')->count(),
            'resolved'    => Complaint::where('client_id', $clientId)->where('status', 'resolved')->count(),
            'closed'      => Complaint::where('client_id', $clientId)->where('status', 'closed')->count(),
            'escalated'   => Complaint::where('client_id', $clientId)->where('status', 'escalated')->count(),
            'rejected'    => Complaint::where('client_id', $clientId)->where('status', 'rejected')->count(),
        ];

        return view('client.complaints.index', compact('complaints', 'counts'));
    }

    public function create($appointmentId = null)
    {
        $clientId = Auth::id();

        $appointments = Appointment::where('client_id', $clientId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDoesntHave('complaint')
            ->with(['salon', 'service'])
            ->latest()
            ->get();

        if ($appointments->isEmpty()) {
            return redirect()->route('client.dashboard')
                ->with('error', 'You can only submit a complaint for confirmed or completed appointments.');
        }

        $selectedAppointment = null;
        if ($appointmentId) {
            $selectedAppointment = Appointment::where('client_id', $clientId)
                ->whereIn('status', ['confirmed', 'completed'])
                ->find($appointmentId);
        }

        return view('client.complaints.create', compact('appointments', 'selectedAppointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'type'           => 'required|in:service,staff,payment,product,other',
            'subject'        => 'required|string|max:255',
            'description'    => 'required|string|min:10',
            'image'          => 'nullable|image|max:2048',
        ]);

        try {
            $clientId = Auth::id();

            $appointment = Appointment::where('client_id', $clientId)
                ->whereIn('status', ['confirmed', 'completed'])
                ->findOrFail($request->appointment_id);

            if (Complaint::where('appointment_id', $appointment->id)->exists()) {
                return back()->withInput()->with('error', 'A complaint has already been submitted for this appointment.');
            }

            $subject = ($request->subject === 'Other' && $request->filled('custom_subject')) 
                ? $request->custom_subject 
                : $request->subject;

            $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('complaints', 'public') 
                : null;

            $complaint = Complaint::create([
    'client_id'      => $clientId,
    'salon_id'       => $appointment->salon_id,
    'appointment_id' => $appointment->id,
    'owner_id'       => $appointment->salon->owner_id ?? null,
    'type'           => $request->type,
    'subject'        => $subject,
    'description'    => $request->description,
    'image'          => $imagePath,
    'status'         => 'pending',
]);

            try {
                $client = Auth::user();

                NotificationHelper::send(
                    $appointment->salon_id,
                    'complaint',
                    [
                        'title'   => '⚠️ New Complaint Received',
                        'message' => $client->name . ' submitted a complaint: ' . $subject,
                        'link'    => route('owner.complaints.show', $complaint->id),
                    ]
                );

                $salon = Salon::find($appointment->salon_id);
                $ownerEmail = $salon->owner->email ?? config('mail.from.address');

                if ($ownerEmail) {
                    $emailSubject = "New Complaint Alert: #" . $complaint->id;
                    $emailBody = "A client has filed a complaint regarding a recent appointment at your salon.<br><br>" .
                                 "<strong>Client:</strong> {$client->name}<br>" .
                                 "<strong>Subject:</strong> {$subject}<br>" .
                                 "<strong>Type:</strong> " . ucfirst($request->type) . "<br>" .
                                 "<strong>Description:</strong> {$request->description}<br>" .
                                 "<strong>Booking Reference:</strong> {$appointment->booking_ref}<br><br>" .
                                 "Please log in to your dashboard to review and respond to this complaint.";

                    Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
                }

            } catch (\Exception $e) {
                Log::warning('Complaint notification/email failed: ' . $e->getMessage());
            }

            return redirect()->route('client.complaints.index')
                ->with('success', 'Complaint submitted successfully! Owner will review it shortly.');

        } catch (\Exception $e) {
            Log::error('Complaint Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to submit complaint: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        $complaint->load(['salon', 'appointment', 'appointment.service', 'appointment.stylist', 'replies.user']);

        return view('client.complaints.show', compact('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($complaint->status, ['pending', 'in_progress'])) {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already under review and can no longer be edited.');
        }

        return view('client.complaints.create', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($complaint->status, ['pending', 'in_progress'])) {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already under review and can no longer be edited.');
        }

        $request->validate([
            'type'        => 'required|in:service,staff,payment,product,other',
            'subject'     => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'type'        => $request->type,
            'subject'     => $request->subject,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('complaints', 'public');
        }

        $complaint->update($data);

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Complaint updated successfully.');
    }

    public function acceptResolution(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        if (method_exists($complaint, 'canClientAccept') && !$complaint->canClientAccept()) {
            return redirect()->back()->with('error', 'You cannot accept this resolution.');
        }

        $complaint->update([
            'client_action'      => 'accept',
            'client_actioned_at' => now(),
            'status'             => 'closed',
        ]);

        try {
            $client = Auth::user();

            NotificationHelper::send(
                $complaint->salon_id,
                'complaint',
                [
                    'title'   => '✅ Complaint Resolved',
                    'message' => $client->name . ' accepted the resolution for complaint #' . $complaint->id,
                    'link'    => route('owner.complaints.show', $complaint->id),
                ]
            );

            $salon = Salon::find($complaint->salon_id);
            $ownerEmail = $salon->owner->email ?? config('mail.from.address');

            if ($ownerEmail) {
                $emailSubject = "Complaint Resolved: #" . $complaint->id;
                $emailBody = "The client has accepted your resolution for the following complaint.<br><br>" .
                             "<strong>Client:</strong> {$client->name}<br>" .
                             "<strong>Complaint #:</strong> {$complaint->id}<br><br>" .
                             "This complaint has now been officially marked as <strong>Closed</strong>.";

                Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }

        } catch (\Exception $e) {
            Log::warning('Complaint accept notification/email failed: ' . $e->getMessage());
        }

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Thank you! Complaint has been closed.');
    }

    public function escalate(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        if (method_exists($complaint, 'canClientEscalate') && !$complaint->canClientEscalate()) {
            return redirect()->back()->with('error', 'You cannot escalate this complaint.');
        }

        $complaint->update([
            'client_action'      => 'escalate',
            'client_actioned_at' => now(),
            'status'             => 'escalated',
        ]);

        $client = Auth::user();

        // Dashboard notification (bell icon) — visible in the admin panel
        try {
            app(AdminNotificationController::class)->notifyAdmins(
                'New Escalated Complaint',
                $client->name . ' escalated complaint #' . $complaint->id . ': ' . $complaint->subject,
                route('admin.complaints.show', $complaint->id)
            );
        } catch (\Exception $e) {
            Log::warning('Complaint escalate admin notification failed: ' . $e->getMessage());
        }

        // Email — sent to every admin
        try {
            $admins = User::where('role', 'admin')->get();

            $emailSubject = "Complaint Escalated: #" . $complaint->id;
            $emailBody = "A client was not satisfied with the salon owner's resolution and has escalated their complaint for admin review.<br><br>" .
                         "<strong>Client:</strong> {$client->name}<br>" .
                         "<strong>Subject:</strong> {$complaint->subject}<br>" .
                         "<strong>Complaint #:</strong> {$complaint->id}<br><br>" .
                         "Please log in to the admin panel to review and respond to this complaint.";

            foreach ($admins as $admin) {
                if ($admin->email) {
                    Mail::to($admin->email)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
                }
            }
        } catch (\Exception $e) {
            Log::warning('Complaint escalate admin email failed: ' . $e->getMessage());
        }

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Complaint escalated to Admin. They will review it shortly.');
    }
}