<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Appointment;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                'type'           => $request->type,
                'subject'        => $subject,
                'description'    => $request->description,
                'image'          => $imagePath,
                'status'         => 'pending',
            ]);

            try {
                NotificationHelper::send(
                    $appointment->salon_id,
                    'complaint',
                    [
                        'title'   => '⚠️ New Complaint Received',
                        'message' => Auth::user()->name . ' submitted a complaint: ' . $subject,
                        'link'    => route('owner.complaints.show', $complaint->id),
                    ]
                );
            } catch (\Exception $e) {
                Log::warning('Complaint notification failed: ' . $e->getMessage());
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

        return view('client.complaints.edit', compact('complaint'));
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
            NotificationHelper::send(
                $complaint->salon_id,
                'complaint',
                [
                    'title'   => '✅ Complaint Resolved',
                    'message' => Auth::user()->name . ' accepted the resolution for complaint #' . $complaint->id,
                    'link'    => route('owner.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Complaint accept notification failed: ' . $e->getMessage());
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

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Complaint escalated to Admin. They will review it shortly.');
    }
}