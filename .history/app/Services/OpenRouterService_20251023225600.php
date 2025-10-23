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
        // Ensure no trailing spaces in the default value
        $this->baseUrl = rtrim(config('services.openrouter.base_url', 'https://openrouter.ai/api/v1'), '/');
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

        $endpoint = $this->baseUrl . '/chat/completions'; // Concatenate base URL with endpoint

        $body = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => <<<'PROMPT'
Analyse cette image médicale et retourne les maladies détectées avec tous leurs champs suivants (indique le type de chaque champ) :
- nom: string
- description: string
- traitement: string
- prevention: string
- status: string
- type: string

Pour chaque maladie, inclure la liste des symptômes associés avec tous leurs champs :
- nom: string
- description: string
- gravite: string
- status: string

Ajoute également le pourcentage de confiance pour chaque maladie sous le champ :
- confiance: int

IMPORTANT : Si l'image ou le texte **ne contient aucune information** sur des maladies ou des symptômes, retourne **uniquement** le JSON suivant :
{"maladies": []}

Retourne **uniquement** les données en JSON, sans aucune explication, en français.
PROMPT
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
            Log::info('OpenRouter request', ['endpoint' => $endpoint, 'body' => $body]);

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->post($endpoint, $body);

            $status = $response->status();
            $json = $response->json(); // This might fail if response is not JSON

            Log::info('OpenRouter response', ['status' => $status, 'response' => $json]);

            if (! $response->successful()) {
                // Log the raw body if JSON parsing failed earlier or if status indicates error
                if ($response->body()) {
                     Log::warning('OpenRouter non-successful raw response body', ['raw_body' => $response->body()]);
                }
                return ['success' => false, 'error' => 'API returned non-success status: ' . $status, 'raw_response' => $json, 'status' => $status];
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

            if (!is_string($content)) {
                 Log::warning('OpenRouter content is not a string', ['content_type' => gettype($content), 'content' => $content]);
            }

            return ['success' => true, 'content' => $content, 'raw_response' => $json];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Log specific client errors (e.g., 401 Unauthorized, 404 Not Found)
            Log::error('OpenRouterService Client Error: ' . $e->getMessage(), [
                'request_body' => $body,
                'response_body' => $e->getResponse()->getBody()->getContents(),
                'response_status' => $e->getResponse()->getStatusCode()
            ]);
            return ['success' => false, 'error' => 'Client Error: ' . $e->getMessage()];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Log server errors (e.g., 500 Internal Server Error)
             Log::error('OpenRouterService Server Error: ' . $e->getMessage(), [
                'request_body' => $body,
                'response_body' => $e->getResponse()->getBody()->getContents(),
                'response_status' => $e->getResponse()->getStatusCode()
            ]);
            return ['success' => false, 'error' => 'Server Error: ' . $e->getMessage()];
        } catch (\Throwable $e) {
            Log::error('OpenRouterService General Error: ' . $e->getMessage(), ['exception' => $e]);
            return ['success' => false, 'error' => 'General Error: ' . $e->getMessage()];
        }
    }
}