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

            // Log the full response for debugging
            \Log::info('Cloudinary response:', ['response' => $uploaded]);

            if (!is_array($uploaded)) {
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
