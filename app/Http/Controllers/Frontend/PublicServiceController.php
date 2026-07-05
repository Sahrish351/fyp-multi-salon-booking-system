<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::with(['salon', 'category'])
            ->whereHas('salon', function($q) {
                $q->where('status', 'approved');
            })
            ->where('is_active', true)
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->city, function($q) use ($request) {
                $q->whereHas('salon', fn($q2) => $q2->where('city', $request->city));
            })
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(16);

        $categories = Category::where('is_active', true)->get();
        $cities = Salon::where('status', 'approved')->distinct('city')->pluck('city');

        return view('frontend.services.index', compact('services', 'categories', 'cities'));
    }

    public function show($salonSlug, $serviceId)
    {
        $salon = Salon::where('slug', $salonSlug)
            ->where('status', 'approved')
            ->firstOrFail();

        $service = Service::where('id', $serviceId)
            ->where('salon_id', $salon->id)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedServices = Service::where('salon_id', $salon->id)
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $stylists = $salon->stylists()
            ->where('is_active', true)
            ->get();

        return view('frontend.services.show', compact('salon', 'service', 'relatedServices', 'stylists'));
    }

    public function byCategory($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $services = Service::with(['salon', 'category'])
            ->whereHas('salon', function($q) {
                $q->where('status', 'approved');
            })
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(16);

        return view('frontend.services.by-category', compact('category', 'services'));
    }
}