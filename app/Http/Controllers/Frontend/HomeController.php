<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Category;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Stats
        $totalBookings = Appointment::count() + 50000;
        $totalSalons = Salon::where('status', 'approved')->count();
        $totalClients = User::withoutGlobalScope('softDeletes')->where('role', 'client')->count();

        // Categories
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        // 1. Featured / Recommended Salons (Pehle 4 Salons)
        $featuredSalons = Salon::where('status', 'approved')
            ->with(['services.category'])
            ->latest()
            ->take(4)
            ->get();

        // 2. New Salons (Aglay 4 Salons - ID exclude karke taake repeat na ho)
        $featuredIds = $featuredSalons->pluck('id');
        $newSalons = Salon::where('status', 'approved')
            ->whereNotIn('id', $featuredIds)
            ->with(['services.category'])
            ->latest()
            ->take(4)
            ->get();

        // 3. Top Rated / Trending Salons (Aglay 4 Salons)
        $usedIds = $featuredIds->merge($newSalons->pluck('id'));
        $topRatedSalons = Salon::where('status', 'approved')
            ->whereNotIn('id', $usedIds)
            ->with(['services.category'])
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        return view('frontend.home', compact(
            'totalBookings',
            'totalSalons',
            'totalClients',
            'categories',
            'featuredSalons',
            'newSalons',
            'topRatedSalons'
        ));
    }
}