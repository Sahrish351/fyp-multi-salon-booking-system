<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentMonitorController extends Controller
{
    // INDEX - Show all appointments
    public function index(Request $request)
    {
        $appointments = Appointment::with(['client', 'salon', 'stylist', 'service', 'payment'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date, fn($q) => $q->whereDate('appointment_date', $request->date))
            ->when($request->search, fn($q) => $q->whereHas('client', fn($q2) =>
                $q2->where('name', 'like', '%' . $request->search . '%')))
            ->latest()
            ->paginate(20);
            
        return view('admin.appointments.index', compact('appointments'));
    }

    // SHOW - Show single appointment details
    public function show($id)
    {
        $appointment = Appointment::with(['client', 'salon', 'stylist', 'service', 'payment'])
            ->findOrFail($id);
            
        return view('admin.appointments.show', compact('appointment'));
    }

    // EXPORT - Export appointments to CSV
    public function export(Request $request)
    {
        $appointments = Appointment::with(['client', 'salon', 'service'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date, fn($q) => $q->whereDate('appointment_date', $request->date))
            ->get();

        $filename = "appointments_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // CSV Headers
        fputcsv($handle, ['ID', 'Client Name', 'Client Phone', 'Salon Name', 'Service', 'Stylist', 'Date', 'Time', 'Amount', 'Status', 'Payment Status']);

        // CSV Data
        foreach($appointments as $appointment) {
            fputcsv($handle, [
                $appointment->id,
                $appointment->client->name ?? 'N/A',
                $appointment->client->phone ?? 'N/A',
                $appointment->salon->name ?? 'N/A',
                $appointment->service->name ?? 'N/A',
                $appointment->stylist->name ?? 'N/A',
                $appointment->appointment_date,
                $appointment->start_time ?? 'N/A',
                $appointment->total_amount ?? 0,
                $appointment->status,
                $appointment->payment->status ?? 'pending'
            ]);
        }

        fclose($handle);
        exit;
    }

    // COMPLETE - Mark appointment as completed
    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'completed']);
        
        return redirect()->back()->with('success', 'Appointment marked as completed!');
    }
}