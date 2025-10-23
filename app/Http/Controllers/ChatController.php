<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $userMessage = $request->input('message');

        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'timeout' => 120,
        ]);

        try {
            $response = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
                    'messages' => [
                        ['role' => 'user', 'content' => $userMessage]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            $assistantReply = $body['choices'][0]['message']['content'] ?? 'No response.';

            return response()->json(['assistant' => $assistantReply]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
