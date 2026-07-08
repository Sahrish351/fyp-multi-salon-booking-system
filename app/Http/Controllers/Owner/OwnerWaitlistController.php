<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OwnerWaitlistController extends Controller
{
    /**
     * Display a listing of waitlist entries.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user->salon_id) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            // ✅ REAL DATA FROM DATABASE with relationships
            $waitlistEntries = Waitlist::where('salon_id', $user->salon_id)
                ->with(['client', 'service', 'stylist'])
                ->orderBy('position', 'asc')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($entry) {
                    return [
                        'id' => $entry->id,
                        'client_name' => $entry->client->name ?? 'N/A',
                        'client_email' => $entry->client->email ?? 'N/A',
                        'client_phone' => $entry->client->phone ?? 'N/A',
                        'service' => $entry->service->name ?? 'N/A',
                        'stylist' => $entry->stylist->name ?? 'Any',
                        'preferred_date' => $entry->preferred_date ? date('M d, Y', strtotime($entry->preferred_date)) : 'N/A',
                        'preferred_date_raw' => $entry->preferred_date,
                        'position' => $entry->position ?? 1,
                        'status' => $entry->status ?? 'waiting',
                        'priority' => $entry->position <= 3 ? 'High' : ($entry->position <= 6 ? 'Medium' : 'Low'),
                        'added_date' => $entry->created_at ? date('M d, Y', strtotime($entry->created_at)) : 'N/A',
                        'notes' => null,
                        'expires_at' => $entry->expires_at ? date('M d, Y', strtotime($entry->expires_at)) : null,
                        'notified_at' => $entry->notified_at ? date('M d, Y', strtotime($entry->notified_at)) : null,
                    ];
                });

            $stats = [
                'total' => $waitlistEntries->count(),
                'high_priority' => $waitlistEntries->where('priority', 'High')->count(),
                'this_week' => $waitlistEntries->count(),
            ];

            return view('owner.waitlist.index', compact('waitlistEntries', 'stats'));

        } catch (\Exception $e) {
            Log::error('Waitlist Index Error: ' . $e->getMessage());
            return view('owner.waitlist.index', ['waitlistEntries' => collect([]), 'stats' => ['total' => 0, 'high_priority' => 0, 'this_week' => 0]])
                ->with('error', 'Unable to load waitlist.');
        }
    }

    /**
     * Show the form for creating a new waitlist entry.
     */
    public function create()
    {
        try {
            $user = auth()->user();

            // ✅ GET CLIENTS (USERS WITH ROLE CLIENT)
            $clients = User::where('role', 'client')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'phone']);

            // ✅ GET SERVICES
            $services = Service::where('salon_id', $user->salon_id ?? 0)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);

            // ✅ GET STYLISTS
            $stylists = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->where('status', 'available')
                ->orderBy('name')
                ->get(['id', 'name']);

            return view('owner.waitlist.create', compact('clients', 'services', 'stylists'));

        } catch (\Exception $e) {
            Log::error('Waitlist Create Error: ' . $e->getMessage());
            return redirect()->route('owner.waitlist.index')
                ->with('error', 'Unable to load create page.');
        }
    }

    /**
     * Store a newly created waitlist entry.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user->salon_id) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:users,id',
                'service_id' => 'required|exists:services,id',
                'stylist_id' => 'nullable|exists:stylists,id',
                'preferred_date' => 'required|date',
                'position' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ SAVE TO DATABASE
            Waitlist::create([
                'salon_id' => $user->salon_id,
                'client_id' => $request->client_id,
                'service_id' => $request->service_id,
                'stylist_id' => $request->stylist_id ?? null,
                'preferred_date' => $request->preferred_date,
                'position' => $request->position,
                'status' => 'waiting',
                'expires_at' => now()->addDays(7),
            ]);

            return redirect()->route('owner.waitlist.index')
                ->with('success', 'Client added to waitlist successfully!');

        } catch (\Exception $e) {
            Log::error('Waitlist Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to add client to waitlist.')
                ->withInput();
        }
    }

    /**
     * Display the specified waitlist entry.
     */
    public function show($id)
    {
        try {
            $user = auth()->user();

            $entry = Waitlist::where('salon_id', $user->salon_id ?? 0)
                ->with(['client', 'service', 'stylist'])
                ->find($id);

            if (!$entry) {
                return redirect()->route('owner.waitlist.index')
                    ->with('error', 'Waitlist entry not found.');
            }

            $entryData = [
                'id' => $entry->id,
                'client_name' => $entry->client->name ?? 'N/A',
                'client_email' => $entry->client->email ?? 'N/A',
                'client_phone' => $entry->client->phone ?? 'N/A',
                'service' => $entry->service->name ?? 'N/A',
                'stylist' => $entry->stylist->name ?? 'Any',
                'preferred_date' => $entry->preferred_date ? date('M d, Y', strtotime($entry->preferred_date)) : 'N/A',
                'preferred_date_raw' => $entry->preferred_date,
                'position' => $entry->position ?? 1,
                'status' => $entry->status ?? 'waiting',
                'priority' => $entry->position <= 3 ? 'High' : ($entry->position <= 6 ? 'Medium' : 'Low'),
                'added_date' => $entry->created_at ? date('M d, Y', strtotime($entry->created_at)) : 'N/A',
                'notes' => null,
                'expires_at' => $entry->expires_at ? date('M d, Y', strtotime($entry->expires_at)) : null,
                'notified_at' => $entry->notified_at ? date('M d, Y', strtotime($entry->notified_at)) : null,
            ];

            return view('owner.waitlist.show', ['entry' => $entryData]);

        } catch (\Exception $e) {
            Log::error('Waitlist Show Error: ' . $e->getMessage());
            return redirect()->route('owner.waitlist.index')
                ->with('error', 'Waitlist entry not found.');
        }
    }

    /**
     * Show the form for editing the specified waitlist entry.
     */
    public function edit($id)
    {
        try {
            $user = auth()->user();

            $entry = Waitlist::where('salon_id', $user->salon_id ?? 0)
                ->with(['client', 'service', 'stylist'])
                ->find($id);

            if (!$entry) {
                return redirect()->route('owner.waitlist.index')
                    ->with('error', 'Waitlist entry not found.');
            }

            $clients = User::where('role', 'client')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'phone']);

            $services = Service::where('salon_id', $user->salon_id ?? 0)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);

            $stylists = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->orderBy('name')
                ->get(['id', 'name']);

            $entryData = [
                'id' => $entry->id,
                'client_id' => $entry->client_id,
                'client_name' => $entry->client->name ?? 'N/A',
                'service_id' => $entry->service_id,
                'stylist_id' => $entry->stylist_id,
                'preferred_date_raw' => $entry->preferred_date,
                'position' => $entry->position ?? 1,
                'status' => $entry->status ?? 'waiting',
            ];

            return view('owner.waitlist.edit', [
                'entry' => $entryData,
                'clients' => $clients,
                'services' => $services,
                'stylists' => $stylists,
            ]);

        } catch (\Exception $e) {
            Log::error('Waitlist Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.waitlist.index')
                ->with('error', 'Waitlist entry not found.');
        }
    }

    /**
     * Update the specified waitlist entry.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();

            $entry = Waitlist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$entry) {
                return redirect()->route('owner.waitlist.index')
                    ->with('error', 'Waitlist entry not found.');
            }

            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:users,id',
                'service_id' => 'required|exists:services,id',
                'stylist_id' => 'nullable|exists:stylists,id',
                'preferred_date' => 'required|date',
                'position' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $entry->update([
                'client_id' => $request->client_id,
                'service_id' => $request->service_id,
                'stylist_id' => $request->stylist_id ?? null,
                'preferred_date' => $request->preferred_date,
                'position' => $request->position,
            ]);

            return redirect()->route('owner.waitlist.index')
                ->with('success', 'Waitlist entry updated successfully!');

        } catch (\Exception $e) {
            Log::error('Waitlist Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update waitlist entry.')
                ->withInput();
        }
    }

    /**
     * Remove the specified waitlist entry.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();

            $entry = Waitlist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$entry) {
                return redirect()->route('owner.waitlist.index')
                    ->with('error', 'Waitlist entry not found.');
            }

            $entry->delete();

            return redirect()->route('owner.waitlist.index')
                ->with('success', 'Client removed from waitlist successfully!');

        } catch (\Exception $e) {
            Log::error('Waitlist Destroy Error: ' . $e->getMessage());
            return redirect()->route('owner.waitlist.index')
                ->with('error', 'Unable to remove client from waitlist.');
        }
    }

    /**
     * Remove alias for destroy.
     */
    public function remove($id)
    {
        return $this->destroy($id);
    }

    /**
     * Notify client.
     */
    public function notify(Request $request, $id)
    {
        try {
            $user = auth()->user();

            $entry = Waitlist::where('salon_id', $user->salon_id ?? 0)
                ->with('client')
                ->find($id);

            if (!$entry) {
                return redirect()->route('owner.waitlist.index')
                    ->with('error', 'Waitlist entry not found.');
            }

            $entry->update([
                'notified_at' => now(),
            ]);

            // TODO: Send actual notification to client

            return redirect()->route('owner.waitlist.index')
                ->with('success', 'Client "' . ($entry->client->name ?? 'N/A') . '" has been notified!');

        } catch (\Exception $e) {
            Log::error('Waitlist Notify Error: ' . $e->getMessage());
            return redirect()->route('owner.waitlist.index')
                ->with('error', 'Unable to notify client.');
        }
    }
}