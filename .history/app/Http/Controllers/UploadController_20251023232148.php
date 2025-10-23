<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Services\OpenRouterService;

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

            // Call OpenRouter service to analyze the image
            try {
                $openRouter = app(OpenRouterService::class);
                $analysis = $openRouter->analyzeImage($uploadedFileUrl);

                // Prepare analysis data to flash to session
                $analysisResult = null;
                
                // Debug log the raw analysis response
                \Log::debug('OpenRouter raw response:', ['analysis' => $analysis]);
                
                if (isset($analysis['success']) && $analysis['success']) {
                    $content = $analysis['content'] ?? null;
                    \Log::debug('OpenRouter content:', [
                        'content' => $content,
                        'type' => gettype($content),
                        'length' => is_string($content) ? strlen($content) : 0
                    ]);
                    
                    if (is_string($content)) {
                        $decoded = json_decode($content, true);
                        \Log::debug('Decoded content:', [
                            'decoded' => $decoded,
                            'error' => json_last_error_msg(),
                            'has_maladies' => isset($decoded['maladies']),
                            'maladies_type' => isset($decoded['maladies']) ? gettype($decoded['maladies']) : 'not set'
                        ]);
                        
                        // Validate if decoded content matches expected structure
                        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['maladies'])) {
                            $analysisResult = $decoded;
                        } else {
                            // If invalid JSON, return error
                            $analysisResult = ['error' => content];
                        }
                    } else {
                        $analysisResult = ['error' => 'Unexpected response format from AI service'];
                    }
                } else {
                    $analysisResult = ['error' => $analysis['error'] ?? 'Unknown error', 'raw' => $analysis['raw_response'] ?? null];
                }
                
                // Debug log the final result
                \Log::debug('Final analysis result:', ['result' => $analysisResult]);
            } catch (\Throwable $e) {
                \Log::error('OpenRouter analyze error: ' . $e->getMessage());
                $analysisResult = ['error' => $e->getMessage()];
            }

            // Redirect back with success message, image URL and analysis
            return redirect()->back()->with([
                'success' => 'Image uploaded and analyzed successfully!',
                'image' => $uploadedFileUrl,
                'analysis' => $analysisResult,
            ]);

        } catch (\Exception $e) {
            \Log::error('Cloudinary upload error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Upload failed: ' . $e->getMessage()]);
        }
    }
}