<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerAppointmentController extends Controller
{
   
    public function index(Request $request)
    {
        $stats = [
            'total_today'   => 24,
            'confirmed'     => 18,
            'pending'       => 6,
            'revenue_today' => 2840,
        ];

        $appointments = $this->dummyAppointments();
        $stylists = ['Emma Wilson', 'James Brown', 'Sophia Lee', 'Olivia Martinez', 'Isabella Garcia'];

        return view('owner.appointments.index', compact('stats', 'appointments', 'stylists'));
    }

   
    public function create()
    {
        $services = ['Hair Styling & Color', 'Premium Haircut', 'Luxury Manicure & Pedicure', 'Gold Facial Treatment', 'Full Body Spa Massage'];
        $stylists = ['Emma Wilson', 'James Brown', 'Sophia Lee', 'Olivia Martinez', 'Isabella Garcia'];

        return view('owner.appointments.create', compact('services', 'stylists'));
    }

    public function store(Request $request)
    {
        return redirect()->route('owner.appointments.index')->with('success', 'Appointment booked successfully!');
    }

   
    public function show($appointment)
    {
        $appointmentData = $this->findDummyAppointment($appointment);

        return view('owner.appointments.show', ['appointment' => $appointmentData]);
    }

   
    public function edit($appointment)
    {
        $appointmentData = $this->findDummyAppointment($appointment);
        $services = ['Hair Styling & Color', 'Premium Haircut', 'Luxury Manicure & Pedicure', 'Gold Facial Treatment', 'Full Body Spa Massage'];
        $stylists = ['Emma Wilson', 'James Brown', 'Sophia Lee', 'Olivia Martinez', 'Isabella Garcia'];

        return view('owner.appointments.edit', [
            'appointment' => $appointmentData,
            'services' => $services,
            'stylists' => $stylists,
        ]);
    }

  
    public function update(Request $request, $appointment)
    {
        return redirect()->route('owner.appointments.index')->with('success', 'Appointment updated successfully!');
    }


    public function destroy(Request $request, $appointment)
    {
        return redirect()->route('owner.appointments.index')->with('success', 'Appointment deleted successfully!');
    }

   
    public function approve(Request $request, $id)
    {
        return redirect()->route('owner.appointments.show', ['appointment' => $id])
            ->with('success', 'Appointment confirmed!');
    }

   
    public function complete(Request $request, $id)
    {
        return redirect()->route('owner.appointments.show', ['appointment' => $id])
            ->with('success', 'Appointment marked as completed!');
    }

  
    public function cancel(Request $request, $id)
    {
        return redirect()->route('owner.appointments.show', ['appointment' => $id])
            ->with('success', 'Appointment cancelled.');
    }

   
    public function invoice($id)
    {
        $appointmentData = $this->findDummyAppointment($id);

        return view('owner.appointments.invoice', ['appointment' => $appointmentData]);
    }

   
    public function export(Request $request)
    {
        $appointments = $this->dummyAppointments();

        $csv = "Client,Service,Date,Stylist,Price,Status\n";
        foreach ($appointments as $appt) {
            $csv .= "{$appt['client_name']},{$appt['service']},{$appt['date']},{$appt['stylist']},{$appt['price']},{$appt['status']}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="appointments.csv"');
    }

   
    private function dummyAppointments(): array
    {
        return [
            [
                'id' => 1, 'client_name' => 'Sarah Johnson', 'client_email' => 'sarah.j@email.com', 'client_phone' => '+1 234-567-8901',
                'service' => 'Hair Styling & Color', 'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08',
                'time_range' => '10:00 AM - 11:30 AM', 'start_time_raw' => '10:00', 'end_time_raw' => '11:30',
                'stylist' => 'Emma Wilson', 'price' => 120, 'status' => 'Confirmed', 'notes' => null,
            ],
            [
                'id' => 2, 'client_name' => 'Michael Chen', 'client_email' => 'm.chen@email.com', 'client_phone' => '+1 234-567-8902',
                'service' => 'Premium Haircut', 'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08',
                'time_range' => '11:30 AM - 12:15 PM', 'start_time_raw' => '11:30', 'end_time_raw' => '12:15',
                'stylist' => 'James Brown', 'price' => 85, 'status' => 'Confirmed', 'notes' => null,
            ],
            [
                'id' => 3, 'client_name' => 'Emily Davis', 'client_email' => 'emily.d@email.com', 'client_phone' => '+1 234-567-8903',
                'service' => 'Luxury Manicure & Pedicure', 'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08',
                'time_range' => '02:00 PM - 03:30 PM', 'start_time_raw' => '14:00', 'end_time_raw' => '15:30',
                'stylist' => 'Sophia Lee', 'price' => 95, 'status' => 'Pending', 'notes' => 'First-time client, prefers gel polish.',
            ],
            [
                'id' => 4, 'client_name' => 'David Miller', 'client_email' => 'd.miller@email.com', 'client_phone' => '+1 234-567-8904',
                'service' => 'Gold Facial Treatment', 'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08',
                'time_range' => '03:30 PM - 05:00 PM', 'start_time_raw' => '15:30', 'end_time_raw' => '17:00',
                'stylist' => 'Olivia Martinez', 'price' => 150, 'status' => 'Confirmed', 'notes' => null,
            ],
            [
                'id' => 5, 'client_name' => 'Lisa Anderson', 'client_email' => 'lisa.a@email.com', 'client_phone' => '+1 234-567-8905',
                'service' => 'Full Body Spa Massage', 'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08',
                'time_range' => '04:00 PM - 05:30 PM', 'start_time_raw' => '16:00', 'end_time_raw' => '17:30',
                'stylist' => 'Isabella Garcia', 'price' => 180, 'status' => 'In Progress', 'notes' => null,
            ],
        ];
    }

    
    private function findDummyAppointment($id): array
    {
        $appointments = $this->dummyAppointments();

        foreach ($appointments as $appt) {
            if ($appt['id'] == $id) {
                return $appt;
            }
        }

        return $appointments[0];
    }
}
