<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Services\AiService;
use App\Services\MessageService;
use App\Services\TransactionService;
use Illuminate\Http\Request;


class ChatController
{
    public function sendMessage(
        Request $request, 
        AiService $aiService , 
        TransactionService $transactionService, 
        MessageService $messageService
    ) {
        $validated = $request->validate([
            'message'=> 'required|max:1000',
        ]);

        $userMessage = $validated['message'];
        $messageService->createUserMessage($request->user(), $userMessage);
        
        $categoriesText = Category::all(['id', 'name', 'type'])
            ->map(fn($c) => "ID: {$c->id} | {$c->name} ({$c->type->value})")
            ->implode("\n");

        $aiResult = $aiService->processUserMessage($userMessage, $categoriesText);

        if (!$aiResult) {
            return response()->json([
                'error' => 'Não consegui processar sua mensagem agora. Tente de novo.'
            ], 500);
        }
        
        $botContent = "Entendido!";

        if (isset($aiResult['intent']) && $aiResult['intent'] === 'register') {
            $transactionService->createFromAiResult($request->user(), $aiResult);
            $botContent = $aiResult['content'] ?? 'Transação registrada!';
        }
        
        elseif (isset($aiResult['intent']) && $aiResult['intent'] === 'query') {
            $botContent = $transactionService->calculateTotalFromAi($request->user(), $aiResult);
        }

        $botMessage = $messageService->createBotMessage($request->user(), $botContent);

        return response()->json(['message' => $botMessage->content], 200);
    }
}
