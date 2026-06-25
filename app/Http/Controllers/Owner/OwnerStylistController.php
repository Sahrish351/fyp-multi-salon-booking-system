<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerStylistController extends Controller
{
    /**
     * Route: GET /owner/stylists  -->  name: owner.stylists.index
     *
     * Team Members grid page dikhana.
     *
     * BAAD ME: Database se real stylists laayen:
     *   $stylists = Stylist::where('salon_id', auth()->user()->salon_id)->get();
     */
    public function index(Request $request)
    {
        $stylists = $this->dummyStylists();

        return view('owner.stylists.index', compact('stylists'));
    }

    /**
     * Route: GET /owner/stylists/create  -->  name: owner.stylists.create
     *
     * Naya team member add karne ka page.
     */
    public function create()
    {
        return view('owner.stylists.create');
    }

    /**
     * Route: POST /owner/stylists  -->  name: owner.stylists.store
     *
     * Naya team member add karna (Create page form submit).
     *
     * BAAD ME:
     *   $request->validate([
     *       'name'             => 'required|string|max:255',
     *       'role'             => 'required|string|max:255',
     *       'email'            => 'required|email|unique:stylists,email',
     *       'phone'            => 'required|string|max:20',
     *       'specialization'   => 'nullable|string',
     *       'experience_years' => 'nullable|integer|min:0',
     *       'bio'              => 'nullable|string',
     *       'photo'            => 'nullable|image|max:2048',
     *       'status'           => 'required|in:Active,Inactive',
     *   ]);
     *   Stylist::create([...$request->validated(), 'salon_id' => auth()->user()->salon_id]);
     */
    public function store(Request $request)
    {
        return redirect()->route('owner.stylists.index')->with('success', 'Team member added successfully!');
    }

    /**
     * Route: GET /owner/stylists/{stylist}  -->  name: owner.stylists.show
     *
     * Team member ka detail page (photo, stats, bio, recent appointments).
     *
     * BAAD ME:
     *   $stylist = Stylist::findOrFail($id)->toArray();
     *   $recentAppointments = Appointment::where('stylist_id', $id)
     *       ->latest()->take(5)->get();
     */
    public function show($stylist)
    {
        $stylistData = $this->findDummyStylist($stylist);

        $recentAppointments = [
            ['client' => 'Sarah Johnson', 'service' => 'Hair Styling', 'date' => 'Jun 8, 2026', 'status' => 'Confirmed'],
            ['client' => 'Lisa Anderson', 'service' => 'Haircut',      'date' => 'Jun 6, 2026', 'status' => 'Completed'],
            ['client' => 'Amanda Lee',    'service' => 'Hair Coloring','date' => 'Jun 3, 2026', 'status' => 'Completed'],
        ];

        return view('owner.stylists.show', [
            'stylist' => $stylistData,
            'recentAppointments' => $recentAppointments,
        ]);
    }

    /**
     * Route: GET /owner/stylists/{stylist}/edit  -->  name: owner.stylists.edit
     *
     * Team member edit karne ka page (pre-filled form).
     *
     * BAAD ME:
     *   $stylist = Stylist::findOrFail($id)->toArray();
     */
    public function edit($stylist)
    {
        $stylistData = $this->findDummyStylist($stylist);

        return view('owner.stylists.edit', ['stylist' => $stylistData]);
    }

    /**
     * Route: PUT /owner/stylists/{stylist}  -->  name: owner.stylists.update
     *
     * Team member update karna (Edit page form submit).
     *
     * BAAD ME:
     *   $stylist = Stylist::findOrFail($id);
     *   $stylist->update($request->validated());
     */
    public function update(Request $request, $stylist)
    {
        return redirect()->route('owner.stylists.index')->with('success', 'Team member updated successfully!');
    }

    /**
     * Route: DELETE /owner/stylists/{stylist}  -->  name: owner.stylists.destroy
     *
     * Team member remove karna (Delete confirmation modal submit).
     *
     * BAAD ME:
     *   Stylist::findOrFail($id)->delete();
     */
    public function destroy(Request $request, $stylist)
    {
        return redirect()->route('owner.stylists.index')->with('success', 'Team member removed successfully!');
    }

    /**
     * Route: POST /owner/stylists/{id}/availability  -->  name: owner.stylists.availability.store
     * (Aapki web.php mein already maujood route — yahan basic stub)
     */
    public function storeAvailability(Request $request, $id)
    {
        return redirect()->route('owner.stylists.availability.index', ['stylist' => $id])
            ->with('success', 'Availability updated!');
    }

    /**
     * Route: POST /owner/stylists/{id}/holiday  -->  name: owner.stylists.holiday.store
     */
    public function storeHoliday(Request $request, $id)
    {
        return redirect()->route('owner.stylists.holidays.index', ['stylist' => $id])
            ->with('success', 'Holiday added!');
    }

    /**
     * Route: GET /owner/stylists/{stylist}/availability  -->  name: owner.stylists.availability.index
     */
    public function availability($stylist)
    {
        $stylistData = $this->findDummyStylist($stylist);

        return response("<h2 style='font-family:sans-serif; padding:40px;'>"
            . htmlspecialchars($stylistData['name'])
            . " — Availability page (coming soon)</h2>");
    }

    /**
     * Route: DELETE /owner/stylists/{stylist}/availability/{day}  -->  name: owner.stylists.availability.destroy
     */
    public function destroyAvailability(Request $request, $stylist, $day)
    {
        return back()->with('success', 'Availability slot removed!');
    }

    /**
     * Dummy/demo stylists list — BAAD ME ye method hata kar Eloquent
     * query se replace kar dena.
     */
    private function dummyStylists(): array
    {
        return [
            ['id' => 1, 'name' => 'Emma Wilson',     'role' => 'Senior Hair Stylist', 'rating' => 4.9, 'clients' => 245, 'revenue' => 31850, 'photo_url' => null, 'status' => 'Active'],
            ['id' => 2, 'name' => 'James Brown',      'role' => 'Master Barber',       'rating' => 4.8, 'clients' => 189, 'revenue' => 24120, 'photo_url' => null, 'status' => 'Active'],
            ['id' => 3, 'name' => 'Sophia Lee',        'role' => 'Nail Specialist',     'rating' => 4.9, 'clients' => 210, 'revenue' => 27300, 'photo_url' => null, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Olivia Martinez',   'role' => 'Facial Specialist',   'rating' => 4.7, 'clients' => 156, 'revenue' => 19850, 'photo_url' => null, 'status' => 'Active'],
            ['id' => 5, 'name' => 'Isabella Garcia',   'role' => 'Massage Therapist',   'rating' => 4.8, 'clients' => 132, 'revenue' => 22400, 'photo_url' => null, 'status' => 'Active'],
        ];
    }

    /**
     * Dummy stylist id ke mutabiq dhoondna — BAAD ME Stylist::findOrFail($id) se replace karna.
     */
    private function findDummyStylist($id): array
    {
        $stylists = $this->dummyStylists();

        foreach ($stylists as $s) {
            if ($s['id'] == $id) {
                $s['email']            = strtolower(str_replace(' ', '.', $s['name'])) . '@glowaura.com';
                $s['phone']            = '+1 (555) 123-45' . str_pad($id, 2, '0', STR_PAD_LEFT);
                $s['specialization']   = 'Hair Styling';
                $s['experience_years'] = 5;
                $s['bio']              = $s['name'] . ' is a dedicated ' . strtolower($s['role']) . ' with years of experience delivering top-quality service to every client.';
                $s['total_appointments'] = 312;
                return $s;
            }
        }

        return [
            'id' => $id,
            'name' => 'Emma Wilson',
            'role' => 'Senior Hair Stylist',
            'rating' => 4.9,
            'clients' => 245,
            'revenue' => 31850,
            'photo_url' => null,
            'status' => 'Active',
            'email' => 'emma.wilson@glowaura.com',
            'phone' => '+1 (555) 123-4501',
            'specialization' => 'Hair Styling',
            'experience_years' => 5,
            'bio' => 'Emma Wilson is a dedicated senior hair stylist with years of experience.',
            'total_appointments' => 312,
        ];
    }
}
