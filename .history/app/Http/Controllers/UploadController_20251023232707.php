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

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $file = $request->file('image');

            // --- REVERTED TO YOUR ORIGINAL CODE ---
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
            // --- END OF REVERT ---


            // Call OpenRouter service to analyze the image
            try {
                // --- REVERTED TO YOUR ORIGINAL CODE ---
                $openRouter = app(OpenRouterService::class);
                // --- END OF REVERT ---
                
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
                        
                        // Regex to extract the JSON block from the AI's response.
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
                        
                        // Decode the *cleaned* JSON string
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