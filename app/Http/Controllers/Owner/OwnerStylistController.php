<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OwnerStylistController extends Controller
{
    // Logged-in owner ka salon ID nikalta hai
    private function getSalonId()
    {
        $user = auth()->user();

        if (!empty($user->salon_id)) {
            return $user->salon_id;
        }

        if (method_exists($user, 'salon') && $user->salon) {
            return $user->salon->id;
        }

        $salonId = Salon::where('owner_id', $user->id)->value('id');

        // Salon na mile to Salon 1 par mat jao, access band karo
        abort_if(!$salonId, 403, 'No salon linked to this account.');

        return $salonId;
    }

    // Form mein dikhane ke liye is salon ki active services
    private function salonServices($salonId)
    {
        return Service::where('salon_id', $salonId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);
    }

    public function index(Request $request)
    {
        try {
            $salonId = $this->getSalonId();

            $stylists = Stylist::where('salon_id', $salonId)
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
                        'photo_url' => $stylist->avatar ? asset('storage/' . $stylist->avatar) : null,
                        'status' => $stylist->status ?? 'active',
                    ];
                });

            return view('owner.stylists.index', compact('stylists'));

        } catch (HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Stylist Index Error: ' . $e->getMessage());
            return view('owner.stylists.index', ['stylists' => collect([])])
                ->with('error', 'Unable to load team members.');
        }
    }

    public function create()
    {
        $salonId = $this->getSalonId();

        return view('owner.stylists.create', [
            'services' => $this->salonServices($salonId),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $salonId = $this->getSalonId();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'role' => 'nullable|string|max:255',
                'email' => [
                    'nullable', 'email',
                    Rule::unique('stylists', 'email')->whereNull('deleted_at'),
                ],
                'phone' => 'nullable|string|max:20',
                'specialization' => 'nullable|string',
                'experience_years' => 'nullable|integer|min:0',
                'bio' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'nullable|in:Active,Inactive,active,inactive',
                'services' => 'nullable|array',
                'services.*' => [
                    Rule::exists('services', 'id')
                        ->where('salon_id', $salonId)
                        ->whereNull('deleted_at'),
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $avatarPath = null;
            if ($request->hasFile('photo')) {
                $avatarPath = $request->file('photo')->store('stylists', 'public');
            }

            $status = strtolower($request->status ?? 'active');

            $stylist = Stylist::create([
                'salon_id' => $salonId,
                'name' => $request->name,
                'role' => $request->role ?? 'Stylist',
                'email' => $request->email,
                'phone' => $request->phone,
                'specializations' => $request->specialization ?? $request->role ?? 'Hair & Beauty',
                'experience_years' => $request->experience_years ?? 0,
                'bio' => $request->bio,
                'avatar' => $avatarPath,
                'status' => $status,
                'is_active' => $status === 'active',
                'rating' => 5.0,
            ]);

            // Chuni hui services save karo
            $stylist->services()->sync($request->input('services', []));

            return redirect()->route('owner.stylists.index')
                ->with('success', 'Team member "' . $request->name . '" added successfully!');

        } catch (HttpException $e) {
            throw $e;
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
            $salonId = $this->getSalonId();

            $stylist = Stylist::where('salon_id', $salonId)->find($id);

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
                        'date' => $appt->appointment_date ? $appt->appointment_date->format('M d, Y') : 'N/A',
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
                'photo_url' => $stylist->avatar ? asset('storage/' . $stylist->avatar) : null,
                'status' => $stylist->status ?? 'active',
                'email' => $stylist->email,
                'phone' => $stylist->phone,
                'specialization' => $stylist->specializations ?? 'General',
                'experience_years' => $stylist->experience_years ?? 0,
                'bio' => $stylist->bio,
                'total_appointments' => $appointmentsCount,
            ];

            return view('owner.stylists.show', [
                'stylist' => $stylistData,
                'recentAppointments' => $recentAppointments,
            ]);

        } catch (HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Stylist Show Error: ' . $e->getMessage());
            return redirect()->route('owner.stylists.index')
                ->with('error', 'Team member not found.');
        }
    }

    public function edit($id)
    {
        try {
            $salonId = $this->getSalonId();

            $stylist = Stylist::where('salon_id', $salonId)->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            $stylistData = [
                'id' => $stylist->id,
                'name' => $stylist->name,
                'role' => $stylist->role ?? '',
                'email' => $stylist->email,
                'phone' => $stylist->phone,
                'specialization' => $stylist->specializations ?? '',
                'experience_years' => $stylist->experience_years ?? 0,
                'bio' => $stylist->bio,
                'photo_url' => $stylist->avatar ? asset('storage/' . $stylist->avatar) : null,
                'status' => $stylist->status ?? 'active',
            ];

            return view('owner.stylists.edit', [
                'stylist' => $stylistData,
                'services' => $this->salonServices($salonId),
                'selectedServiceIds' => $stylist->services()->pluck('services.id')->all(),
            ]);

        } catch (HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Stylist Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.stylists.index')
                ->with('error', 'Team member not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $salonId = $this->getSalonId();

            $stylist = Stylist::where('salon_id', $salonId)->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'role' => 'nullable|string|max:255',
                'email' => [
                    'nullable', 'email',
                    Rule::unique('stylists', 'email')->ignore($stylist->id)->whereNull('deleted_at'),
                ],
                'phone' => 'nullable|string|max:20',
                'specialization' => 'nullable|string',
                'experience_years' => 'nullable|integer|min:0',
                'bio' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'nullable|in:Active,Inactive,active,inactive',
                'services' => 'nullable|array',
                'services.*' => [
                    Rule::exists('services', 'id')
                        ->where('salon_id', $salonId)
                        ->whereNull('deleted_at'),
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($request->hasFile('photo')) {
                if ($stylist->avatar && Storage::disk('public')->exists($stylist->avatar)) {
                    Storage::disk('public')->delete($stylist->avatar);
                }
                $stylist->avatar = $request->file('photo')->store('stylists', 'public');
            }

            $status = strtolower($request->status ?? $stylist->status ?? 'active');

            $stylist->name = $request->name;
            $stylist->role = $request->role ?? $stylist->role;
            $stylist->email = $request->email;
            $stylist->phone = $request->phone;
            $stylist->specializations = $request->specialization;
            $stylist->experience_years = $request->experience_years ?? 0;
            $stylist->bio = $request->bio;
            $stylist->status = $status;
            $stylist->is_active = $status === 'active';
            $stylist->save();

            // Services update karo (jo uncheck hui wo hat jayengi)
            $stylist->services()->sync($request->input('services', []));

            return redirect()->route('owner.stylists.index')
                ->with('success', 'Team member "' . $stylist->name . '" updated successfully!');

        } catch (HttpException $e) {
            throw $e;
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
            $salonId = $this->getSalonId();

            $stylist = Stylist::where('salon_id', $salonId)->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            // Upcoming active appointments hon to delete na karne do
            $hasUpcoming = Appointment::where('stylist_id', $stylist->id)
                ->whereDate('appointment_date', '>=', now()->toDateString())
                ->whereNotIn('status', ['completed', 'cancelled', 'no_show'])
                ->exists();

            if ($hasUpcoming) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'This stylist has upcoming appointments. Cancel or complete them first.');
            }

            $stylistName = $stylist->name;

            // Model ka deleting event khali time slots bhi delete kar deta hai
            DB::transaction(function () use ($stylist) {
                $stylist->delete();
            });

            return redirect()->route('owner.stylists.index')
                ->with('success', 'Team member "' . $stylistName . '" removed successfully!');

        } catch (HttpException $e) {
            throw $e;
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
        return redirect()->route('owner.stylists.holidays.index', ['stylist' => $id])
            ->with('success', 'Holiday added!');
    }

    public function availability($id)
    {
        try {
            $salonId = $this->getSalonId();

            $stylist = Stylist::where('salon_id', $salonId)->find($id);

            if (!$stylist) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Team member not found.');
            }

            return view('owner.stylists.availability', ['stylist' => $stylist]);

        } catch (HttpException $e) {
            throw $e;
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