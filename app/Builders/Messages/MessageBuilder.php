<?php

namespace App\Builders\Messages;

use App\Models\Chat;
use App\Models\Message;
use App\Services\Messages\MessageService;
use Illuminate\Database\Eloquent\Builder;

class MessageBuilder
{
    public function __construct(private readonly Chat $chat)
    {}

    /**
     * Получить builder модели Message.
     *
     * @return Builder
     */
    public function handle(): Builder
    {
        return Message::query()
            ->where('chat_id', $this->chat->id)
            ->orderBy('created_at', 'desc')
            ->with(MessageService::RELATION_LIST);
    }
}
