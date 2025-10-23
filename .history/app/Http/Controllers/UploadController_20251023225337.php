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

            // Configure Cloudinary with credentials
            config(['cloudinary.cloud_name' => env('CLOUDINARY_CLOUD_NAME')]);
            config(['cloudinary.api_key' => env('CLOUDINARY_KEY')]);
            config(['cloudinary.api_secret' => env('CLOUDINARY_SECRET')]);

            $uploaded = Cloudinary::getFacadeRoot()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'resource_type' => 'image',
                    'folder' => 'uploads' // Optional: organize uploads in a folder
                ]
            );

            // Log the full response for debugging
            \Log::info('Cloudinary response:', ['response' => $uploaded]);

            if (!is_array($uploaded)) {
                \Log::error('Cloudinary unexpected response type:', ['type' => gettype($uploaded)]);
                throw new \Exception('Unexpected response from Cloudinary: ' . json_encode($uploaded));
            }

            // Check for secure_url or url in the response
            $uploadedFileUrl = $uploaded['secure_url'] ?? $uploaded['url'] ?? null;

            if (!$uploadedFileUrl) {
                throw new \Exception('Failed to get URL from Cloudinary response: ' . json_encode($uploaded));
            }

            return response()->json([
                'success' => true,
                'url' => $uploadedFileUrl,
                'full_response' => $uploaded // temporarily include full response for debugging
            ]);
        } catch (\Exception $e) {
            \Log::error('Cloudinary upload error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
