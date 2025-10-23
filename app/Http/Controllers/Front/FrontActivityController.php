<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Category;

class FrontActivityController extends Controller
{
    // Show all activities
    public function index(Request $request)
    {
        $activities = Activity::latest()
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->paginate(12);

        return view('front.activities.index', compact('activities'));
    }

    // Show activities by category
    public function byCategory(Category $category, Request $request)
    {
        $activities = Activity::where('category_id', $category->id)
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->paginate(12);

        return view('front.activities.activitesbycategory', compact('activities', 'category'));
    }
}
