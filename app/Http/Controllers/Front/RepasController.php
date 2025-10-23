<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class RepasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $repas = $user->repas()->with('aliments')->latest()->paginate(10);
        return view('front.repas.index', compact('repas'));
    }



    public function analyzeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $image = $request->file('image');
        $imageData = base64_encode(file_get_contents($image->getRealPath()));

        $apiKey = config('services.gemini.api_key');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Analyze the food in this image. Identify the food item and provide its name in French in a JSON object with the key 'food_name'. Also provide a 'nutrition' key containing estimated values for 'calories', 'protein', 'carbohydrates', and 'fat'. If it's not food, return an error."
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => $image->getMimeType(),
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::post($url, $payload);
            // Retry the request 3 times with a 200ms delay if it fails with a server error (like 503)
            $response = Http::retry(3, 200)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $analysis = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Could not analyze the image.';

                $json_string = str_replace(['```json', '```'], '', $analysis);
                $json_data = json_decode(trim($json_string), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($json_data['food_name']) && isset($json_data['nutrition'])) {
                    $nutrition = $json_data['nutrition'];
                    $standardized_data = [
                        'food_name' => $json_data['food_name'],
                        'nutrition' => [
                            'calories' => intval($nutrition['calories'] ?? 0),
                            'protein' => intval($nutrition['protein'] ?? 0),
                            'carbohydrates' => intval($nutrition['carbohydrates'] ?? 0),
                            'fat' => intval($nutrition['fat'] ?? 0),
                        ]
                    ];
                    return response()->json($standardized_data);
                } else {
                    return response()->json(['error' => 'Failed to analyze image.', 'raw' => $analysis], 500);
                }
            }

            return response()->json(['error' => 'Failed to analyze image.', 'details' => $response->body()], $response->status());
            // Provide a more user-friendly error for temporary issues
            if ($response->serverError()) {
                return response()->json(['error' => 'The image analysis service is currently busy. Please try again in a moment.'], $response->status());
            }

            return response()->json(['error' => 'Failed to communicate with the image analysis service.', 'details' => $response->body()], $response->status());
        } catch (\Exception $e) {
            Log::error(' API request failed: ' . $e->getMessage());
            Log::error('Gemini API request failed: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
