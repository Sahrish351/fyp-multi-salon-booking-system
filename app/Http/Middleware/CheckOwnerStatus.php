<?php

namespace App\Http\Middleware;

use App\Models\Salon;
use Closure;
use Illuminate\Http\Request;

class CheckOwnerStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'owner') {
            return $next($request);
        }

        if ($request->routeIs([
            'owner.salon.pending',
            'owner.salon.rejected',
            'owner.salon.suspended',
            'logout',
            'verification.notice',
            'verification.send',
            'verification.verify',
            'verification.resend',
        ])) {
            return $next($request);
        }

        if (!$user->is_verified) {
            return redirect()->route('verification.notice');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon || $salon->status === 'pending') {
            return redirect()->route('owner.salon.pending');
        }

        if ($salon->status === 'rejected') {
            return redirect()->route('owner.salon.rejected');
        }

        if ($salon->status === 'suspended') {
            return redirect()->route('owner.salon.suspended');
        }

        return $next($request);
    }
}