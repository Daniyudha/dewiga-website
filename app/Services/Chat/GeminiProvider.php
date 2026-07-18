<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AIProviderInterface
{
    /**
     * Send chat completion request to Gemini API.
     * Uses Authorization: Bearer header for OAuth tokens (AQ...), 
     * or ?key= query param for API keys (AIzaSy...).
     */
    public function chat(string $userMessage, ?string $dataContext, array $conversationHistory = [], string $locale = 'id'): array
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.0-flash');

        if (empty($apiKey)) {
            Log::info('Chatbot: Gemini API key not configured, skipping Gemini');
            return $this->errorResponse('API key not configured');
        }

        $systemPrompt = config('chatbot.system_prompt');
        $contents = $this->buildContents($systemPrompt, $userMessage, $dataContext, $conversationHistory);

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => (float) config('services.gemini.temperature', 0.7),
                'maxOutputTokens' => (int) config('services.gemini.max_tokens', 1024),
                'topP' => 0.95,
                'topK' => 40,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ],
        ];

        // Determine auth method
        if (str_starts_with($apiKey, 'AIzaSy')) {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($apiKey);
            $headers = ['Content-Type' => 'application/json'];
        } else {
            // OAuth token or x-goog-api-key → Bearer header
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ];
        }

        $timeout = (int) config('services.gemini.timeout', 30);

        try {
            $startTime = microtime(true);

            $response = Http::timeout($timeout)
                ->withHeaders($headers)
                ->post($endpoint, $payload);

            $elapsed = round((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();

                Log::warning('Chatbot: Gemini API HTTP error', [
                    'status' => $status,
                    'body' => substr($body, 0, 200),
                ]);

                // If Bearer failed, try x-goog-api-key as fallback
                if (($status === 401 || $status === 403) && !str_starts_with($apiKey, 'AIzaSy')) {
                    Log::info('Chatbot: Bearer failed, trying x-goog-api-key');
                    return $this->tryWithApiKeyHeader($payload, $apiKey, $model, $timeout);
                }

                return $this->errorResponse("HTTP {$status}");
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if (empty($text)) {
                Log::warning('Chatbot: Empty Gemini response');
                return $this->errorResponse('Empty response');
            }

            Log::info('Chatbot: Gemini response received', [
                'length' => mb_strlen($text),
                'time_ms' => $elapsed,
            ]);

            return [
                'text' => $text,
                'success' => true,
                'model' => $model,
                'response_time_ms' => $elapsed,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Chatbot: Gemini connection error: ' . $e->getMessage());
            return $this->errorResponse('Connection error');
        } catch (\Exception $e) {
            Log::error('Chatbot: Gemini error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    protected function tryWithApiKeyHeader(array $payload, string $apiKey, string $model, int $timeout): array
    {
        try {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";
            $response = Http::timeout($timeout)
                ->withHeaders(['Content-Type' => 'application/json', 'x-goog-api-key' => $apiKey])
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                if (!empty($text)) {
                    Log::info('Chatbot: Gemini x-goog-api-key worked');
                    return ['text' => $text, 'success' => true, 'model' => $model];
                }
            }
        } catch (\Exception $e) {}

        return $this->errorResponse('All auth methods failed');
    }

    protected function buildContents(string $systemPrompt, string $userMessage, ?string $dataContext, array $history): array
    {
        $contents = [];

        // System prompt as first user message (Gemini doesn't have native system role)
        $contents[] = ['role' => 'user', 'parts' => [['text' => $systemPrompt]]];
        $contents[] = ['role' => 'model', 'parts' => [['text' => 'Baik, saya Mas Pandu, asisten resmi Desa Wisata Gabugan. Saya siap membantu wisatawan dengan ramah dan informatif.']]];

        // Conversation history
        foreach (array_slice($history, -(int) config('chatbot.session.max_history', 10)) as $msg) {
            if (isset($msg['role'], $msg['text'])) {
                $role = $msg['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = ['role' => $role, 'parts' => [['text' => $msg['text']]]];
            }
        }

        // Current message with RAG context
        if (empty($dataContext) || str_contains($dataContext, 'TIDAK ADA DATA')) {
            $text = "PERTANYAAN: {$userMessage}\n\nDATA: Tidak ditemukan di database.\nINSTRUKSI: Sampaikan info belum tersedia, arahkan ke WhatsApp +62 813-2885-6252.";
        } else {
            $text = "PERTANYAAN: {$userMessage}\n\nDATA DATABASE:\n{$dataContext}\nINSTRUKSI: Jawab berdasarkan data. Gunakan gaya Mas Pandu yang ramah. Jangan mengarang.";
        }

        $contents[] = ['role' => 'user', 'parts' => [['text' => $text]]];
        return $contents;
    }

    protected function errorResponse(string $reason): array
    {
        return ['text' => '', 'success' => false, 'error' => $reason];
    }
}