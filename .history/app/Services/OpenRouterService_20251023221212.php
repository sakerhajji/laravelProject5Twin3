<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected string $baseUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.openrouter.base_url', 'https://openrouter.ai/api/v1');
        $this->apiKey = config('services.openrouter.key');
    }

    /**
     * Analyze an image URL using the OpenRouter chat completion (Qwen VL) model.
     * Returns the raw model message content (expected JSON string) or an array with error info.
     *
     * @param string $imageUrl
     * @param string $model
     * @return array{success:bool, content?:mixed, raw_response?:array, error?:string}
     */
    public function analyzeImage(string $imageUrl, string $model = 'qwen/qwen-vl-plus'): array
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'error' => 'OpenRouter API key not configured (OPENROUTER_API_KEY)'];
        }

        $endpoint = rtrim($this->baseUrl, '/') . '/chat/completions';

        $body = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => "Analyse cette image médicale et retourne les maladies détectées avec tous leurs champs suivants (indique le type de chaque champ) :\n- nom: string\n- description: string\n- traitement: string\n- prevention: string\n- status: string\n- type: string\n\nPour chaque maladie, inclure la liste des symptômes associés avec tous leurs champs :\n- nom: string\n- description: string\n- gravite: string\n- status: string\n\nAjoute également le pourcentage de confiance pour chaque maladie sous le champ :\n- confiance: int\n\nIMPORTANT : Si l'image ou le texte **ne contient aucune information** sur des maladies ou des symptômes, retourne **uniquement** le JSON suivant :\n{"maladies": []}\n\nRetourne **uniquement** les données en JSON, sans aucune explication, en français."
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => ['url' => $imageUrl]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->post($endpoint, $body);

            $status = $response->status();
            $json = $response->json();

            Log::info('OpenRouter request', ['endpoint' => $endpoint, 'body' => $body]);
            Log::info('OpenRouter response', ['status' => $status, 'response' => $json]);

            if (! $response->successful()) {
                return ['success' => false, 'error' => 'API returned non-success status', 'raw_response' => $json, 'status' => $status];
            }

            // Expect structure similar to: { choices: [ { message: { content: ... } } ] }
            $choice = $json['choices'][0] ?? null;

            if (! $choice) {
                return ['success' => false, 'error' => 'No choices in response', 'raw_response' => $json];
            }

            $message = $choice['message'] ?? null;

            if (! $message) {
                return ['success' => false, 'error' => 'No message in choice', 'raw_response' => $json];
            }

            // The content may be a string or a structured object depending on the API.
            $content = $message['content'] ?? null;

            return ['success' => true, 'content' => $content, 'raw_response' => $json];
        } catch (\Throwable $e) {
            Log::error('OpenRouterService error: ' . $e->getMessage(), ['exception' => $e]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
