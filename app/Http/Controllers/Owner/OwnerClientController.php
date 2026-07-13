<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class OwnerClientController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            // ✅ SAB CLIENTS LEKAR AO (JO ROLE = CLIENT HAIN)
            $clientsRaw = User::where('role', 'client')
                ->orderBy('name')
                ->get();

            $clients = $clientsRaw->map(function ($user) use ($salon) {
                // Appointments count
                $appointments = Appointment::where('salon_id', $salon->id)
                    ->where('client_id', $user->id)
                    ->orderBy('appointment_date', 'desc')
                    ->get();

                $totalVisits = $appointments->count();

                // Total spent
                $totalSpent = Payment::where('salon_id', $salon->id)
                    ->where('client_id', $user->id)
                    ->where('status', 'approved')
                    ->sum('amount');

                $lastAppt      = $appointments->first();
                $lastVisitDate = $lastAppt
                    ? Carbon::parse($lastAppt->appointment_date)->format('M d, Y')
                    : 'N/A';

                // Status logic
                $status = $user->status ?? 'New';
                if ($status == 'New' && ($totalVisits >= 10 || $totalSpent >= 50000)) {
                    $status = 'VIP';
                } elseif ($status == 'New' && $totalVisits >= 3) {
                    $status = 'Regular';
                }

                return [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'phone'         => $user->phone ?? 'N/A',
                    'join_date'     => Carbon::parse($user->created_at)->format('M d, Y'),
                    'join_date_raw' => Carbon::parse($user->created_at)->format('Y-m-d'),
                    'total_visits'  => $totalVisits,
                    'total_spent'   => $totalSpent,
                    'last_visit'    => $lastVisitDate,
                    'status'        => $status,
                    'notes'         => $user->notes ?? '',
                ];
            });

            // Stats
            $stats = [
                'total'          => $clients->count(),
                'vip'            => $clients->where('status', 'VIP')->count(),
                'new_this_month' => $clientsRaw->filter(function ($u) {
                    return Carbon::parse($u->created_at)->month === now()->month
                        && Carbon::parse($u->created_at)->year  === now()->year;
                })->count(),
                'active_today'   => Appointment::where('salon_id', $salon->id)
                                        ->whereDate('appointment_date', today())
                                        ->distinct('client_id')
                                        ->count('client_id'),
            ];

            return view('owner.clients.index', compact('stats', 'clients'));

        } catch (\Exception $e) {
            Log::error('Client Index Error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return view('owner.clients.index', [
                'stats'   => ['total' => 0, 'vip' => 0, 'new_this_month' => 0, 'active_today' => 0],
                'clients' => collect([]),
            ])->with('error', 'Unable to load clients: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('owner.clients.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone ?? null,
                'password' => Hash::make('Welcome@123'),
                'role'     => 'client',
                'is_active' => true,
                // ✅ STATUS AUR NOTES SAVE KARO
                'status'   => $request->status ?? 'New',
                'notes'    => $request->notes ?? null,
            ]);

            return redirect()->route('owner.clients.index')
                ->with('success', 'Client "' . $user->name . '" added! Default password: Welcome@123');

        } catch (\Exception $e) {
            Log::error('Client Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to add client: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $salon = $this->getOwnerSalon();
            $user  = User::findOrFail($id);

            $appointments = Appointment::where('salon_id', $salon->id)
                ->where('client_id', $user->id)
                ->with(['service', 'stylist'])
                ->orderBy('appointment_date', 'desc')
                ->get();

            $totalVisits = $appointments->count();
            $totalSpent  = Payment::where('salon_id', $salon->id)
                ->where('client_id', $user->id)
                ->where('status', 'approved')
                ->sum('amount');

            $lastAppt = $appointments->first();
            
            // ✅ STATUS LOGIC
            $status = $user->status ?? 'New';
            if ($status == 'New' && ($totalVisits >= 10 || $totalSpent >= 50000)) {
                $status = 'VIP';
            } elseif ($status == 'New' && $totalVisits >= 3) {
                $status = 'Regular';
            }

            $client = [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone ?? 'N/A',
                'join_date'     => Carbon::parse($user->created_at)->format('M d, Y'),
                'join_date_raw' => Carbon::parse($user->created_at)->format('Y-m-d'),
                'total_visits'  => $totalVisits,
                'total_spent'   => $totalSpent,
                'last_visit'    => $lastAppt
                    ? Carbon::parse($lastAppt->appointment_date)->format('M d, Y')
                    : 'N/A',
                'status'        => $status,
                'notes'         => $user->notes ?? '',
            ];

            $visitHistory = $appointments->map(function ($appt) {
                return [
                    'service' => optional($appt->service)->name ?? 'N/A',
                    'stylist' => optional($appt->stylist)->name ?? 'N/A',
                    'date'    => Carbon::parse($appt->appointment_date)->format('M d, Y'),
                    'amount'  => $appt->total_amount ?? 0,
                    'status'  => ucfirst($appt->status ?? 'N/A'),
                ];
            })->toArray();

            return view('owner.clients.show', compact('client', 'visitHistory'));

        } catch (\Exception $e) {
            Log::error('Client Show Error: ' . $e->getMessage());
            return redirect()->route('owner.clients.index')
                ->with('error', 'Client not found: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);

            $client = [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone ?? '',
                'join_date_raw' => Carbon::parse($user->created_at)->format('Y-m-d'),
                'status'        => $user->status ?? 'New',
                'notes'         => $user->notes ?? '',
            ];

            return view('owner.clients.edit', compact('client'));

        } catch (\Exception $e) {
            return redirect()->route('owner.clients.index')
                ->with('error', 'Client not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
            ]);

            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?? $user->phone,
                'status' => $request->status ?? $user->status,
                'notes'  => $request->notes ?? $user->notes,
            ]);

            return redirect()->route('owner.clients.index')
                ->with('success', 'Client "' . $user->name . '" updated successfully!');

        } catch (\Exception $e) {
            Log::error('Client Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $name = $user->name;
            $user->delete();

            return redirect()->route('owner.clients.index')
                ->with('success', 'Client "' . $name . '" removed.');

        } catch (\Exception $e) {
            return redirect()->route('owner.clients.index')
                ->with('error', 'Unable to delete client.');
        }
    }

    public function export(Request $request)
    {
        try {
            $salon     = $this->getOwnerSalon();
            
            // ✅ SAB CLIENTS EXPORT KARO
            $clients   = User::where('role', 'client')
                ->orderBy('name')
                ->get();

            $csv = "Name,Email,Phone,Join Date,Status\n";
            foreach ($clients as $c) {
                $csv .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    str_replace(',', ' ', $c->name),
                    $c->email,
                    str_replace(',', ' ', $c->phone ?? 'N/A'),
                    Carbon::parse($c->created_at)->format('Y-m-d'),
                    $c->status ?? 'New'
                );
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="clients-' . date('Y-m-d') . '.csv"');

        } catch (\Exception $e) {
            return redirect()->route('owner.clients.index')
                ->with('error', 'Export failed.');
        }
    }
}