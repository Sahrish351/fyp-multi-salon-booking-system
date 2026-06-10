<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicSalonController extends Controller
{
    public function index(Request $request)
    {
        $salons = Salon::with(['services', 'reviews'])
            ->where('status', 'approved')
            ->when($request->city, fn($q) => $q->where('city', $request->city))
            ->when($request->area, fn($q) => $q->where('area', 'like', '%' . $request->area . '%'))
            ->when($request->category, function($q) use ($request) {
                $q->whereHas('services.category', fn($q2) => $q2->where('slug', $request->category));
            })
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->sort === 'rating', fn($q) => $q->orderByDesc('rating'))
            ->when($request->sort === 'latest', fn($q) => $q->latest())
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $cities = Salon::where('status', 'approved')->distinct('city')->pluck('city');

        return view('frontend.salons.index', compact('salons', 'categories', 'cities'));
    }

    public function show($slug)
    {
        $salon = Salon::with([
            'services.category',
            'stylists' => function($q) {
                $q->where('is_active', true);
            },
            'stylists.availabilities',
            'gallery' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            },
            'reviews' => function($q) {
                $q->where('is_approved', true)->latest()->limit(5);
            },
            'paymentDetails' => function($q) {
                $q->where('is_active', true);
            },
        ])->where('slug', $slug)->where('status', 'approved')->firstOrFail();

        $isFavorite = Auth::check() 
            ? Auth::user()->favorites()->where('salon_id', $salon->id)->exists()
            : false;

        $reviewsCount = Review::where('salon_id', $salon->id)
            ->where('is_approved', true)
            ->count();

        $ratingDistribution = [
            5 => Review::where('salon_id', $salon->id)->where('rating', 5)->where('is_approved', true)->count(),
            4 => Review::where('salon_id', $salon->id)->where('rating', 4)->where('is_approved', true)->count(),
            3 => Review::where('salon_id', $salon->id)->where('rating', 3)->where('is_approved', true)->count(),
            2 => Review::where('salon_id', $salon->id)->where('rating', 2)->where('is_approved', true)->count(),
            1 => Review::where('salon_id', $salon->id)->where('rating', 1)->where('is_approved', true)->count(),
        ];

        $similarSalons = Salon::where('status', 'approved')
            ->where('city', $salon->city)
            ->where('id', '!=', $salon->id)
            ->take(4)
            ->get();

        return view('frontend.salons.show', compact(
            'salon',
            'isFavorite',
            'reviewsCount',
            'ratingDistribution',
            'similarSalons'
        ));
    }

    // ✅ YEH METHOD ADD KARO (Gallery Page ke liye)
    public function gallery($slug)
    {
        $salon = Salon::with(['gallery' => function($q) {
            $q->where('is_active', true)->orderBy('sort_order', 'asc');
        }])->where('slug', $slug)->where('status', 'approved')->firstOrFail();
        
        return view('frontend.salons.gallery', compact('salon'));
    }
}