<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintSubmitController extends Controller
{
    public function create(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) abort(403);
        
        if ($appointment->complaint) {
            return redirect()->route('client.appointments.show', $appointment)->with('error', 'You already have a complaint for this appointment.');
        }

        return view('client.complaints.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) abort(403);

        $request->validate([
            'type'        => 'required|in:service_quality,staff_behavior,payment_issue,booking_issue,other',
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Complaint::create([
            'client_id'      => Auth::id(),
            'salon_id'       => $appointment->salon_id,
            'appointment_id' => $appointment->id,
            'type'           => $request->type,
            'subject'        => $request->subject,
            'description'    => $request->description,
            'status'         => 'open',
            'priority'       => 'medium',
        ]);

        return redirect()->route('client.appointments.show', $appointment)->with('success', 'Complaint submitted! Admin will review it.');
    }

    public function index()
    {
        $complaints = Complaint::with(['salon', 'appointment'])
            ->where('client_id', Auth::id())
            ->latest()
            ->paginate(15);
        
        return view('client.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->client_id !== Auth::id()) abort(403);
        
        $complaint->load('replies.user');
        return view('client.complaints.show', compact('complaint'));
    }
}