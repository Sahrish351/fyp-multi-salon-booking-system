<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class OwnerPaymentController extends Controller
{
    
    public function index(Request $request)
    {
        $stats = [
            'total_revenue' => 45280,
            'completed'     => 42860,
            'pending'       => 2420,
            'today_total'   => 2840,
        ];
 
        $payments = $this->dummyPayments();
 
        return view('owner.payments.index', compact('stats', 'payments'));
    }
 
   
    public function create()
    {
        $services = ['Hair Styling & Color', 'Premium Haircut', 'Luxury Manicure & Pedicure', 'Gold Facial Treatment', 'Full Body Spa Massage'];
 
        return view('owner.payments.create', compact('services'));
    }
 
   
    public function store(Request $request)
    {
        return redirect()->route('owner.payments.index')->with('success', 'Payment recorded successfully!');
    }
 
  
    public function show($payment)
    {
        $paymentData = $this->findDummyPayment($payment);
 
        return view('owner.payments.show', ['payment' => $paymentData]);
    }
 
   
    public function edit($payment)
    {
        $paymentData = $this->findDummyPayment($payment);
        $services = ['Hair Styling & Color', 'Premium Haircut', 'Luxury Manicure & Pedicure', 'Gold Facial Treatment', 'Full Body Spa Massage'];
 
        return view('owner.payments.edit', ['payment' => $paymentData, 'services' => $services]);
    }
 
    
    public function update(Request $request, $payment)
    {
        return redirect()->route('owner.payments.index')->with('success', 'Payment updated successfully!');
    }
 
    
    public function destroy(Request $request, $payment)
    {
        return redirect()->route('owner.payments.index')->with('success', 'Payment deleted successfully!');
    }
 
   
    public function approve(Request $request, $payment)
    {
        return redirect()->route('owner.payments.show', ['payment' => $payment])
            ->with('success', 'Payment approved!');
    }
 
  
    public function reject(Request $request, $payment)
    {
        return redirect()->route('owner.payments.show', ['payment' => $payment])
            ->with('success', 'Payment rejected.');
    }
 
   
    public function export(Request $request)
    {
        $payments = $this->dummyPayments();
 
        $csv = "Payment ID,Client,Service,Amount,Method,Date,Status\n";
        foreach ($payments as $p) {
            $csv .= "{$p['payment_id']},{$p['client_name']},{$p['service']},{$p['amount']},{$p['method']},{$p['date']},{$p['status']}\n";
        }
 
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="payments.csv"');
    }
 
   
    private function dummyPayments(): array
    {
        return [
            [
                'id' => 1, 'payment_id' => 'PAY-001', 'client_name' => 'Sarah Johnson', 'client_email' => 'sarah.j@email.com',
                'service' => 'Hair Styling & Color', 'amount' => 120, 'method' => 'Credit Card',
                'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08', 'time' => '10:30 AM', 'time_raw' => '10:30',
                'invoice_no' => 'INV-2026-001', 'status' => 'Completed',
            ],
            [
                'id' => 2, 'payment_id' => 'PAY-002', 'client_name' => 'Michael Chen', 'client_email' => 'm.chen@email.com',
                'service' => 'Premium Haircut', 'amount' => 85, 'method' => 'Cash',
                'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08', 'time' => '11:45 AM', 'time_raw' => '11:45',
                'invoice_no' => 'INV-2026-002', 'status' => 'Completed',
            ],
            [
                'id' => 3, 'payment_id' => 'PAY-003', 'client_name' => 'Emily Davis', 'client_email' => 'emily.d@email.com',
                'service' => 'Luxury Manicure & Pedicure', 'amount' => 95, 'method' => 'Credit Card',
                'date' => 'Jun 8, 2026', 'date_raw' => '2026-06-08', 'time' => '02:15 PM', 'time_raw' => '14:15',
                'invoice_no' => 'INV-2026-003', 'status' => 'Pending',
            ],
            [
                'id' => 4, 'payment_id' => 'PAY-004', 'client_name' => 'David Miller', 'client_email' => 'd.miller@email.com',
                'service' => 'Gold Facial Treatment', 'amount' => 150, 'method' => 'Debit Card',
                'date' => 'Jun 7, 2026', 'date_raw' => '2026-06-07', 'time' => '04:00 PM', 'time_raw' => '16:00',
                'invoice_no' => 'INV-2026-004', 'status' => 'Completed',
            ],
        ];
    }
 
    private function findDummyPayment($id): array
    {
        $payments = $this->dummyPayments();
 
        foreach ($payments as $p) {
            if ($p['id'] == $id) {
                return $p;
            }
        }
 
        return $payments[0];
    }
}