<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OwnerCategoryController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    // ✅ Har category ko alag icon aur color milega name ke hisab se
    private function getCategoryStyle(string $name, int $id): array
    {
        $nameMap = [
            'hair styling'    => ['icon' => 'scissors',          'color' => 'cat-pink'],
            'hair'            => ['icon' => 'scissors',          'color' => 'cat-pink'],
            'nail care'       => ['icon' => 'hand-index-thumb',  'color' => 'cat-purple'],
            'nails'           => ['icon' => 'hand-index-thumb',  'color' => 'cat-purple'],
            'facial'          => ['icon' => 'emoji-smile',       'color' => 'cat-green'],
            'skin care'       => ['icon' => 'droplet-fill',      'color' => 'cat-green'],
            'spa'             => ['icon' => 'flower1',           'color' => 'cat-teal'],
            'makeup'          => ['icon' => 'palette-fill',      'color' => 'cat-orange'],
            'bridal'          => ['icon' => 'heart-fill',        'color' => 'cat-gold'],
            'body treatment'  => ['icon' => 'person-fill',       'color' => 'cat-blue'],
            'massage'         => ['icon' => 'activity',          'color' => 'cat-teal'],
            'waxing'          => ['icon' => 'stars',             'color' => 'cat-orange'],
            'threading'       => ['icon' => 'pencil-fill',       'color' => 'cat-purple'],
            'color'           => ['icon' => 'brush-fill',        'color' => 'cat-gold'],
            'hair color'      => ['icon' => 'brush-fill',        'color' => 'cat-gold'],
            'eyebrow'         => ['icon' => 'eye-fill',          'color' => 'cat-blue'],
            'eyelash'         => ['icon' => 'eye',               'color' => 'cat-purple'],
            'mehndi'          => ['icon' => 'flower3',           'color' => 'cat-orange'],
        ];

        $lowerName = strtolower(trim($name));

        // Name match karo
        foreach ($nameMap as $key => $style) {
            if (str_contains($lowerName, $key)) {
                return $style;
            }
        }

        // Fallback: ID ke hisab se rotate karo (har category alag color)
        $fallbacks = [
            ['icon' => 'grid-fill',        'color' => 'cat-gold'],
            ['icon' => 'stars',            'color' => 'cat-purple'],
            ['icon' => 'gem',              'color' => 'cat-green'],
            ['icon' => 'lightning-fill',   'color' => 'cat-blue'],
            ['icon' => 'fire',             'color' => 'cat-orange'],
            ['icon' => 'heart-fill',       'color' => 'cat-pink'],
            ['icon' => 'flower1',          'color' => 'cat-teal'],
        ];

        return $fallbacks[$id % count($fallbacks)];
    }

    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $categories = Category::withCount('services')
                ->where('salon_id', $salon->id)
                ->orderBy('name')
                ->get()
                ->map(function ($category) {
                    $style = $this->getCategoryStyle($category->name, $category->id);
                    return [
                        'id'          => $category->id,
                        'name'        => $category->name,
                        'count'       => $category->services_count,
                        'icon'        => $style['icon'],   // ✅ alag icon
                        'icon_bg'     => $style['color'],  // ✅ alag color
                        'status'      => $category->is_active ? 'Active' : 'Inactive',
                        'description' => $category->description ?? '',
                    ];
                });

            return view('owner.categories.index', compact('categories'));

        } catch (\Exception $e) {
            Log::error('Category Index Error: ' . $e->getMessage());
            return view('owner.categories.index', ['categories' => collect([])])
                ->with('error', 'Unable to load categories.');
        }
    }

    public function create()
    {
        return view('owner.categories.create');
    }

    public function store(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'icon_color'  => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Auto style assign
            $style = $this->getCategoryStyle($request->name, rand(0, 6));

            Category::create([
                'salon_id'    => $salon->id,
                'name'        => $request->name,
                'description' => $request->description,
                'icon_color'  => $request->icon_color ?? $style['color'],
                'is_active'   => $request->status === 'Active',
            ]);

            return redirect()->route('owner.categories.index')
                ->with('success', 'Category "' . $request->name . '" created successfully!');

        } catch (\Exception $e) {
            Log::error('Category Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to create category.')->withInput();
        }
    }

    public function show($id)
    {
        try {
            $salon    = $this->getOwnerSalon();
            $category = Category::withCount('services')
                ->where('salon_id', $salon->id)
                ->find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')->with('error', 'Category not found.');
            }

            $style = $this->getCategoryStyle($category->name, $category->id);

            $servicesInCategory = Service::where('category_id', $category->id)
                ->where('salon_id', $salon->id)
                ->select('id', 'name', 'duration', 'price', 'is_active')
                ->get()
                ->map(fn($s) => [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'duration' => $s->duration,
                    'price'    => $s->price,
                    'status'   => $s->is_active ? 'Active' : 'Inactive',
                ]);

            $categoryData = [
                'id'             => $category->id,
                'name'           => $category->name,
                'description'    => $category->description ?? 'No description provided.',
                'count'          => $category->services_count,
                'icon'           => $style['icon'],
                'icon_bg'        => $style['color'],
                'status'         => $category->is_active ? 'Active' : 'Inactive',
                'total_bookings' => 0,
            ];

            return view('owner.categories.show', [
                'category'           => $categoryData,
                'servicesInCategory' => $servicesInCategory,
            ]);

        } catch (\Exception $e) {
            Log::error('Category Show Error: ' . $e->getMessage());
            return redirect()->route('owner.categories.index')->with('error', 'Category not found.');
        }
    }

    public function edit($id)
    {
        try {
            $salon    = $this->getOwnerSalon();
            $category = Category::where('salon_id', $salon->id)->find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')->with('error', 'Category not found.');
            }

            $style = $this->getCategoryStyle($category->name, $category->id);

            $categoryData = [
                'id'          => $category->id,
                'name'        => $category->name,
                'description' => $category->description,
                'icon_bg'     => $category->icon_color ?? $style['color'],
                'status'      => $category->is_active ? 'Active' : 'Inactive',
            ];

            return view('owner.categories.edit', ['category' => $categoryData]);

        } catch (\Exception $e) {
            Log::error('Category Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.categories.index')->with('error', 'Category not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $salon    = $this->getOwnerSalon();
            $category = Category::where('salon_id', $salon->id)->find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')->with('error', 'Category not found.');
            }

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'icon_color'  => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $category->update([
                'name'        => $request->name,
                'description' => $request->description,
                'icon_color'  => $request->icon_color ?? $category->icon_color,
                'is_active'   => $request->status === 'Active',
            ]);

            return redirect()->route('owner.categories.index')
                ->with('success', 'Category "' . $category->name . '" updated successfully!');

        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update category.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $salon    = $this->getOwnerSalon();
            $category = Category::where('salon_id', $salon->id)->find($id);

            if (!$category) {
                return redirect()->route('owner.categories.index')->with('error', 'Category not found.');
            }

            $categoryName = $category->name;
            $serviceCount = Service::where('category_id', $category->id)->count();

            if ($serviceCount > 0) {
                return redirect()->route('owner.categories.index')
                    ->with('error', 'Cannot delete "' . $categoryName . '" — it has ' . $serviceCount . ' service(s).');
            }

            $category->delete();

            return redirect()->route('owner.categories.index')
                ->with('success', 'Category "' . $categoryName . '" deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage());
            return redirect()->route('owner.categories.index')->with('error', 'Unable to delete category.');
        }
    }
}