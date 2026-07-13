<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OwnerGalleryController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    /**
     * ✅ Table: 'galleries' | Column: 'image_path'
     */
    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $photosRaw = DB::table('galleries')
                ->where('salon_id', $salon->id)
                ->whereNull('deleted_at')
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();

            $photos = $photosRaw->map(function ($p) {
                // ✅ image_path se storage URL banana
                $url = null;
                if (!empty($p->image_path)) {
                    $url = asset('storage/' . $p->image_path);
                }

                // Category naam category_id se
                $categoryName = 'hair';
                if (!empty($p->category_id)) {
                    $cat = DB::table('categories')->find($p->category_id);
                    $categoryName = $cat ? strtolower($cat->name) : 'hair';
                }

                return [
                    'id'         => $p->id,
                    'url'        => $url,
                    'caption'    => $p->caption ?? '',
                    'category'   => $categoryName,
                    'sort_order' => $p->sort_order ?? 0,
                    'views'      => $p->views ?? 0,
                ];
            })->toArray();

            $totalPhotos      = count($photos);
            $totalViews       = array_sum(array_column($photos, 'views'));
            $uniqueCategories = count(array_unique(array_column($photos, 'category')));

            return view('owner.gallery.index', compact(
                'photos',
                'totalPhotos',
                'totalViews',
                'uniqueCategories'
            ));

        } catch (\Exception $e) {
            Log::error('Gallery Index Error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return view('owner.gallery.index', [
                'photos'           => [],
                'totalPhotos'      => 0,
                'totalViews'       => 0,
                'uniqueCategories' => 0,
            ])->with('error', 'Unable to load gallery: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return redirect()->route('owner.gallery.index');
    }

    /**
     * ✅ 'galleries' table mein insert | 'image_path' column
     */
    public function store(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.gallery.index')
                    ->with('error', 'Salon not found.');
            }

            $request->validate([
                'image'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                'caption'  => 'nullable|string|max:255',
                'category' => 'nullable|string',
            ]);

            // ✅ Image storage/gallery/ mein save
            $path = $request->file('image')->store('gallery', 'public');

            // Category ID dhoondhna
            $categoryId = null;
            if ($request->filled('category')) {
                $cat = DB::table('categories')
                    ->where('salon_id', $salon->id)
                    ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->category) . '%'])
                    ->first();
                $categoryId = $cat ? $cat->id : null;
            }

            $maxOrder = DB::table('galleries')
                ->where('salon_id', $salon->id)
                ->max('sort_order') ?? 0;

            DB::table('galleries')->insert([
                'salon_id'    => $salon->id,
                'category_id' => $categoryId,
                'image_path'  => $path,       // ✅ image_path
                'caption'     => $request->caption ?? null,
                'sort_order'  => $maxOrder + 1,
                'views'       => 0,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ]);

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Photo uploaded successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery Store Error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->route('owner.gallery.index')
                ->with('error', 'Unable to upload photo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        return redirect()->route('owner.gallery.index');
    }

    public function edit($id)
    {
        return redirect()->route('owner.gallery.index');
    }

    /**
     * ✅ 'galleries' table update | caption + category
     */
    public function update(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();

            $photo = DB::table('galleries')
                ->where('id', $id)
                ->where('salon_id', $salon->id)
                ->whereNull('deleted_at')
                ->first();

            if (!$photo) {
                return redirect()->route('owner.gallery.index')
                    ->with('error', 'Photo not found.');
            }

            $categoryId = $photo->category_id;
            if ($request->filled('category')) {
                $cat = DB::table('categories')
                    ->where('salon_id', $salon->id)
                    ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->category) . '%'])
                    ->first();
                if ($cat) $categoryId = $cat->id;
            }

            DB::table('galleries')
                ->where('id', $id)
                ->where('salon_id', $salon->id)
                ->update([
                    'caption'     => $request->caption ?? $photo->caption,
                    'category_id' => $categoryId,
                    'updated_at'  => now(),
                ]);

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Photo updated successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery Update Error: ' . $e->getMessage());
            return redirect()->route('owner.gallery.index')
                ->with('error', 'Unable to update photo.');
        }
    }

    /**
     * ✅ Soft delete + storage file delete | image_path column
     */
    public function destroy($id)
    {
        try {
            $salon = $this->getOwnerSalon();

            $photo = DB::table('galleries')
                ->where('id', $id)
                ->where('salon_id', $salon->id)
                ->whereNull('deleted_at')
                ->first();

            if (!$photo) {
                return redirect()->route('owner.gallery.index')
                    ->with('error', 'Photo not found.');
            }

            // ✅ Storage se file delete karo (image_path column)
            if (!empty($photo->image_path) && Storage::disk('public')->exists($photo->image_path)) {
                Storage::disk('public')->delete($photo->image_path);
            }

            // Soft delete
            DB::table('galleries')
                ->where('id', $id)
                ->where('salon_id', $salon->id)
                ->update(['deleted_at' => now()]);

            return redirect()->route('owner.gallery.index')
                ->with('success', 'Photo deleted!');

        } catch (\Exception $e) {
            Log::error('Gallery Destroy Error: ' . $e->getMessage());
            return redirect()->route('owner.gallery.index')
                ->with('error', 'Unable to delete photo.');
        }
    }

    /**
     * ✅ Reorder — 'galleries' table
     */
    public function reorder(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            $order = $request->input('order', []);

            foreach ($order as $sortOrder => $photoId) {
                DB::table('galleries')
                    ->where('id', $photoId)
                    ->where('salon_id', $salon->id)
                    ->update([
                        'sort_order' => $sortOrder,
                        'updated_at' => now(),
                    ]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Gallery Reorder Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
