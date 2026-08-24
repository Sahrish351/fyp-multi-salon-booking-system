<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use App\Models\Salon;
use Illuminate\Support\Facades\Auth;

class PublicServiceController extends Controller
{
    public function index()
    {
        $allowedCategories = ['hair', 'makeup', 'nails', 'spa', 'bridal', 'facial', 'waxing', 'threading'];

        $categories = Category::all()
            ->unique(function ($cat) {
                return strtolower(trim($cat->name));
            })
            ->filter(function ($cat) use ($allowedCategories) {
                return in_array(strtolower(trim($cat->name)), $allowedCategories);
            });

        $services = Service::with(['category', 'salon'])
            ->latest()
            ->get()
            ->unique(function ($svc) {
                return strtolower(trim($svc->name));
            });

    
        $userSalon = null;
        if (Auth::check() && Auth::user()->isOwner()) {
            $userSalon = Salon::where('owner_id', Auth::id())->first();
        }

        return view('frontend.services.index', compact('services', 'categories', 'userSalon'));
    }

    public function show($salonSlug, $serviceId)
    {
        return redirect()->route('salons.show', $salonSlug);
    }
}