<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerTimeSlotController extends Controller
{
    private array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public function index(Request $request)
    {
        $stylists = $this->dummyStylists();

        $selectedStylistId = $request->get('stylist', $stylists[0]['id']);
        $selectedStylist = $this->findStylist($stylists, $selectedStylistId);

        $weeklySlots = $this->dummyWeeklySlots($selectedStylist['id']);

        return view('owner.time-slots.index', [
            'stylists'        => $stylists,
            'selectedStylist' => $selectedStylist,
            'days'            => $this->days,
            'weeklySlots'     => $weeklySlots,
        ]);
    }

    /**
     * Route: POST /owner/time-slots/generate  -->  name: owner.time-slots.generate
     *
     * Bulk time slots generate karna (Generate Slots modal submit).
     * Diye gaye stylist + days + start/end time + interval ke hisab se
     * automatically slots create karta hai.
     *
     * BAAD ME:
     *   $request->validate([
     *       'stylist_id' => 'required|exists:stylists,id',
     *       'days'       => 'required|array|min:1',
     *       'start_time' => 'required|date_format:H:i',
     *       'end_time'   => 'required|date_format:H:i|after:start_time',
     *       'interval'   => 'required|integer|in:30,45,60',
     *   ]);
     *
     *   $start = Carbon::createFromFormat('H:i', $request->start_time);
     *   $end   = Carbon::createFromFormat('H:i', $request->end_time);
     *
     *   foreach ($request->days as $day) {
     *       $time = $start->copy();
     *       while ($time->lt($end)) {
     *           TimeSlot::firstOrCreate([
     *               'stylist_id' => $request->stylist_id,
     *               'day'        => $day,
     *               'time'       => $time->format('h:i A'),
     *           ], ['active' => true]);
     *           $time->addMinutes((int) $request->interval);
     *       }
     *   }
     */
    public function generate(Request $request)
    {
        return redirect()
            ->route('owner.time-slots.index', ['stylist' => $request->input('stylist_id')])
            ->with('success', 'Time slots generated successfully!');
    }

   
    public function toggleStatus(Request $request, $timeSlot)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Slot status updated!');
    }

   
    private function dummyStylists(): array
    {
        return [
            ['id' => 1, 'name' => 'Emma Wilson',     'photo_url' => null],
            ['id' => 2, 'name' => 'James Brown',      'photo_url' => null],
            ['id' => 3, 'name' => 'Sophia Lee',        'photo_url' => null],
            ['id' => 4, 'name' => 'Olivia Martinez',   'photo_url' => null],
            ['id' => 5, 'name' => 'Isabella Garcia',   'photo_url' => null],
        ];
    }

    private function findStylist(array $stylists, $id): array
    {
        foreach ($stylists as $s) {
            if ($s['id'] == $id) {
                return $s;
            }
        }
        return $stylists[0];
    }

  
    private function dummyWeeklySlots(int $stylistId): array
    {
        $weekdayTimes = ['09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM'];
        $weekendTimes = ['10:00 AM', '11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM'];

        $weeklySlots = [];
        $id = $stylistId * 1000; 

        foreach ($this->days as $day) {
            $times = in_array($day, ['Saturday', 'Sunday']) ? $weekendTimes : $weekdayTimes;

            $weeklySlots[$day] = [];
            foreach ($times as $time) {
                $id++;
                
                $active = !(($id % 7) === 0);

                $weeklySlots[$day][] = [
                    'id'     => $id,
                    'time'   => $time,
                    'active' => $active,
                ];
            }
        }

        return $weeklySlots;
    }
}
