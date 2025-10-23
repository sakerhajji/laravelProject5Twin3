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

            $uploaded = Cloudinary::getFacadeRoot()->uploadApi()->upload(
                $file->getRealPath(),
                ['resource_type' => 'image']
            );

            // Cloudinary uploadApi returns an array with secure_url
            $uploadedFileUrl = is_array($uploaded) && isset($uploaded['secure_url']) ? $uploaded['secure_url'] : null;

            if (! $uploadedFileUrl) {
                throw new \Exception('Failed to get secure_url from Cloudinary response');
            }

            return response()->json([
                'url' => $uploadedFileUrl
            ]);
        } catch (\Exception $e) {
            \Log::error('Cloudinary upload error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
