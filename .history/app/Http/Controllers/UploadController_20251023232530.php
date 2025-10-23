<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Services\OpenRouterService;
use Illuminate\Support\Facades\Log; // Make sure Log is imported

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    /**
     * Store the uploaded image, analyze it, and redirect back.
     *
     * @param Request $request
     * @param OpenRouterService $openRouter  // REFINEMENT: Use dependency injection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, OpenRouterService $openRouter)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $file = $request->file('image');

            // REFINEMENT: Simplified Cloudinary upload call
            $uploaded = Cloudinary::upload($file->getRealPath(), [
                'resource_type' => 'image',
                'folder' => 'uploads',
            ]);

            // REFINEMENT: Use the object-oriented getter
            $uploadedFileUrl = $uploaded->getSecurePath();

            // Call OpenRouter service to analyze the image
            try {
                // $openRouter is now injected, no need for app()
                $analysis = $openRouter->analyzeImage($uploadedFileUrl);

                $analysisResult = null;
                
                Log::debug('OpenRouter raw response:', ['analysis' => $analysis]);
                
                if (isset($analysis['success']) && $analysis['success']) {
                    $content = $analysis['content'] ?? null;
                    Log::debug('OpenRouter content:', [
                        'content' => $content,
                        'type' => gettype($content),
                    ]);
                    
                    if (is_string($content)) {
                        
                        // --- START OF THE FIX ---
                        
                        // ADDED: Regex to extract the JSON block from the AI's response.
                        // This handles "```json\n{...}\n```" and other text.
                        $jsonString = $content;
                        if (preg_match('/```json\s*(\{.*\})\s*```/s', $content, $matches)) {
                            // Found markdown JSON block
                            $jsonString = $matches[1];
                        } else if (preg_match('/(\{.*\})/s', $content, $matches)) {
                            // Fallback: Find the first curly-brace-enclosed block
                            $jsonString = $matches[1];
                        }

                        Log::debug('Cleaned JSON string:', ['json' => $jsonString]);
                        
                        // CHANGED: Decode the *cleaned* JSON string
                        $decoded = json_decode($jsonString, true);
                        
                        // --- END OF THE FIX ---

                        Log::debug('Decoded content:', [
                            'decoded' => $decoded,
                            'error' => json_last_error_msg(),
                            'has_maladies' => isset($decoded['maladies']),
                        ]);
                        
                        // Validate if decoded content matches expected structure
                        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['maladies'])) {
                            $analysisResult = $decoded;
                        } else {
                            // CHANGED: Improved error message
                            $analysisResult = ['error' => 'Invalid JSON response from AI. Error: ' . json_last_error_msg()];
                        }
                    } else {
                        $analysisResult = ['error' => 'Unexpected response format (not a string) from AI service'];
                    }
                } else {
                    $analysisResult = ['error' => $analysis['error'] ?? 'Unknown error', 'raw' => $analysis['raw_response'] ?? null];
                }
                
                Log::debug('Final analysis result:', ['result' => $analysisResult]);
            } catch (\Throwable $e) {
                Log::error('OpenRouter analyze error: ' . $e->getMessage());
                $analysisResult = ['error' => $e->getMessage()];
            }

            // Redirect back with success message, image URL and analysis
            return redirect()->back()->with([
                'success' => 'Image uploaded and analyzed successfully!',
                'image' => $uploadedFileUrl,
                'analysis' => $analysisResult,
            ]);

        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Upload failed: ' . $e->getMessage()]);
        }
    }
}