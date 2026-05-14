<?php

namespace App\Services;

use App\Models\Concept;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model = 'llama3-8b-8192';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->apiUrl = config('services.groq.url');
    }

    public function generate(Concept $concept): array
    {
        $prompt = $this->buildPrompt($concept);

        Log::info('Groq Request', [
            'url' => $this->apiUrl,
            'model' => $this->model,
            'prompt_length' => strlen($prompt),
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, [
            'model'    => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens'  => 1000,
            'temperature' => 0.7,
        ]);

        Log::info('Groq Response Status', ['status' => $response->status()]);

        if ($response->failed()) {
            Log::error('Groq API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Groq API request failed: ' . $response->status());
        }

        return $this->parseResponse($response->json());
    }

    private function buildPrompt(Concept $concept): string
    {
        return <<<PROMPT
You are a senior technical interviewer.
Generate exactly 5 interview questions for the following concept.
Return ONLY a JSON array of strings, no explanation, no markdown.

Concept: {$concept->title}
Domain: {$concept->domain->name}
Difficulty: {$concept->difficulty->value}
Explanation: {$concept->explanation}

Example output: ["Question 1?", "Question 2?", "Question 3?", "Question 4?", "Question 5?"]
PROMPT;
    }

    private function parseResponse(array $json): array
    {
        $content = $json['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new \Exception('Empty response from Groq API.');
        }

        $questions = json_decode(trim($content), true);

        if (!is_array($questions) || empty($questions)) {
            throw new \Exception('Could not parse questions from Groq API response.');
        }

        return $questions;
    }
}