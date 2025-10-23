<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontCategoryController extends Controller
{
    public function index(Request $request)
    {
        // Base query
        $query = Category::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Fetch categories
        $categories = $query->paginate(12);

        return view('front.categories.index', compact('categories'));
    }
}
