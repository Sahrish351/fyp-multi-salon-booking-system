<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Waitlist;

class ExpireWaitlistSlots extends Command
{
    protected $signature   = 'waitlist:expire';
    protected $description = 'Expire notified waitlist slots and offer to next client';

    public function handle()
    {
        $expired = Waitlist::where('status', 'notified')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $wl) {
            $wl->update(['status' => 'expired']);

            // Next waiting person ko offer karo
            $next = Waitlist::where('time_slot_id', $wl->time_slot_id)
                ->where('status', 'waiting')
                ->orderBy('position')
                ->first();

            if ($next) {
                $next->update([
                    'status'     => 'notified',
                    'expires_at' => now()->addMinutes(10),
                ]);
                $next->client->notify(
                    new \App\Notifications\WaitlistSlotAvailable($next)
                );
            }
        }

        $this->info('Expired waitlist slots processed.');
    }
}