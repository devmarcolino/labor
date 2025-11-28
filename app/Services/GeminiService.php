<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function analyze($prompt)
    {
        $apiKey = env('GOOGLE_GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $response = Http::post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        return $response->json();
    }
}
