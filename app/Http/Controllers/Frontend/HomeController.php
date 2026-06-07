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
        
        // ✅ YEH LINE ADD KARO (withoutGlobalScope ke saath)
        $totalClients = User::withoutGlobalScope('softDeletes')->where('role', 'client')->count();

        // Categories
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        // Featured Salons (recommended)
        $featuredSalons = Salon::where('status', 'approved')
            ->with('services')
            ->latest()
            ->take(12)
            ->get();

        // New Salons (new to glamora)
        $newSalons = Salon::where('status', 'approved')
            ->latest()
            ->take(12)
            ->get();

        // Top Rated Salons (trending)
        $topRatedSalons = Salon::where('status', 'approved')
            ->orderBy('rating', 'desc')
            ->take(12)
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