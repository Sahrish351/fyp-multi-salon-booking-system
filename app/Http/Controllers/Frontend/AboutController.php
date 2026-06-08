<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Review;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'salons'     => Salon::where('status', 'approved')->count(),
            'clients'    => User::where('role', 'client')->count(),
            'bookings'   => Appointment::count(),
            'reviews'    => Review::where('is_approved', true)->count(),
        ];

        $featuredSalons = Salon::where('status', 'approved')
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $topRatedReviews = Review::with(['client', 'salon'])
            ->where('is_approved', true)
            ->where('rating', '>=', 4)
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.about', compact('stats', 'featuredSalons', 'topRatedReviews'));
    }
}