<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('services')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('category-icons', 'public');
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        // Load the services relation + count so the edit page shows the
        // real number of services instead of always falling back to 0.
        $category->load('services')->loadCount('services');

        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('category-icons', 'public');
        }
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}