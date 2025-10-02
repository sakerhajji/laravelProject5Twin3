<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // add this at top


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('backoffice.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backoffice.categories.create');
    }

public function store(Request $request)
{
    // ✅ Validation happens here
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Handle file upload if image is present
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('categories', 'public');
    }

    // Save the category
    Category::create($validated);

    return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
}


    public function show(Category $category)
    {
        return view('backoffice.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('backoffice.categories.edit', compact('category'));
    }

public function update(Request $request, Category $category)
{
    // ✅ Validation
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Handle file upload if new image is uploaded
    if ($request->hasFile('image')) {
        if ($category->image) {
            Storage::disk('public')->delete($category->image); // delete old image
        }
        $validated['image'] = $request->file('image')->store('categories', 'public');
    }

    // Update category
    $category->update($validated);

    return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
}



    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
