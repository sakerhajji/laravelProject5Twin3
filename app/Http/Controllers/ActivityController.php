<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http; // Add at the top

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('category', 'user');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $activities = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('backoffice.activities.index', compact('activities', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('backoffice.activities.create', compact('categories'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'time' => 'required|string|max:50',
        'category_id' => 'required|exists:categories,id',
        'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480', // 20MB max
    ]);

    $validated['user_id'] = Auth::id();

    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $isVideo = in_array($file->getClientOriginalExtension(), ['mp4', 'mov', 'avi']);

        // ✅ Upload using correct method
        $uploadedFile = Cloudinary::getFacadeRoot()->uploadApi()->upload(
            $file->getRealPath(),
            ['resource_type' => $isVideo ? 'video' : 'image']
        );

        $validated['media_url'] = $uploadedFile['secure_url']; // use array key
        $validated['media_type'] = $isVideo ? 'video' : 'image';
    }

    Activity::create($validated);

    return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully!');
}

    public function show(Activity $activity)
    {
        return view('backoffice.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $categories = Category::all();
        return view('backoffice.activities.edit', compact('activity', 'categories'));
    }

public function update(Request $request, Activity $activity)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'time' => 'required|string|max:50',
        'category_id' => 'required|exists:categories,id',
        'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
    ]);

    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $isVideo = in_array($file->getClientOriginalExtension(), ['mp4', 'mov', 'avi']);

        // ✅ Upload using correct method
        $uploadedFile = Cloudinary::getFacadeRoot()->uploadApi()->upload(
            $file->getRealPath(),
            ['resource_type' => $isVideo ? 'video' : 'image']
        );

        $validated['media_url'] = $uploadedFile['secure_url'];
        $validated['media_type'] = $isVideo ? 'video' : 'image';
    }

    $activity->update($validated);

    return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully!');
}

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully!');
    }

// Show the check exercise page
public function checkExercisePage()
{
    return view('front.activities.checkexercice'); // correct path
}

// Handle the form submission and call Roboflow API
public function checkExercise(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:5120',
    ]);

    $imagePath = $request->file('image')->getRealPath();
    $apiKey = env('ROBOFLOW_API_KEY');

    $response = Http::attach(
        'file', file_get_contents($imagePath), $request->file('image')->getClientOriginalName()
    )->post("https://detect.roboflow.com/exercise-detection-crmtf/1?api_key={$apiKey}");

    if ($response->failed()) {
        return response()->json([
            'error' => 'Roboflow API request failed',
            'body' => $response->body()
        ], 500);
    }

    $result = $response->json();

    return response()->json($result);
}



}
