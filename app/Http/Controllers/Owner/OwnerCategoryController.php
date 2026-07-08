<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OwnerCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        try {
            // ✅ SAB CATEGORIES DIKHAO
            $categories = Category::withCount('services')
                ->orderBy('name')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'count' => $category->services_count,
                        'icon_bg' => $category->icon_color ?? 'cat-gold',
                        'status' => $category->is_active ? 'Active' : 'Inactive',
                        'description' => $category->description,
                    ];
                });

            return view('owner.categories.index', compact('categories'));

        } catch (\Exception $e) {
            Log::error('Category Index Error: ' . $e->getMessage());
            return view('owner.categories.index', ['categories' => collect([])])
                ->with('error', 'Unable to load categories.');
        }
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('owner.categories.create');
    }

    /**
     * Store a newly created category in database.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string|max:500',
                'icon_color' => 'nullable|string',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ CREATE CATEGORY
            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'icon_color' => $request->icon_color ?? 'cat-gold',
                'is_active' => $request->status === 'Active' ? true : false,
            ]);

            return redirect()->route('owner.categories.index')
                ->with('success', 'Category "' . $request->name . '" created successfully!');

        } catch (\Exception $e) {
            Log::error('Category Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to create category.')
                ->withInput();
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        try {
            $category = Category::withCount('services')->find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')
                    ->with('error', 'Category not found.');
            }

            $servicesInCategory = Service::where('category_id', $category->id)
                ->select('id', 'name', 'duration', 'price', 'is_active')
                ->get()
                ->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'duration' => $service->duration,
                        'price' => $service->price,
                        'status' => $service->is_active ? 'Active' : 'Inactive',
                    ];
                });

            $categoryData = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description ?? 'No description provided.',
                'count' => $category->services_count,
                'icon_bg' => $category->icon_color ?? 'cat-gold',
                'status' => $category->is_active ? 'Active' : 'Inactive',
                'total_bookings' => 0,
            ];

            return view('owner.categories.show', [
                'category' => $categoryData,
                'servicesInCategory' => $servicesInCategory,
            ]);

        } catch (\Exception $e) {
            Log::error('Category Show Error: ' . $e->getMessage());
            return redirect()->route('owner.categories.index')
                ->with('error', 'Category not found.');
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')
                    ->with('error', 'Category not found.');
            }

            $categoryData = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'icon_bg' => $category->icon_color ?? 'cat-gold',
                'status' => $category->is_active ? 'Active' : 'Inactive',
            ];

            return view('owner.categories.edit', ['category' => $categoryData]);

        } catch (\Exception $e) {
            Log::error('Category Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.categories.index')
                ->with('error', 'Category not found.');
        }
    }

    /**
     * Update the specified category in database.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')
                    ->with('error', 'Category not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string|max:500',
                'icon_color' => 'nullable|string',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ UPDATE CATEGORY
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'icon_color' => $request->icon_color ?? $category->icon_color,
                'is_active' => $request->status === 'Active' ? true : false,
            ]);

            return redirect()->route('owner.categories.index')
                ->with('success', 'Category "' . $category->name . '" updated successfully!');

        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update category.')
                ->withInput();
        }
    }

    /**
     * Remove the specified category from database.
     */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')
                    ->with('error', 'Category not found.');
            }

            $categoryName = $category->name;

            // ✅ CHECK IF CATEGORY HAS SERVICES
            $serviceCount = Service::where('category_id', $category->id)->count();
            
            if ($serviceCount > 0) {
                return redirect()->route('owner.categories.index')
                    ->with('error', 'Cannot delete "' . $categoryName . '" because it has ' . $serviceCount . ' service(s).');
            }

            // ✅ DELETE CATEGORY
            $category->delete();

            return redirect()->route('owner.categories.index')
                ->with('success', 'Category "' . $categoryName . '" deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage());
            return redirect()->route('owner.categories.index')
                ->with('error', 'Unable to delete category.');
        }
    }

    /**
     * Helper: Get icon color
     */
    private function getIconColor($id): string
    {
        $colors = ['cat-gold', 'cat-purple', 'cat-green', 'cat-blue', 'cat-orange', 'cat-pink', 'cat-teal'];
        return $colors[$id % count($colors)];
    }
}