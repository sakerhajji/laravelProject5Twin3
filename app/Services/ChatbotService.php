<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatbotService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
    }

    public function sendMessage(string $message): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => url('/'),
                'X-Title' => 'Smart Dashboard Chatbot',
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un assistant utile qui aide les utilisateurs du Smart Dashboard.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);

            if ($response->failed()) {
                return "Erreur: " . $response->body();
            }

            return $response->json()['choices'][0]['message']['content'] ?? 'Pas de réponse.';
        } catch (\Exception $e) {
            return 'Erreur serveur: ' . $e->getMessage();
        }
    }
}
