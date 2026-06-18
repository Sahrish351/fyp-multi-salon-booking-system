<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintSubmitController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['salon', 'replies'])
            ->where('client_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('client.complaints.index', compact('complaints'));
    }

    public function create()
{
    $appointments = Appointment::where('client_id', Auth::id())
        ->where('status', 'completed')
        ->latest()
        ->get();

    return view('client.complaints.create', compact('appointments'));
}

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        $appointment = Appointment::where('client_id', Auth::id())
            ->where('id', $request->appointment_id)
            ->firstOrFail();

        if (Complaint::where('appointment_id', $appointment->id)->exists()) {
            return back()->with('error', 'Complaint already submitted for this appointment.');
        }

        $complaint = Complaint::create([
            'client_id' => Auth::id(),
            'appointment_id' => $appointment->id,
            'salon_id' => $appointment->salon_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'open',
            'priority' => 'medium',
            'type' => 'general',
        ]);

        return redirect()->route('client.complaints.index')
            ->with('success', 'Complaint submitted successfully!');
    }

    public function show($id)
    {
        $complaint = Complaint::with(['salon', 'replies', 'appointment'])
            ->where('client_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        return view('client.complaints.show', compact('complaint'));
    }
}