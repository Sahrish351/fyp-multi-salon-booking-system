<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class OwnerClientController extends Controller
{
   
    public function index(Request $request)
    {
        $stats = [
            'total'          => 1245,
            'vip'            => 186,
            'new_this_month' => 48,
            'active_today'   => 24,
        ];
 
        $clients = $this->dummyClients();
 
        return view('owner.clients.index', compact('stats', 'clients'));
    }
 
   
    public function create()
    {
        return view('owner.clients.create');
    }
 
    
    public function store(Request $request)
    {
        return redirect()->route('owner.clients.index')->with('success', 'Client added successfully!');
    }
 
 
    public function show($client)
    {
        $clientData = $this->findDummyClient($client);
 
        $visitHistory = [
            ['service' => 'Hair Styling & Color', 'stylist' => 'Emma Wilson', 'date' => 'Jun 5, 2026', 'amount' => 120, 'status' => 'Completed'],
            ['service' => 'Premium Haircut',      'stylist' => 'James Brown', 'date' => 'May 12, 2026', 'amount' => 85,  'status' => 'Completed'],
            ['service' => 'Luxury Manicure',      'stylist' => 'Sophia Lee',  'date' => 'Apr 20, 2026', 'amount' => 65,  'status' => 'Completed'],
        ];
 
        return view('owner.clients.show', ['client' => $clientData, 'visitHistory' => $visitHistory]);
    }
 
  
    public function edit($client)
    {
        $clientData = $this->findDummyClient($client);
 
        return view('owner.clients.edit', ['client' => $clientData]);
    }
 
   
    public function update(Request $request, $client)
    {
        return redirect()->route('owner.clients.index')->with('success', 'Client updated successfully!');
    }
 
    
    public function destroy(Request $request, $client)
    {
        return redirect()->route('owner.clients.index')->with('success', 'Client deleted successfully!');
    }
 
    
    public function export(Request $request)
    {
        $clients = $this->dummyClients();
 
        $csv = "Name,Email,Phone,Join Date,Total Visits,Total Spent,Status\n";
        foreach ($clients as $c) {
            $csv .= "{$c['name']},{$c['email']},{$c['phone']},{$c['join_date']},{$c['total_visits']},{$c['total_spent']},{$c['status']}\n";
        }
 
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="clients.csv"');
    }
 
   
    private function dummyClients(): array
    {
        return [
            ['id' => 1, 'name' => 'Sarah Johnson', 'email' => 'sarah.j@email.com', 'phone' => '+1 234-567-8901', 'join_date' => 'Jan 15, 2026', 'join_date_raw' => '2026-01-15', 'total_visits' => 24, 'total_spent' => 2880, 'last_visit' => 'Jun 5, 2026', 'status' => 'VIP'],
            ['id' => 2, 'name' => 'Michael Chen',  'email' => 'm.chen@email.com',  'phone' => '+1 234-567-8902', 'join_date' => 'Feb 20, 2026', 'join_date_raw' => '2026-02-20', 'total_visits' => 18, 'total_spent' => 1530, 'last_visit' => 'Jun 3, 2026', 'status' => 'Regular'],
            ['id' => 3, 'name' => 'Emily Davis',   'email' => 'emily.d@email.com', 'phone' => '+1 234-567-8903', 'join_date' => 'Mar 10, 2026', 'join_date_raw' => '2026-03-10', 'total_visits' => 12, 'total_spent' => 1140, 'last_visit' => 'Jun 1, 2026', 'status' => 'Regular'],
            ['id' => 4, 'name' => 'David Miller',  'email' => 'd.miller@email.com', 'phone' => '+1 234-567-8904', 'join_date' => 'Jan 5, 2026', 'join_date_raw' => '2026-01-05', 'total_visits' => 32, 'total_spent' => 4800, 'last_visit' => 'Jun 7, 2026', 'status' => 'VIP'],
            ['id' => 5, 'name' => 'Lisa Anderson', 'email' => 'lisa.a@email.com', 'phone' => '+1 234-567-8905', 'join_date' => 'Apr 12, 2026', 'join_date_raw' => '2026-04-12', 'total_visits' => 8,  'total_spent' => 1440, 'last_visit' => 'Jun 6, 2026', 'status' => 'Regular'],
        ];
    }
 
   
    private function findDummyClient($id): array
    {
        $clients = $this->dummyClients();
 
        foreach ($clients as $c) {
            if ($c['id'] == $id) {
                $c['notes'] = 'Prefers organic hair products. Allergic to certain dyes.';
                return $c;
            }
        }
 
        return $clients[0];
    }
}