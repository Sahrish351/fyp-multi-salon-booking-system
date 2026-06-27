<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class OwnerWaitlistController extends Controller
{
   
    public function index(Request $request)
    {
        $waitlistEntries = $this->dummyWaitlist();
 
        $stats = [
            'total'         => count($waitlistEntries),
            'high_priority' => count(array_filter($waitlistEntries, fn ($e) => $e['priority'] === 'High')),
            'this_week'     => count($waitlistEntries),
        ];
 
        return view('owner.waitlist.index', compact('waitlistEntries', 'stats'));
    }
 
    public function create()
    {
        $services = ['Hair Coloring', 'Haircut', 'Manicure', 'Facial', 'Full Body Massage', 'Bridal Makeup'];
 
        return view('owner.waitlist.create', compact('services'));
    }
 
   
    public function store(Request $request)
    {
        return redirect()->route('owner.waitlist.index')->with('success', 'Client added to waitlist!');
    }
 
   
    public function show($waitlist)
    {
        $entry = $this->findDummyEntry($waitlist);
 
        return view('owner.waitlist.show', compact('entry'));
    }
 
    
    public function edit($waitlist)
    {
        $entry = $this->findDummyEntry($waitlist);
        $services = ['Hair Coloring', 'Haircut', 'Manicure', 'Facial', 'Full Body Massage', 'Bridal Makeup'];
 
        return view('owner.waitlist.edit', compact('entry', 'services'));
    }
 
   
    public function update(Request $request, $waitlist)
    {
        return redirect()->route('owner.waitlist.index')->with('success', 'Waitlist entry updated!');
    }
 
  
    public function destroy(Request $request, $waitlist)
    {
        return redirect()->route('owner.waitlist.index')->with('success', 'Removed from waitlist!');
    }
 
    public function remove(Request $request, $waitlist)
    {
        return $this->destroy($request, $waitlist);
    }
 
  
    public function notify(Request $request, $id)
    {
        return back()->with('success', 'Client has been notified!');
    }
 
   
    private function dummyWaitlist(): array
    {
        return [
            [
                'id' => 1, 'client_name' => 'Amanda Cooper', 'client_email' => 'amanda@email.com', 'client_phone' => '+1 234-567-9001',
                'service' => 'Hair Coloring', 'preferred_date' => 'Jun 10, 2026', 'preferred_date_raw' => '2026-06-10',
                'priority' => 'High', 'added_date' => 'Jun 5, 2026', 'notes' => null,
            ],
            [
                'id' => 2, 'client_name' => 'Daniel Brown', 'client_email' => 'daniel@email.com', 'client_phone' => '+1 234-567-9002',
                'service' => 'Haircut', 'preferred_date' => 'Jun 9, 2026', 'preferred_date_raw' => '2026-06-09',
                'priority' => 'Medium', 'added_date' => 'Jun 4, 2026', 'notes' => null,
            ],
            [
                'id' => 3, 'client_name' => 'Rachel Green', 'client_email' => 'rachel@email.com', 'client_phone' => '+1 234-567-9003',
                'service' => 'Manicure', 'preferred_date' => 'Jun 11, 2026', 'preferred_date_raw' => '2026-06-11',
                'priority' => 'Low', 'added_date' => 'Jun 5, 2026', 'notes' => null,
            ],
            [
                'id' => 4, 'client_name' => 'Chris Evans', 'client_email' => 'chris@email.com', 'client_phone' => '+1 234-567-9004',
                'service' => 'Facial', 'preferred_date' => 'Jun 12, 2026', 'preferred_date_raw' => '2026-06-12',
                'priority' => 'High', 'added_date' => 'Jun 6, 2026', 'notes' => 'Prefers afternoon slots.',
            ],
        ];
    }
 
   
    private function findDummyEntry($id): array
    {
        $entries = $this->dummyWaitlist();
 
        foreach ($entries as $entry) {
            if ($entry['id'] == $id) {
                return $entry;
            }
        }
 
        return $entries[0];
    }
}