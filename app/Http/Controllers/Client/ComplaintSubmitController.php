<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Appointment;
use App\Models\Salon;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintSubmitController extends Controller
{
    /**
     * List all complaints filed by the currently logged in client.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['salon', 'appointment', 'appointment.service'])
            ->where('client_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(10)->withQueryString();

        $counts = [
            'total' => Complaint::where('client_id', Auth::id())->count(),
            'pending' => Complaint::where('client_id', Auth::id())->where('status', 'pending')->count(),
            'in_progress' => Complaint::where('client_id', Auth::id())->where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('client_id', Auth::id())->where('status', 'resolved')->count(),
            'closed' => Complaint::where('client_id', Auth::id())->where('status', 'closed')->count(),
            'escalated' => Complaint::where('client_id', Auth::id())->where('status', 'escalated')->count(),
            'rejected' => Complaint::where('client_id', Auth::id())->where('status', 'rejected')->count(),
        ];

        return view('client.complaints.index', compact('complaints', 'counts'));
    }

    /**
     * Show the "file a new complaint" form.
     * ✅ Only confirmed and completed appointments.
     * ✅ If appointmentId is passed, auto-select that appointment.
     */
    public function create($appointmentId = null)
    {
        $appointments = Appointment::where('client_id', Auth::id())
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDoesntHave('complaint')
            ->with(['salon', 'service'])
            ->latest()
            ->get();

        $appointment = null;
        if ($appointmentId) {
            $appointment = Appointment::where('client_id', Auth::id())
                ->whereIn('status', ['confirmed', 'completed'])
                ->where('id', $appointmentId)
                ->firstOrFail();
        }

        // Check existing complaints for these appointments
        $appointmentIds = $appointments->pluck('id')->toArray();
        $existingComplaints = Complaint::whereIn('appointment_id', $appointmentIds)
            ->where('client_id', Auth::id())
            ->pluck('appointment_id')
            ->toArray();

        return view('client.complaints.create', compact('appointments', 'appointment', 'existingComplaints'));
    }

    /**
     * Store a newly filed complaint.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'appointment_id' => 'required|exists:appointments,id',
                'type' => 'required|in:service,staff,payment,product,other',
                'subject' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'image' => 'nullable|image|max:2048',
            ]);

            // ✅ Handle "Other" subject
            $subject = $request->subject;
            if ($subject === 'Other' && $request->filled('custom_subject')) {
                $subject = $request->custom_subject;
            }

            $appointment = Appointment::where('client_id', Auth::id())
                ->where('id', $request->appointment_id)
                ->firstOrFail();

            // ✅ Check if complaint already exists
            if (Complaint::where('appointment_id', $appointment->id)->exists()) {
                return back()->withInput()->with('error', 'A complaint has already been submitted for this appointment.');
            }

            // ✅ Check if appointment status allows complaint
            if (!in_array($appointment->status, ['confirmed', 'completed'])) {
                return back()->withInput()->with('error', 'Complaints can only be filed for confirmed or completed appointments.');
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('complaints', 'public');
            }

            // ✅ CORRECT VALUES - STATUS AUR TYPE SAHI DAALO
            $complaint = Complaint::create([
                'client_id' => Auth::id(),
                'appointment_id' => $appointment->id,
                'salon_id' => $appointment->salon_id,
                'type' => $request->type, // ✅ 'service', 'staff', 'payment', 'product', 'other'
                'subject' => $subject,
                'description' => $request->description,
                'image' => $imagePath,
                'status' => 'pending', // ✅ 'pending' (not 'open')
            ]);

            // ✅ Owner ko notification
            try {
                NotificationHelper::send(
                    $appointment->salon_id,
                    'complaint',
                    [
                        'title' => '⚠️ New Complaint Received',
                        'message' => Auth::user()->name . ' submitted a complaint: ' . $subject,
                        'link' => route('owner.complaints.show', $complaint->id),
                    ]
                );
            } catch (\Exception $e) {
                Log::warning('Complaint notification failed: ' . $e->getMessage());
            }

            return redirect()->route('client.complaints.index')
                ->with('success', 'Your complaint has been submitted successfully! Owner will review it shortly.');

        } catch (\Exception $e) {
            Log::error('Complaint Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to submit complaint: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show a single complaint.
     */
    public function show($id)
    {
        $complaint = Complaint::with(['salon', 'appointment', 'appointment.service', 'appointment.stylist'])
            ->where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('client.complaints.show', compact('complaint'));
    }

    /**
     * Show the edit form — only while the complaint is still 'pending' or 'in_progress'.
     */
    public function edit($id)
    {
        $complaint = Complaint::where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array($complaint->status, ['pending', 'in_progress'])) {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already being reviewed and can no longer be edited.');
        }

        $appointments = Appointment::where('client_id', Auth::id())
            ->where('status', 'completed')
            ->with(['salon', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('client.complaints.edit', compact('complaint', 'appointments'));
    }

    /**
     * Update subject/description of a complaint.
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array($complaint->status, ['pending', 'in_progress'])) {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already being reviewed and can no longer be edited.');
        }

        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'type' => 'required|in:service,staff,payment,product,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'appointment_id' => $request->appointment_id,
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('complaints', 'public');
            $data['image'] = $imagePath;
        }

        $complaint->update($data);

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Your complaint has been updated successfully!');
    }

    /**
     * Delete/withdraw a complaint.
     */
    public function destroy($id)
    {
        $complaint = Complaint::where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array($complaint->status, ['pending', 'in_progress'])) {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already being reviewed and can no longer be deleted.');
        }

        $complaint->delete();

        return redirect()->route('client.complaints.index')
            ->with('success', 'Your complaint has been withdrawn.');
    }

    /**
     * Accept resolution from owner.
     */
    public function acceptResolution(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        if (!$complaint->canClientAccept()) {
            return redirect()->back()->with('error', 'You cannot accept this resolution.');
        }

        $complaint->update([
            'client_action' => 'accept',
            'client_actioned_at' => now(),
            'status' => 'closed',
        ]);

        try {
            NotificationHelper::send(
                $complaint->salon_id,
                'complaint',
                [
                    'title' => '✅ Complaint Resolved',
                    'message' => Auth::user()->name . ' accepted the resolution for complaint #' . $complaint->id,
                    'link' => route('owner.complaints.show', $complaint->id),
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Complaint accept notification failed: ' . $e->getMessage());
        }

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Thank you! Complaint has been closed.');
    }

    /**
     * Escalate complaint to admin.
     */
    public function escalate(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) {
            abort(403);
        }

        if (!$complaint->canClientEscalate()) {
            return redirect()->back()->with('error', 'You cannot escalate this complaint.');
        }

        $complaint->update([
            'client_action' => 'escalate',
            'client_actioned_at' => now(),
            'status' => 'escalated',
        ]);

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Complaint escalated to Admin. They will review it shortly.');
    }
}