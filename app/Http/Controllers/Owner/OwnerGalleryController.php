<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Salon;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OwnerGalleryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $photos = Gallery::where('salon_id', $salon->id)
                ->with('category')
                ->orderBy('sort_order')
                ->get()
                ->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'url' => $photo->image_path ? asset('storage/' . $photo->image_path) : null,
                        'caption' => $photo->caption,
                        'category' => $photo->category->name ?? 'Uncategorized',
                        'category_id' => $photo->category_id,
                        'sort_order' => $photo->sort_order ?? 0,
                        'views' => $photo->views ?? 0,
                    ];
                });

            $totalPhotos = $photos->count();
            
        
            $totalViews = Gallery::where('salon_id', $salon->id)->sum('views') ?? 0;
     
            $categoriesCount = $photos->pluck('category')->unique()->filter()->count();

            $stats = [
                'total' => $totalPhotos,
                'total_views' => $totalViews,
                'categories' => $categoriesCount,
            ];

            return view('owner.gallery.index', compact('photos', 'stats'));

        } catch (\Exception $e) {
            Log::error('Gallery Index Error: ' . $e->getMessage());
            return view('owner.gallery.index', [
                'photos' => collect([]),
                'stats' => ['total' => 0, 'total_views' => 0, 'categories' => 0],
            ])->with('error', 'Unable to load gallery.');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
                'caption' => 'nullable|string|max:255',
                'category_id' => 'required|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

           
            $imagePath = $request->file('image')->store('gallery', 'public');

            $maxOrder = Gallery::where('salon_id', $salon->id)->max('sort_order') ?? 0;

         
            Gallery::create([
                'salon_id' => $salon->id,
                'category_id' => $request->category_id,
                'image_path' => $imagePath,
                'caption' => $request->caption,
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
                'views' => 0,
            ]);

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Photo uploaded successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to upload photo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $photo = Gallery::where('salon_id', $salon->id)->find($id);

            if (!$photo) {
                return redirect()->route('owner.gallery.index')
                    ->with('error', 'Photo not found.');
            }

            $validator = Validator::make($request->all(), [
                'caption' => 'nullable|string|max:255',
                'category_id' => 'required|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $photo->update([
                'caption' => $request->caption,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Photo updated successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery Update Error: ' . $e->getMessage());
            return redirect()->route('owner.gallery.index')
                ->with('error', 'Unable to update photo.');
        }
    }

    public function destroy($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $photo = Gallery::where('salon_id', $salon->id)->find($id);

            if (!$photo) {
                return redirect()->route('owner.gallery.index')
                    ->with('error', 'Photo not found.');
            }

          
            if ($photo->image_path && Storage::disk('public')->exists($photo->image_path)) {
                Storage::disk('public')->delete($photo->image_path);
            }

            $photo->delete();

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Photo deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery Destroy Error: ' . $e->getMessage());
            return redirect()->route('owner.gallery.index')
                ->with('error', 'Unable to delete photo.');
        }
    }

    public function reorder(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return response()->json(['success' => false, 'message' => 'Salon not found.'], 404);
            }

            $validator = Validator::make($request->all(), [
                'order' => 'required|array',
                'order.*' => 'integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Invalid order data.'], 400);
            }

            foreach ($request->order as $sortOrder => $photoId) {
                Gallery::where('id', $photoId)
                    ->where('salon_id', $salon->id)
                    ->update(['sort_order' => $sortOrder]);
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Gallery reordered successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery Reorder Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to reorder.'], 500);
        }
    }

    
    public function incrementView($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return response()->json(['success' => false, 'message' => 'Salon not found.'], 404);
            }

            $photo = Gallery::where('salon_id', $salon->id)->find($id);

            if (!$photo) {
                return response()->json(['success' => false, 'message' => 'Photo not found.'], 404);
            }

            $photo->increment('views');

            return response()->json(['success' => true, 'views' => $photo->views]);

        } catch (\Exception $e) {
            Log::error('Gallery View Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to increment view.'], 500);
        }
    }
}