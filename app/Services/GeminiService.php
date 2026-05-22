<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public function generate(string $prompt): ?string
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');

        if (!$apiKey) {
            return 'Gemini API key is missing. Please add GEMINI_API_KEY in your .env file.';
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.5,
                        'topP' => 0.9,
                        'maxOutputTokens' => 2048,
                        'thinkingConfig' => [
                            'thinkingBudget' => 0,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return 'AI response could not be generated right now. Please try again later.';
            }

            $parts = $response->json('candidates.0.content.parts', []);

            $text = collect($parts)
                ->pluck('text')
                ->filter()
                ->implode("\n");

            return trim($text) ?: 'No AI response generated.';
        } catch (\Throwable $e) {
            Log::error('Gemini API Exception', [
                'message' => $e->getMessage(),
            ]);

            return 'AI service is currently unavailable. Please try again later.';
        }
    }
}