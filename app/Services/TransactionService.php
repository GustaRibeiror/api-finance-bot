<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    public function createFromAiResult(User $user, array $aiResult): ?Transaction
    {
        if (!isset($aiResult['intent']) || $aiResult['intent'] !== 'register') {
            return null;
        }
        
        return Transaction::create([
            'user_id'     => $user->id,
            'category_id' => $aiResult['category_id'],
            'description' => $aiResult['description'],
            'amount'      => $aiResult['amount'],
            'type'        => $aiResult['type'],
        ]);
    }

    public function calculateTotalFromAi(User $user, array $aiResult): string
    {
        $query = Transaction::where('user_id', $user->id);

        if (!empty($aiResult['query_category_id'])) {
            $query->where('category_id', $aiResult['query_category_id']);
        }

        if (!empty($aiResult['start_date']) && !empty($aiResult['end_date'])) {
            $query->whereDate('created_at', '>=', $aiResult['start_date'])
                  ->whereDate('created_at', '<=', $aiResult['end_date']);
        }

        $totalInCents = $query->sum('amount');
        $totalFormatted = number_format($totalInCents / 100, 2, ',', '.');

        return "Você gastou um total de R$ {$totalFormatted} nesse período.";
    }
}