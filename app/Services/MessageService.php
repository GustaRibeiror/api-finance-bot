<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;

class MessageService
{
    public function createUserMessage(User $user, string $content): Message
    {
        return Message::create([
            'user_id' => $user->id,
            'content' => $content,
            'from'    => 'user',
        ]);
    }

    public function createBotMessage(User $user, ?string $aiContent): Message
    {
        $finalContent = $aiContent ?? 'Entendido.';

        return Message::create([
            'user_id' => $user->id,
            'content' => $finalContent,
            'from'    => 'bot',
        ]);
    }
}