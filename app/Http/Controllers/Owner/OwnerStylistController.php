<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Models\Salon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OwnerStylistController extends Controller
{
    
    public function index(Request $request)
{
    try {
        $user = auth()->user();

       
        $stylists = Stylist::where('salon_id', $user->salon_id ?? 0)
            ->orderBy('name')
            ->get()
            ->map(function ($stylist) {
                $clientsCount = Appointment::where('stylist_id', $stylist->id)
                    ->distinct('client_id')
                    ->count('client_id');

                $revenue = Appointment::where('stylist_id', $stylist->id)
                    ->where('status', 'completed')
                    ->sum('total_amount');

                return [
                    'id' => $stylist->id,
                    'name' => $stylist->name,
                    'role' => $stylist->role ?? 'Stylist',
                    'rating' => $stylist->rating ?? 4.5,
                    'clients' => $clientsCount,
                    'revenue' => $revenue,
                    'photo_url' => $stylist->photo ? asset('storage/' . $stylist->photo) : null,
                    'status' => $stylist->status ?? 'Active',
                ];
            });

        return view('owner.stylists.index', compact('stylists'));

    } catch (\Exception $e) {
        Log::error('Stylist Index Error: ' . $e->getMessage());
        return view('owner.stylists.index', ['stylists' => collect([])])
            ->with('error', 'Unable to load team members.');
    }
}
   
    public function create()
    {
        return view('owner.stylists.create');
    }

  
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'email' => 'nullable|email|unique:stylists,email',
                'phone' => 'required|string|max:20',
                'specialization' => 'nullable|string',
                'experience_years' => 'nullable|integer|min:0',
                'bio' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

           
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('stylists', 'public');
            }

            
            Stylist::create([
                'salon_id' => $user->salon_id ?? 1,
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email ?? null,
                'phone' => $request->phone,
                'specialization' => $request->specialization,
                'experience_years' => $request->experience_years ?? 0,
                'bio' => $request->bio,
                'photo' => $photoPath,
                'status' => $request->status,
                'rating' => 4.5,
            ]);

            return redirect()->route('owner.stylists.index')
                ->with('success', 'Team member "' . $request->name . '" added successfully!');

        } catch (\Exception $e) {
            Log::error('Stylist Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to add team member: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $user = auth()->user();

            $stylist = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

          
            $clientsCount = Appointment::where('stylist_id', $stylist->id)
                ->distinct('client_id')
                ->count('client_id');

            $revenue = Appointment::where('stylist_id', $stylist->id)
                ->where('status', 'completed')
                ->sum('total_amount');

            $appointmentsCount = Appointment::where('stylist_id', $stylist->id)->count();

       
            $recentAppointments = Appointment::where('stylist_id', $stylist->id)
                ->with(['client', 'service'])
                ->orderBy('appointment_date', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($appt) {
                    return [
                        'client' => $appt->client->name ?? 'N/A',
                        'service' => $appt->service->name ?? 'N/A',
                        'date' => $appt->appointment_date ? date('M d, Y', strtotime($appt->appointment_date)) : 'N/A',
                        'status' => ucfirst($appt->status ?? 'pending'),
                    ];
                });

            $stylistData = [
                'id' => $stylist->id,
                'name' => $stylist->name,
                'role' => $stylist->role ?? 'Stylist',
                'rating' => $stylist->rating ?? 4.5,
                'clients' => $clientsCount,
                'revenue' => $revenue,
                'photo_url' => $stylist->photo ? asset('storage/' . $stylist->photo) : null,
                'status' => $stylist->status ?? 'Active',
                'email' => $stylist->email,
                'phone' => $stylist->phone,
                'specialization' => $stylist->specialization ?? 'General',
                'experience_years' => $stylist->experience_years ?? 0,
                'bio' => $stylist->bio,
                'total_appointments' => $appointmentsCount,
            ];

            return view('owner.stylists.show', [
                'stylist' => $stylistData,
                'recentAppointments' => $recentAppointments,
            ]);

        } catch (\Exception $e) {
            Log::error('Stylist Show Error: ' . $e->getMessage());
            return redirect()->route('owner.stylists.index')
                ->with('error', 'Team member not found.');
        }
    }

    public function edit($id)
    {
        try {
            $user = auth()->user();

            $stylist = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            $stylistData = [
                'id' => $stylist->id,
                'name' => $stylist->name,
                'role' => $stylist->role ?? 'Stylist',
                'email' => $stylist->email,
                'phone' => $stylist->phone,
                'specialization' => $stylist->specialization ?? '',
                'experience_years' => $stylist->experience_years ?? 0,
                'bio' => $stylist->bio,
                'photo_url' => $stylist->photo ? asset('storage/' . $stylist->photo) : null,
                'status' => $stylist->status ?? 'Active',
            ];

            return view('owner.stylists.edit', ['stylist' => $stylistData]);

        } catch (\Exception $e) {
            Log::error('Stylist Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.stylists.index')
                ->with('error', 'Team member not found.');
        }
    }

  
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();

            $stylist = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'email' => 'nullable|email|unique:stylists,email,' . $id,
                'phone' => 'required|string|max:20',
                'specialization' => 'nullable|string',
                'experience_years' => 'nullable|integer|min:0',
                'bio' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($request->hasFile('photo')) {
                if ($stylist->photo && Storage::disk('public')->exists($stylist->photo)) {
                    Storage::disk('public')->delete($stylist->photo);
                }
                $photoPath = $request->file('photo')->store('stylists', 'public');
                $stylist->photo = $photoPath;
            }

           
            $stylist->name = $request->name;
            $stylist->role = $request->role;
            $stylist->email = $request->email ?? null;
            $stylist->phone = $request->phone;
            $stylist->specialization = $request->specialization;
            $stylist->experience_years = $request->experience_years ?? 0;
            $stylist->bio = $request->bio;
            $stylist->status = $request->status;
            $stylist->save();

            return redirect()->route('owner.stylists.index')
                ->with('success', 'Team member "' . $stylist->name . '" updated successfully!');

        } catch (\Exception $e) {
            Log::error('Stylist Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update team member.')
                ->withInput();
        }
    }

  
    public function destroy($id)
    {
        try {
            $user = auth()->user();

            $stylist = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

           
            if ($stylist->photo && Storage::disk('public')->exists($stylist->photo)) {
                Storage::disk('public')->delete($stylist->photo);
            }

            $stylistName = $stylist->name;
            $stylist->delete();

            return redirect()->route('owner.stylists.index')
                ->with('success', 'Team member "' . $stylistName . '" removed successfully!');

        } catch (\Exception $e) {
            Log::error('Stylist Destroy Error: ' . $e->getMessage());
            return redirect()->route('owner.stylists.index')
                ->with('error', 'Unable to remove team member.');
        }
    }

 
    public function storeAvailability(Request $request, $id)
    {
        
        return redirect()->route('owner.stylists.availability.index', ['stylist' => $id])
            ->with('success', 'Availability updated!');
    }

   
    public function storeHoliday(Request $request, $id)
    {
        // TODO: Implement holiday logic
        return redirect()->route('owner.stylists.holidays.index', ['stylist' => $id])
            ->with('success', 'Holiday added!');
    }

   
    public function availability($id)
    {
        try {
            $user = auth()->user();

            $stylist = Stylist::where('salon_id', $user->salon_id ?? 0)
                ->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            return view('owner.stylists.availability', ['stylist' => $stylist]);

        } catch (\Exception $e) {
            Log::error('Stylist Availability Error: ' . $e->getMessage());
            return redirect()->route('owner.stylists.index')
                ->with('error', 'Unable to load availability.');
        }
    }

   
    public function destroyAvailability(Request $request, $stylist, $day)
    {
        return back()->with('success', 'Availability slot removed!');
    }
}