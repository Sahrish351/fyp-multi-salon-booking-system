<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentManageController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['salon', 'stylist', 'service', 'payment'])
            ->where('client_id', Auth::id())
            ->latest()
            ->paginate(15);
        return view('client.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('salon', 'stylist', 'service', 'payment', 'review');
        return view('client.appointments.show', compact('appointment'));
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) abort(403);

        $request->validate(['cancellation_reason' => 'required']);
        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at'        => now(),
        ]);
        $appointment->timeSlot->update(['status' => 'available']);
        return back()->with('success', 'Appointment cancelled.');
    }
}