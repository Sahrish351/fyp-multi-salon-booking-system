<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Notifications\SalonApproved;
use App\Notifications\SalonRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalonRequestController extends Controller
{
    public function index()
    {
        $pendingSalons = Salon::with('owner')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);
            
        $approvedSalons = Salon::with('owner')
            ->where('status', 'approved')
            ->latest()
            ->paginate(20);
            
        $rejectedSalons = Salon::with('owner')
            ->where('status', 'rejected')
            ->latest()
            ->paginate(20);
            
        $stats = [
            'pending' => Salon::where('status', 'pending')->count(),
            'approved' => Salon::where('status', 'approved')->count(),
            'rejected' => Salon::where('status', 'rejected')->count(),
            'total' => Salon::count(),
        ];
            
        return view('admin.salon-requests.index', compact('pendingSalons', 'approvedSalons', 'rejectedSalons', 'stats'));
    }

    public function show($id)
    {
        $salon = Salon::with('owner')->findOrFail($id);
        return view('admin.salon-requests.show', compact('salon'));
    }

    public function approve($id)
    {
        DB::beginTransaction();
        
        try {
            $salon = Salon::findOrFail($id);
            
            $salon->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Send notification to owner
            $salon->owner->notify(new SalonApproved($salon));

            DB::commit();

            return redirect()->route('admin.salon-requests.index')
                ->with('success', "Salon '{$salon->name}' approved successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        DB::beginTransaction();
        
        try {
            $salon = Salon::findOrFail($id);
            
            $salon->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
                'approved_by' => auth()->id(),
            ]);

            // Send notification to owner with rejection reason
            $salon->owner->notify(new SalonRejected($salon, $request->reason));

            DB::commit();

            return redirect()->route('admin.salon-requests.index')
                ->with('success', "Salon '{$salon->name}' rejected!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'salon_ids' => 'required|array',
        ]);

        DB::beginTransaction();
        
        try {
            $salons = Salon::whereIn('id', $request->salon_ids)->get();
            
            foreach ($salons as $salon) {
                $salon->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
                
                $salon->owner->notify(new SalonApproved($salon));
            }

            DB::commit();

            return redirect()->route('admin.salon-requests.index')
                ->with('success', count($salons) . ' salons approved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }
    }
}