<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        $file = $request->file('image');

        // Upload to Cloudinary
        $uploaded = Cloudinary::getFacadeRoot()->uploadApi()->upload(
            $file->getRealPath(),
            [
                'resource_type' => 'image',
                'folder' => 'uploads',
            ]
        );

        // Get the secure URL
        $uploadedFileUrl = $uploaded['secure_url'] ?? $uploaded['url'];

        // Redirect back with success message and image URL
        return redirect()->back()->with([
            'success' => 'Image uploaded successfully!',
            'image' => $uploadedFileUrl
        ]);

    } catch (\Exception $e) {
        \Log::error('Cloudinary upload error: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'Upload failed: ' . $e->getMessage()]);
    }
}

}
