<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Waitlist;
use App\Http\Controllers\Owner\OwnerWaitlistController;
use Illuminate\Support\Facades\Log;

class ExpireWaitlistSlots extends Command
{
    protected $signature   = 'waitlist:expire';
    protected $description = 'Expire notified waitlist slots and offer to next client';

    public function handle()
    {
        try {
            
            $expired = Waitlist::where('status', 'notified')
                ->where('expires_at', '<', now())
                ->get();

            foreach ($expired as $wl) {
               
                $wl->update(['status' => 'expired']);

                Log::info("Waitlist ID {$wl->id} expired. Auto-notifying next client...");

                
                OwnerWaitlistController::notifyNextWaitingClient($wl->salon_id, $wl->preferred_date);
            }

            $this->info('Expired waitlist slots processed successfully.');

        } catch (\Exception $e) {
            Log::error('ExpireWaitlistSlots Command Error: ' . $e->getMessage());
        }
    }
}