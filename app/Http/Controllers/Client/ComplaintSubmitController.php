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
     * Supports the status filter pills on the index page
     * (?status=open|in_review|resolved|rejected|closed).
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
     * Show the "file a new complaint" form. Only completed appointments
     * are offered, since you can only complain about a finished service.
     */
    public function create()
    {
        $appointments = Appointment::where('client_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('client.complaints.create', compact('appointments'));
    }

    /**
     * Store a newly filed complaint.
     * NOTE: the appointment is chosen via the dropdown in the form
     * (appointment_id in the request body), NOT via a route parameter —
     * this matches the route definition: POST /client/complaints (no {id}).
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'subject'        => 'required|string|max:255',
            'description'    => 'required|string|min:10',
        ]);

        $appointment = Appointment::where('client_id', Auth::id())
            ->where('id', $request->appointment_id)
            ->firstOrFail();

        if (Complaint::where('appointment_id', $appointment->id)->exists()) {
            return back()->withInput()->with('error', 'A complaint has already been submitted for this appointment.');
        }

        Complaint::create([
            'client_id'      => Auth::id(),
            'appointment_id' => $appointment->id,
            'salon_id'       => $appointment->salon_id,
            'subject'        => $request->subject,
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
     * Once an owner/admin has started reviewing it (status moved to
     * in_review/resolved/rejected/closed), the client can no longer
     * change what they originally reported.
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
     * Delete/withdraw an 'open' complaint. Once it's being reviewed,
     * the client can no longer remove it (there's now a record other
     * people are acting on).
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