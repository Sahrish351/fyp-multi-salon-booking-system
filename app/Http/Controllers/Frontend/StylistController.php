<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Stylist;
use App\Models\Review;
use App\Models\Appointment;
use Illuminate\Http\Request;

class StylistController extends Controller
{
    public function profile($salonSlug, $stylistId)
    {
        // Get salon
        $salon = Salon::where('slug', $salonSlug)
            ->where('status', 'approved')
            ->firstOrFail();

        // Get stylist
        $stylist = Stylist::where('id', $stylistId)
            ->where('salon_id', $salon->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Reviews
        $reviewsCount = Review::where('stylist_id', $stylist->id)
            ->where('is_approved', true)
            ->count();

        $avgRating = Review::where('stylist_id', $stylist->id)
            ->where('is_approved', true)
            ->avg('rating') ?? $stylist->rating ?? 5.0;

        $recentReviews = Review::where('stylist_id', $stylist->id)
            ->where('is_approved', true)
            ->with('client')
            ->latest()
            ->take(5)
            ->get();

        // Total appointments
        $totalAppointments = Appointment::where('stylist_id', $stylist->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();

        // ✅ FIX: Yeh line sahi hai (salon->services)
        $services = $salon->services()->where('is_active', true)->take(10)->get();

        // Similar stylists
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