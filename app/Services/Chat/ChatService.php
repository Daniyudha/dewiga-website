<?php

namespace App\Services\Chat;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ChatService
{
    protected DatabaseKnowledgeService $knowledgeService;
    protected GroqProvider $groqProvider;
    protected GeminiProvider $geminiProvider;
    protected SmartResponseService $smartResponse;
    protected QuickTemplateService $quickTemplates;

    public function __construct(
        DatabaseKnowledgeService $knowledgeService,
        GroqProvider $groqProvider,
        GeminiProvider $geminiProvider,
        SmartResponseService $smartResponse,
        QuickTemplateService $quickTemplates
    ) {
        $this->knowledgeService = $knowledgeService;
        $this->groqProvider = $groqProvider;
        $this->geminiProvider = $geminiProvider;
        $this->smartResponse = $smartResponse;
        $this->quickTemplates = $quickTemplates;
    }

    /**
     * Process a chat message.
     * Priority: QuickTemplate > Gemini > Groq > SmartResponse (from DB).
     */
    public function processMessage(string $userMessage, string $locale = 'id'): array
    {
        $startTime = microtime(true);
        $sessionId = $this->getSessionId();

        Log::info('Chatbot: Processing message', [
            'session_id' => $sessionId,
            'message' => $userMessage,
            'locale' => $locale,
        ]);

        // Step 0: Check quick template (instant response for common questions)
        $template = $this->quickTemplates->match($userMessage, $locale);
        if ($template !== null) {
            $elapsedMs = round((microtime(true) - $startTime) * 1000);
            $this->updateConversationHistory($userMessage, $template);
            return [
                'text' => $template,
                'success' => true,
                'source' => 'template',
                'found_data_count' => 0,
                'response_time_ms' => $elapsedMs,
            ];
        }

        // Step 1: RAG - search database for relevant data
        $searchResults = $this->knowledgeService->searchRelevantData($userMessage);
        $dataContext = $this->knowledgeService->formatResultsForPrompt($searchResults);
        $history = $this->getConversationHistory();

        // Step 2: Try Gemini AI first
        $aiResponse = $this->geminiProvider->chat($userMessage, $dataContext, $history, $locale);

        // Step 3: If Gemini failed, try Groq
        if (!$aiResponse['success']) {
            Log::info('Chatbot: Gemini failed, trying Groq');
            $aiResponse = $this->groqProvider->chat($userMessage, $dataContext, $history, $locale);
        }

        // Step 4: If all AI failed, fallback to SmartResponse from database
        if (!$aiResponse['success']) {
            $text = $this->smartResponse->generate($userMessage, $searchResults, $locale);
        } else {
            $text = $this->formatAiResponse($aiResponse['text']);
        }

        $elapsedMs = round((microtime(true) - $startTime) * 1000);

        // Step 5: Update conversation history
        $this->updateConversationHistory($userMessage, $text);

        // Step 6: Log to database
        try {
            ChatLog::create([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'found_data' => !empty($searchResults) ? json_encode($searchResults, JSON_UNESCAPED_UNICODE) : null,
                'prompt_sent' => $dataContext,
                'ai_response' => $text,
                'response_time_ms' => $elapsedMs,
                'locale' => $locale,
                'success' => $aiResponse['success'],
                'error_message' => $aiResponse['error'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::warning('Chatbot: Failed to save chat log: ' . $e->getMessage());
        }

        Log::info('Chatbot: Message processed', [
            'session_id' => $sessionId,
            'ai_success' => $aiResponse['success'],
            'tables_found' => count($searchResults),
            'time_ms' => $elapsedMs,
            'token_usage' => $aiResponse['token_usage'] ?? null,
        ]);

        return [
            'text' => $text,
            'success' => true,
            'source' => $aiResponse['success'] ? ($aiResponse['model'] ?? 'ai') : 'smart',
            'found_data_count' => count($searchResults),
            'response_time_ms' => $elapsedMs,
        ];
    }

    protected function getSessionId(): string
    {
        if (!Session::has('chat_session_id')) {
            Session::put('chat_session_id', Str::uuid()->toString());
            Session::put('chat_history', []);
        }
        return Session::get('chat_session_id');
    }

    protected function getConversationHistory(): array
    {
        return Session::get('chat_history', []);
    }

    protected function updateConversationHistory(string $userMessage, string $assistantResponse): void
    {
        $history = $this->getConversationHistory();
        $history[] = ['role' => 'user', 'text' => $userMessage];
        $history[] = ['role' => 'assistant', 'text' => $assistantResponse];

        $maxMessages = (int) config('chatbot.session.max_history', 10) * 2;
        if (count($history) > $maxMessages) {
            $history = array_slice($history, -$maxMessages);
        }
        Session::put('chat_history', $history);
    }

    /**
     * Format AI response text for display.
     */
    protected function formatAiResponse(string $text): string
    {
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace("/\n{2,}/", "\n", $text);

        $whatsappLink = config('chatbot.contact.whatsapp_link', 'https://api.whatsapp.com/send?phone=6281328856252');
        $text = preg_replace(
            '/\+62[\s]?8[\d]{1,2}[\s\-]?[\d]{4}[\s\-]?[\d]{4,5}/',
            '<a href="' . $whatsappLink . '" target="_blank" class="text-[#00a877] font-semibold hover:underline">$0</a>',
            $text
        );

        return nl2br($text);
    }

    public function clearSession(): void
    {
        Session::forget('chat_session_id');
        Session::forget('chat_history');
        Log::info('Chatbot: Session cleared');
    }
}