<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintSubmitController extends Controller
{
    /**
     * List all complaints filed by the currently logged in client.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['salon', 'replies'])
            ->where('client_id', Auth::id());

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        return view('client.complaints.index', compact('complaints'));
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
            ->latest()
            ->get();

        $appointment = null;
        if ($appointmentId) {
            $appointment = Appointment::where('client_id', Auth::id())
                ->whereIn('status', ['confirmed', 'completed'])
                ->where('id', $appointmentId)
                ->firstOrFail();
        }

        return view('client.complaints.create', compact('appointments', 'appointment'));
    }

    /**
     * Store a newly filed complaint.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'subject'        => 'required|string|max:255',
            'description'    => 'required|string|min:10',
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

        Complaint::create([
            'client_id'      => Auth::id(),
            'appointment_id' => $appointment->id,
            'salon_id'       => $appointment->salon_id,
            'subject'        => $subject,
            'description'    => $request->description,
            'status'         => 'open',
            'priority'       => 'medium',
            'type'           => 'general',
        ]);

        return redirect()->route('client.complaints.index')
            ->with('success', 'Your complaint has been submitted successfully!');
    }

    /**
     * Show a single complaint (with its conversation/replies).
     */
    public function show($id)
    {
        $complaint = Complaint::with(['salon', 'replies.user', 'appointment'])
            ->where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('client.complaints.show', compact('complaint'));
    }

    /**
     * Show the edit form — only while the complaint is still 'open'.
     */
    public function edit($id)
    {
        $complaint = Complaint::where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($complaint->status !== 'open') {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already being reviewed and can no longer be edited.');
        }

        return view('client.complaints.edit', compact('complaint'));
    }

    /**
     * Update subject/description of an 'open' complaint.
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($complaint->status !== 'open') {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already being reviewed and can no longer be edited.');
        }

        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        $complaint->update([
            'subject'     => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Your complaint has been updated successfully!');
    }

    /**
     * Delete/withdraw an 'open' complaint.
     */
    public function destroy($id)
    {
        $complaint = Complaint::where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($complaint->status !== 'open') {
            return redirect()->route('client.complaints.show', $complaint->id)
                ->with('error', 'This complaint is already being reviewed and can no longer be deleted.');
        }

        $complaint->delete();

        return redirect()->route('client.complaints.index')
            ->with('success', 'Your complaint has been withdrawn.');
    }
}