<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Send a chat message and get AI response.
     *
     * POST /api/chat/send
     * Body: { message: string, locale?: string }
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
            'locale' => 'nullable|string|in:id,en',
        ]);

        $userMessage = trim($request->input('message'));
        $locale = $request->input('locale', app()->getLocale());

        try {
            $response = $this->chatService->processMessage($userMessage, $locale);

            return response()->json([
                'success' => true,
                'data' => [
                    'text' => $response['text'],
                    'found_data_count' => $response['found_data_count'],
                    'response_time_ms' => $response['response_time_ms'],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('ChatController: Error processing message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'data' => [
                    'text' => config('chatbot.fallback_message_id'),
                ],
            ], 500);
        }
    }

    /**
     * Get welcome message without calling AI.
     *
     * GET /api/chat/welcome
     */
    public function welcome(Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        if ($locale === 'en') {
            $message = "Hello! 🙏 I'm <strong>Mas Pandu</strong>, the official virtual assistant of Gabugan Tourism Village, Sleman, Yogyakarta.<br><br>I can provide complete information about:<br>• 🌿 Salak Pondoh Agro-tourism Packages<br>• 🌾 Rice field activities & agricultural education<br>• 🎨 Batik, gamelan & Javanese culture<br>• 🏡 Homestay (Live-in) experience<br>• 🍲 Traditional culinary at the foot of Merapi<br><br>Feel free to ask anything! 😊";
        } else {
            $message = "Halo! 🙏 Saya <strong>Mas Pandu</strong>, asisten virtual resmi Desa Wisata Gabugan, Sleman, Yogyakarta.<br><br>Saya siap memberikan informasi lengkap tentang:<br>• 🌿 Paket Agrowisata Salak Pondoh<br>• 🌾 Aktivitas sawah & edukasi pertanian<br>• 🎨 Belajar batik tulis, gamelan, dan budaya Jawa<br>• 🏡 Pengalaman menginap (Live-in) di Homestay<br>• 🍲 Kuliner khas pedesaan lereng Merapi<br><br>Silakan tanya apa saja! 😊";
        }

        return response()->json([
            'success' => true,
            'data' => [
                'text' => $message,
            ],
        ]);
    }

    /**
     * Clear chat session and start fresh.
     *
     * POST /api/chat/clear
     */
    public function clearSession(Request $request): JsonResponse
    {
        $this->chatService->clearSession();

        return response()->json([
            'success' => true,
            'message' => 'Chat session cleared.',
        ]);
    }
}