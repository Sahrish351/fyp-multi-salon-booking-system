<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerCategoryController extends Controller
{
    /**
     * Route: GET /owner/categories  -->  name: owner.categories.index
     *
     * Categories grid page dikhana.
     *
     * BAAD ME: Database se real categories laayen (service count ke saath):
     *   $categories = Category::where('salon_id', auth()->user()->salon_id)
     *       ->withCount('services')->get();
     */
    public function index(Request $request)
    {
        $categories = $this->dummyCategories();

        return view('owner.categories.index', compact('categories'));
    }

    /**
     * Route: GET /owner/categories/create  -->  name: owner.categories.create
     *
     * Nayi category add karne ka page.
     */
    public function create()
    {
        return view('owner.categories.create');
    }

    /**
     * Route: POST /owner/categories  -->  name: owner.categories.store
     *
     * Nayi category add karna (Create page form submit).
     *
     * BAAD ME:
     *   $request->validate([
     *       'name'        => 'required|string|max:255',
     *       'description' => 'nullable|string',
     *       'icon_color'  => 'required|string',
     *       'status'      => 'required|in:Active,Inactive',
     *   ]);
     *   Category::create([...$request->validated(), 'salon_id' => auth()->user()->salon_id]);
     */
    public function store(Request $request)
    {
        return redirect()->route('owner.categories.index')->with('success', 'Category added successfully!');
    }

    /**
     * Route: GET /owner/categories/{category}  -->  name: owner.categories.show
     *
     * Category ka detail page (icon, stats, us category ke services).
     *
     * BAAD ME:
     *   $category = Category::findOrFail($id)->toArray();
     *   $servicesInCategory = Service::where('category_id', $id)->get();
     */
    public function show($category)
    {
        $categoryData = $this->findDummyCategory($category);

        $servicesInCategory = [
            ['name' => 'Premium Haircut', 'duration' => 45, 'price' => 85,  'status' => 'Active'],
            ['name' => 'Hair Coloring',   'duration' => 90, 'price' => 120, 'status' => 'Active'],
            ['name' => 'Beard Trim',      'duration' => 30, 'price' => 45,  'status' => 'Active'],
        ];

        return view('owner.categories.show', [
            'category' => $categoryData,
            'servicesInCategory' => $servicesInCategory,
        ]);
    }

    /**
     * Route: GET /owner/categories/{category}/edit  -->  name: owner.categories.edit
     *
     * Category edit karne ka page (pre-filled form).
     *
     * BAAD ME:
     *   $category = Category::findOrFail($id)->toArray();
     */
    public function edit($category)
    {
        $categoryData = $this->findDummyCategory($category);

        return view('owner.categories.edit', ['category' => $categoryData]);
    }

    /**
     * Route: PUT /owner/categories/{category}  -->  name: owner.categories.update
     *
     * Category update karna (Edit page form submit).
     *
     * BAAD ME:
     *   $category = Category::findOrFail($id);
     *   $category->update($request->validated());
     */
    public function update(Request $request, $category)
    {
        return redirect()->route('owner.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Route: DELETE /owner/categories/{category}  -->  name: owner.categories.destroy
     *
     * Category delete karna (Delete confirmation modal submit).
     *
     * BAAD ME:
     *   Category::findOrFail($id)->delete();
     *   // Services iss category ke delete nahi honge, category_id null ho jayega
     */
    public function destroy(Request $request, $category)
    {
        return redirect()->route('owner.categories.index')->with('success', 'Category deleted successfully!');
    }

    /**
     * Dummy/demo categories list — BAAD ME ye method hata kar Eloquent
     * query se replace kar dena.
     */
    private function dummyCategories(): array
    {
        return [
            ['id' => 1, 'name' => 'Hair Styling',     'count' => 12, 'icon_bg' => 'cat-gold',   'status' => 'Active'],
            ['id' => 2, 'name' => 'Nail Care',        'count' => 8,  'icon_bg' => 'cat-purple', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Facial Treatment', 'count' => 6,  'icon_bg' => 'cat-green',  'status' => 'Active'],
            ['id' => 4, 'name' => 'Spa & Massage',    'count' => 5,  'icon_bg' => 'cat-blue',   'status' => 'Active'],
            ['id' => 5, 'name' => 'Makeup',           'count' => 7,  'icon_bg' => 'cat-orange', 'status' => 'Active'],
            ['id' => 6, 'name' => 'Body Treatment',   'count' => 4,  'icon_bg' => 'cat-pink',   'status' => 'Active'],
            ['id' => 7, 'name' => 'Hair Treatment',   'count' => 6,  'icon_bg' => 'cat-teal',   'status' => 'Active'],
        ];
    }

    /**
     * Dummy category id ke mutabiq dhoondna — BAAD ME Category::findOrFail($id) se replace karna.
     */
    private function findDummyCategory($id): array
    {
        $categories = $this->dummyCategories();

        foreach ($categories as $cat) {
            if ($cat['id'] == $id) {
                $cat['description']     = 'Services related to ' . strtolower($cat['name']) . ' for our valued clients.';
                $cat['total_bookings']  = 120;
                return $cat;
            }
        }

        // Fallback agar id na milay
        return [
            'id' => $id,
            'name' => 'Hair Styling',
            'count' => 12,
            'icon_bg' => 'cat-gold',
            'status' => 'Active',
            'description' => 'Services related to hair styling for our valued clients.',
            'total_bookings' => 120,
        ];
    }
}
