<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Salon;
use Illuminate\Support\Facades\Auth;

class FavoriteSalonController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favoriteSalons()->with('reviews')->paginate(12);
        return view('client.favorites.index', compact('favorites'));
    }

    public function toggle(Salon $salon)
    {
        $existing = Favorite::where('client_id', Auth::id())->where('salon_id', $salon->id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Removed from favorites.');
        }

        Favorite::create(['client_id' => Auth::id(), 'salon_id' => $salon->id]);
        return back()->with('success', 'Added to favorites!');
    }
}