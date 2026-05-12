<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent';
    }

    public function processUserMessage(string $message, string $categoriesText): ?array
    {
        $today = now()->format('Y-m-d');

        $systemInstruction = "
            Você é um assistente financeiro inteligente em um chat. Hoje é {$today}.
            Sua tarefa é interpretar a mensagem do usuário e retornar SEMPRE um objeto JSON estrito.
            
            Regra Principal ('intent'):
            - 'intent': OBRIGATÓRIO. Use 'register' (para anotar novos gastos/ganhos), 'query' (para perguntas sobre relatórios, quanto gastou, saldos) ou 'chat' (para conversa fiada).
            
            Se 'intent' for 'register' (Registrar):
            - 'amount': DEVE ser em CENTAVOS (inteiro). Ex: 25,50 vira 2550.
            - 'type': 'expense' (despesa) ou 'income' (receita).
            - 'description': Resumo curto (ex: 'Uber', 'Almoço').
            - 'category_id': O ID numérico da categoria correspondente da lista abaixo.
            - 'content': Uma resposta curta e amigável confirmando o registro.
            
            Se 'intent' for 'query' (Consultar):
            - 'query_category_id': O ID da categoria pesquisada, ou null se for geral.
            - 'start_date': A data inicial da busca no formato 'YYYY-MM-DD'. Calcule a data corretamente baseada no dia de hoje.
            - 'end_date': A data final da busca no formato 'YYYY-MM-DD'. Se for até o momento atual, use a data de hoje.
            - 'content': Deixe como null.

            Categorias Disponíveis:
            {$categoriesText}
        ";

        
  
        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemInstruction]]
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $message]]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
            ]
        ];

        try {
            $response = Http::post("{$this->baseUrl}?key={$this->apiKey}", $payload);

            if ($response->failed()) {
                Log::error('Erro na API do Gemini: ' . $response->body());
                return null;
            }

            $jsonText = $response->json('candidates.0.content.parts.0.text');
            
            $jsonText = trim(str_replace(['```json', '```'], '', $jsonText));
            
            $decoded = json_decode($jsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Falha ao ler JSON da IA: ' . $jsonText);
                return null;
            }

            return $decoded;

        } catch (\Exception $e) {
            Log::error('Exceção ao processar Gemini: ' . $e->getMessage());
            return null;
        }
    }
}
