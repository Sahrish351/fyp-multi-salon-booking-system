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

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e; // 403 wagera ko dobara throw karo
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

                    if ($dayIndex === false) {
                        continue; // ghalat din ka naam ignore karo
                    }

                    $slotDate = $currentDate->copy()->addDays($dayIndex);

                    $time = $start->copy();
                    while ($time->lt($end)) {
                        $startTime = $time->format('H:i:s');
                        $endTime = $time->copy()->addMinutes($interval)->format('H:i:s');

                        // firstOrCreate: purani slot (booked/locked) ko nahi chhedta
                        $slot = TimeSlot::firstOrCreate(
                            [
                                'stylist_id' => $stylist->id,
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
                ->route('owner.time-slots.index', ['stylist' => $stylist->id])
                ->with('success', $createdCount . ' time slots generated successfully!');

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
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

            $slot = TimeSlot::where('salon_id', $salonId)->find($timeSlot);

            if (!$slot) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Slot not found.'], 404);
                }
                return back()->with('error', 'Slot not found.');
            }

            // Booked slot ko owner change nahi kar sakta
            if (!in_array($slot->status, ['available', 'locked'])) {
                $msg = 'This slot is ' . $slot->status . ' and cannot be changed.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return back()->with('error', $msg);
            }

            $slot->status = $slot->status === 'available' ? 'locked' : 'available';
            $slot->save();

            $msg = 'Slot ' . ($slot->status === 'available' ? 'activated' : 'locked') . ' successfully!';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'status' => $slot->status,
                    'message' => $msg,
                ]);
            }

            return back()->with('success', $msg);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
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