<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Stylist;
use App\Models\Review;
use App\Models\Appointment;

class StylistController extends Controller
{
    public function profile($salonSlug, $stylistId)
    {
        $salon = Salon::where('slug', $salonSlug)
            ->where('status', 'approved')
            ->firstOrFail();

        $stylist = Stylist::where('id', $stylistId)
            ->where('salon_id', $salon->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Reviews (sirf approved)
        $reviewsCount = Review::where('stylist_id', $stylist->id)
            ->where('is_approved', true)
            ->count();

        $avgRating = Review::where('stylist_id', $stylist->id)
            ->where('is_approved', true)
            ->avg('rating') ?? $stylist->rating ?? 5.0;

        $recentReviews = Review::where('stylist_id', $stylist->id)
            ->where('is_approved', true)
            ->with(['client', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $totalAppointments = Appointment::where('stylist_id', $stylist->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();

        $services = $stylist->services()
            ->where('services.is_active', true)
            ->orderBy('services.name')
            ->get();

        $similarStylists = Stylist::where('salon_id', $salon->id)
            ->where('id', '!=', $stylist->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('frontend.stylist.profile', compact(
            'salon',
            'stylist',
            'reviewsCount',
            'avgRating',
            'recentReviews',
            'totalAppointments',
            'services',
            'similarStylists'
        ));
    }
}