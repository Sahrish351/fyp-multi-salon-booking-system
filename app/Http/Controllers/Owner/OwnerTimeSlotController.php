<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Models\TimeSlot;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OwnerTimeSlotController extends Controller
{
    private array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // Helper method to resolve salon ID dynamic way se
    private function getSalonId()
    {
        $user = auth()->user();
        
        if (!empty($user->salon_id)) {
            return $user->salon_id;
        }

        if (method_exists($user, 'salon') && $user->salon) {
            return $user->salon->id;
        }

        $salon = Salon::where('owner_id', $user->id)->first();
        if ($salon) {
            return $salon->id;
        }

        return 1; // Fallback ID
    }

    public function index(Request $request)
    {
        try {
            $salonId = $this->getSalonId();

            $stylists = Stylist::where('salon_id', $salonId)
                ->orderBy('name')
                ->get()
                ->map(function ($stylist) {
                    return [
                        'id' => $stylist->id,
                        'name' => $stylist->name,
                        'photo_url' => $stylist->photo ? asset('storage/' . $stylist->photo) : null,
                    ];
                });

            if ($stylists->isEmpty()) {
                return redirect()->route('owner.stylists.index')
                    ->with('error', 'Please create a stylist first.');
            }

            $selectedStylistId = $request->get('stylist', $stylists[0]['id']);
            $selectedStylist = $stylists->firstWhere('id', (int)$selectedStylistId) ?? $stylists[0];

            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            $weeklySlots = $this->getWeeklySlots($selectedStylist['id'], $startDate, $endDate);

            return view('owner.time-slots.index', [
                'stylists' => $stylists,
                'selectedStylist' => $selectedStylist,
                'days' => $this->days,
                'weeklySlots' => $weeklySlots,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

        } catch (\Exception $e) {
            Log::error('TimeSlots Index Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to load time slots.');
        }
    }

    public function generate(Request $request)
    {
        try {
            $salonId = $this->getSalonId();

            $validator = Validator::make($request->all(), [
                'stylist_id' => 'required|exists:stylists,id',
                'days' => 'required|array|min:1',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'interval' => 'required|integer|in:15,30,45,60',
                'start_date' => 'required|date',
                'weeks' => 'required|integer|min:1|max:4',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $stylist = Stylist::where('salon_id', $salonId)
                ->find($request->stylist_id);

            if (!$stylist) {
                return redirect()->route('owner.time-slots.index')
                    ->with('error', 'Stylist not found.');
            }

            $start = Carbon::createFromFormat('H:i', $request->start_time);
            $end = Carbon::createFromFormat('H:i', $request->end_time);
            $interval = (int) $request->interval;
            $weeks = (int) $request->weeks;
            $startDate = Carbon::parse($request->start_date)->startOfWeek();

            $createdCount = 0;
            $weekDays = $request->days;

            for ($week = 0; $week < $weeks; $week++) {
                $currentDate = $startDate->copy()->addWeeks($week);

                foreach ($weekDays as $dayName) {
                    $dayIndex = array_search($dayName, $this->days);
                    $slotDate = $currentDate->copy()->addDays($dayIndex);

                    $time = $start->copy();
                    while ($time->lt($end)) {
                        $startTime = $time->format('H:i:s');
                        $endTime = $time->copy()->addMinutes($interval)->format('H:i:s');

                        $slot = TimeSlot::updateOrCreate(
                            [
                                'stylist_id' => $request->stylist_id,
                                'slot_date' => $slotDate->format('Y-m-d'),
                                'start_time' => $startTime,
                            ],
                            [
                                'salon_id' => $salonId,
                                'end_time' => $endTime,
                                'status' => 'available',
                            ]
                        );

                        if ($slot->wasRecentlyCreated) {
                            $createdCount++;
                        }

                        $time->addMinutes($interval);
                    }
                }
            }

            return redirect()
                ->route('owner.time-slots.index', ['stylist' => $request->stylist_id])
                ->with('success', $createdCount . ' time slots generated successfully!');

        } catch (\Exception $e) {
            Log::error('TimeSlots Generate Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to generate time slots: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, $timeSlot)
    {
        try {
            $salonId = $this->getSalonId();

            $slot = TimeSlot::where('salon_id', $salonId)
                ->find($timeSlot);

            if (!$slot) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Slot not found.'], 404);
                }
                return back()->with('error', 'Slot not found.');
            }

            $newStatus = $slot->status === 'available' ? 'locked' : 'available';
            $slot->status = $newStatus;
            $slot->save();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'status' => $slot->status,
                    'message' => 'Slot ' . ($slot->status === 'available' ? 'activated' : 'locked') . ' successfully!'
                ]);
            }

            return back()->with('success', 'Slot ' . ($slot->status === 'available' ? 'activated' : 'locked') . ' successfully!');

        } catch (\Exception $e) {
            Log::error('TimeSlots Toggle Error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unable to toggle slot.'], 500);
            }
            return back()->with('error', 'Unable to toggle slot.');
        }
    }

    private function getWeeklySlots(int $stylistId, Carbon $startDate, Carbon $endDate): array
    {
        $slots = TimeSlot::where('stylist_id', $stylistId)
            ->whereBetween('slot_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('slot_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return Carbon::parse($slot->slot_date)->format('l');
            })
            ->map(function ($group) {
                return $group->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'time' => Carbon::parse($slot->start_time)->format('g:i A') . ' - ' . Carbon::parse($slot->end_time)->format('g:i A'),
                        'status' => $slot->status,
                        'active' => $slot->status === 'available',
                    ];
                })->values()->toArray();
            })
            ->toArray();

        $weeklySlots = [];
        foreach ($this->days as $day) {
            $weeklySlots[$day] = $slots[$day] ?? [];
        }

        return $weeklySlots;
    }
}