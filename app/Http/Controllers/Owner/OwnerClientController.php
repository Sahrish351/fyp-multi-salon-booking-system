<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OwnerClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            // ✅ REAL CLIENTS FROM DATABASE (Users with role = client who have appointments)
            $clients = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->with(['appointments' => function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId)->orderBy('appointment_date', 'desc');
                }])
                ->get()
                ->map(function ($client) {
                    $totalVisits = $client->appointments->count();
                    $totalSpent = $client->appointments->sum('total_amount');
                    $lastVisit = $client->appointments->first();

                    return [
                        'id' => $client->id,
                        'name' => $client->name,
                        'email' => $client->email,
                        'phone' => $client->phone ?? 'N/A',
                        'join_date' => $client->created_at ? date('M d, Y', strtotime($client->created_at)) : 'N/A',
                        'join_date_raw' => $client->created_at ? date('Y-m-d', strtotime($client->created_at)) : null,
                        'total_visits' => $totalVisits,
                        'total_spent' => $totalSpent,
                        'last_visit' => $lastVisit ? date('M d, Y', strtotime($lastVisit->appointment_date)) : 'Never',
                        'status' => $totalVisits > 10 ? 'VIP' : ($totalVisits > 5 ? 'Regular' : 'New'),
                        'notes' => null,
                    ];
                });

            // ✅ STATS
            $stats = [
                'total' => $clients->count(),
                'vip' => $clients->where('status', 'VIP')->count(),
                'new_this_month' => User::where('role', 'client')
                    ->whereHas('appointments', function ($query) use ($salonId) {
                        $query->where('salon_id', $salonId);
                    })
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count(),
                'active_today' => Appointment::where('salon_id', $salonId)
                    ->whereDate('appointment_date', Carbon::today())
                    ->distinct('client_id')
                    ->count('client_id'),
            ];

            return view('owner.clients.index', compact('stats', 'clients'));

        } catch (\Exception $e) {
            Log::error('Client Index Error: ' . $e->getMessage());
            return view('owner.clients.index', [
                'stats' => ['total' => 0, 'vip' => 0, 'new_this_month' => 0, 'active_today' => 0],
                'clients' => collect([])
            ])->with('error', 'Unable to load clients.');
        }
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('owner.clients.create');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:20',
                'status' => 'required|in:New,Regular,VIP,Inactive',
                'join_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ CREATE CLIENT USER
            $client = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'client',
                'password' => bcrypt('password123'), // Default password
                'is_active' => $request->status !== 'Inactive',
            ]);

            return redirect()->route('owner.clients.index')
                ->with('success', 'Client "' . $client->name . '" added successfully!');

        } catch (\Exception $e) {
            Log::error('Client Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to add client.')
                ->withInput();
        }
    }

    /**
     * Display the specified client.
     */
    public function show($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            // ✅ FIND CLIENT
            $client = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->with(['appointments' => function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId)
                        ->with(['service', 'stylist'])
                        ->orderBy('appointment_date', 'desc');
                }])
                ->find($id);

            if (!$client) {
                return redirect()->route('owner.clients.index')
                    ->with('error', 'Client not found.');
            }

            $totalVisits = $client->appointments->count();
            $totalSpent = $client->appointments->sum('total_amount');
            $lastVisit = $client->appointments->first();

            $clientData = [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone ?? 'N/A',
                'join_date' => $client->created_at ? date('M d, Y', strtotime($client->created_at)) : 'N/A',
                'join_date_raw' => $client->created_at ? date('Y-m-d', strtotime($client->created_at)) : null,
                'total_visits' => $totalVisits,
                'total_spent' => $totalSpent,
                'last_visit' => $lastVisit ? date('M d, Y', strtotime($lastVisit->appointment_date)) : 'Never',
                'status' => $totalVisits > 10 ? 'VIP' : ($totalVisits > 5 ? 'Regular' : 'New'),
                'notes' => null,
            ];

            // ✅ VISIT HISTORY
            $visitHistory = $client->appointments->take(10)->map(function ($appointment) {
                return [
                    'service' => $appointment->service->name ?? 'N/A',
                    'stylist' => $appointment->stylist->name ?? 'N/A',
                    'date' => $appointment->appointment_date ? date('M d, Y', strtotime($appointment->appointment_date)) : 'N/A',
                    'amount' => $appointment->total_amount ?? 0,
                    'status' => ucfirst($appointment->status ?? 'pending'),
                ];
            })->toArray();

            return view('owner.clients.show', [
                'client' => $clientData,
                'visitHistory' => $visitHistory,
            ]);

        } catch (\Exception $e) {
            Log::error('Client Show Error: ' . $e->getMessage());
            return redirect()->route('owner.clients.index')
                ->with('error', 'Client not found.');
        }
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            $client = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->find($id);

            if (!$client) {
                return redirect()->route('owner.clients.index')
                    ->with('error', 'Client not found.');
            }

            $totalVisits = Appointment::where('client_id', $client->id)
                ->where('salon_id', $salonId)
                ->count();

            $clientData = [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone ?? 'N/A',
                'join_date_raw' => $client->created_at ? date('Y-m-d', strtotime($client->created_at)) : null,
                'status' => $totalVisits > 10 ? 'VIP' : ($totalVisits > 5 ? 'Regular' : 'New'),
                'notes' => null,
            ];

            return view('owner.clients.edit', ['client' => $clientData]);

        } catch (\Exception $e) {
            Log::error('Client Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.clients.index')
                ->with('error', 'Client not found.');
        }
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $client = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->find($id);

            if (!$client) {
                return redirect()->route('owner.clients.index')
                    ->with('error', 'Client not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'required|string|max:20',
                'status' => 'required|in:New,Regular,VIP,Inactive',
                'join_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $client->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_active' => $request->status !== 'Inactive',
            ]);

            return redirect()->route('owner.clients.index')
                ->with('success', 'Client "' . $client->name . '" updated successfully!');

        } catch (\Exception $e) {
            Log::error('Client Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update client.')
                ->withInput();
        }
    }

    /**
     * Remove the specified client.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $client = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->find($id);

            if (!$client) {
                return redirect()->route('owner.clients.index')
                    ->with('error', 'Client not found.');
            }

            $clientName = $client->name;

            // Check if client has appointments
            $appointmentCount = Appointment::where('client_id', $client->id)
                ->where('salon_id', $salonId)
                ->count();

            if ($appointmentCount > 0) {
                return redirect()->route('owner.clients.index')
                    ->with('error', 'Cannot delete "' . $clientName . '" because they have ' . $appointmentCount . ' appointment(s).');
            }

            $client->delete();

            return redirect()->route('owner.clients.index')
                ->with('success', 'Client "' . $clientName . '" deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Client Destroy Error: ' . $e->getMessage());
            return redirect()->route('owner.clients.index')
                ->with('error', 'Unable to delete client.');
        }
    }

    /**
     * Export clients as CSV.
     */
    public function export(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $salonId = $salon->id;

            $clients = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->with(['appointments' => function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                }])
                ->get();

            $csv = "Name,Email,Phone,Join Date,Total Visits,Total Spent,Status\n";
            foreach ($clients as $client) {
                $totalVisits = $client->appointments->count();
                $totalSpent = $client->appointments->sum('total_amount');
                $status = $totalVisits > 10 ? 'VIP' : ($totalVisits > 5 ? 'Regular' : 'New');

                $csv .= $client->name . ",";
                $csv .= $client->email . ",";
                $csv .= ($client->phone ?? 'N/A') . ",";
                $csv .= ($client->created_at ? date('M d, Y', strtotime($client->created_at)) : 'N/A') . ",";
                $csv .= $totalVisits . ",";
                $csv .= $totalSpent . ",";
                $csv .= $status . "\n";
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="clients-' . now()->format('Y-m-d') . '.csv"');

        } catch (\Exception $e) {
            Log::error('Client Export Error: ' . $e->getMessage());
            return redirect()->route('owner.clients.index')
                ->with('error', 'Unable to export clients.');
        }
    }
}