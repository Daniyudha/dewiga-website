<?php

namespace App\Services\Chat;

/**
 * AIProviderInterface
 * 
 * Contract for AI service providers (Groq, Gemini, OpenAI, etc).
 * Implements Dependency Inversion Principle (SOLID).
 * Swap provider by changing binding in AppServiceProvider.
 */
interface AIProviderInterface
{
    /**
     * Send a chat completion request to the AI provider.
     *
     * @param string $userMessage  The user's question
     * @param string|null $dataContext  RAG context from database search
     * @param array $conversationHistory  Previous messages [['role'=>'user'|'assistant','text'=>'...']]
     * @param string $locale  Current locale (id/en)
     * @return array  ['text'=>'...', 'success'=>bool, 'error'=>'...', 'model'=>'...', 'token_usage'=>[...]?]
     */
    public function chat(string $userMessage, ?string $dataContext, array $conversationHistory = [], string $locale = 'id'): array;
}