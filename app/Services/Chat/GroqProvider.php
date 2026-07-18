<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GroqProvider
 *
 * AI provider implementation using Groq API (OpenAI-compatible chat completions).
 * Uses llama-3.3-70b-versatile or configurable model.
 */
class GroqProvider implements AIProviderInterface
{
    /**
     * Send chat completion request to Groq API.
     */
    public function chat(string $userMessage, ?string $dataContext, array $conversationHistory = [], string $locale = 'id'): array
    {
        $apiKey = config('services.groq.api_key');
        $model = config('services.groq.model', 'llama-3.3-70b-versatile');

        if (empty($apiKey)) {
            Log::error('Chatbot: Groq API key not configured');
            return $this->errorResponse('API key not configured');
        }

        $systemPrompt = config('chatbot.system_prompt');
        $messages = $this->buildMessages($systemPrompt, $userMessage, $dataContext, $conversationHistory);

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (float) config('services.groq.temperature', 0.7),
            'max_tokens' => (int) config('services.groq.max_tokens', 1024),
            'top_p' => 0.95,
            'stream' => false,
        ];

        $timeout = (int) config('services.groq.timeout', 30);

        try {
            $startTime = microtime(true);

            $response = Http::timeout($timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            $elapsed = round((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();

                Log::error('Chatbot: Groq API HTTP error', [
                    'status' => $status,
                    'body' => substr($body, 0, 500),
                    'model' => $model,
                ]);

                return $this->errorResponse("HTTP {$status}: " . substr($body, 0, 200));
            }

            $data = $response->json();

            // Extract response text (OpenAI-compatible format)
            $text = $data['choices'][0]['message']['content'] ?? '';

            if (empty($text)) {
                Log::warning('Chatbot: Empty response from Groq', ['data' => $data]);
                return $this->errorResponse('Empty response from AI');
            }

            // Extract token usage
            $tokenUsage = null;
            if (isset($data['usage'])) {
                $tokenUsage = [
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                    'total_tokens' => $data['usage']['total_tokens'] ?? 0,
                ];
            }

            Log::info('Chatbot: Groq response received', [
                'length' => mb_strlen($text),
                'time_ms' => $elapsed,
                'model' => $model,
                'token_usage' => $tokenUsage,
            ]);

            return [
                'text' => $text,
                'success' => true,
                'model' => $model,
                'token_usage' => $tokenUsage,
                'response_time_ms' => $elapsed,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Chatbot: Groq connection error: ' . $e->getMessage());
            return $this->errorResponse('Connection error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Chatbot: Groq error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Build messages array for OpenAI-compatible chat API.
     */
    protected function buildMessages(string $systemPrompt, string $userMessage, ?string $dataContext, array $history): array
    {
        $messages = [];

        // System prompt
        $messages[] = ['role' => 'system', 'content' => $systemPrompt];

        // Conversation history (last N rounds)
        $maxHistory = (int) config('chatbot.session.max_history', 10);
        foreach (array_slice($history, -$maxHistory) as $msg) {
            if (isset($msg['role'], $msg['text'])) {
                $role = $msg['role'] === 'assistant' ? 'assistant' : 'user';
                $messages[] = ['role' => $role, 'content' => $msg['text']];
            }
        }

        // Current message with RAG context
        if (empty($dataContext) || str_contains($dataContext, 'TIDAK ADA DATA YANG DITEMUKAN')) {
            $content = "PERTANYAAN PENGGUNA: {$userMessage}\n\n" .
                       "DATA: Tidak ditemukan informasi terkait di database.\n\n" .
                       "INSTRUKSI: Sampaikan dengan sopan bahwa informasi belum tersedia " .
                       "dan arahkan ke WhatsApp +62 813-2885-6252.";
        } else {
            $content = "PERTANYAAN PENGGUNA: {$userMessage}\n\n" .
                       "BERIKUT DATA DARI DATABASE DESA WISATA GABUGAN:\n" .
                       $dataContext . "\n\n" .
                       "INSTRUKSI: Gunakan data di atas untuk menjawab. " .
                       "Jangan mengarang informasi. Gunakan bahasa yang ramah, natural, dan tambahkan emoji yang relevan.";
        }

        $messages[] = ['role' => 'user', 'content' => $content];

        return $messages;
    }

    protected function errorResponse(string $reason): array
    {
        return [
            'text' => '',
            'success' => false,
            'error' => $reason,
        ];
    }
}