<?php

namespace App\Builders\Chats;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ChatBuilder
{
    public function __construct(private readonly User $user)
    {}

    /**
     * Получить builder модели Chat.
     *
     * @return Builder
     */
    public function handle(): Builder
    {
        $tableChat = with(new Chat())->getTable();
        $tableMessage = with(new Message())->getTable();

        $subSelect = Message::query()
            ->whereColumn($tableMessage . '.chat_id', '=', $tableChat . '.id')
            ->orderBy($tableMessage . '.created_at', 'desc')
            ->limit(1)
            ->getQuery();

        return Chat::query()
            ->whereHas('users', fn (Builder $builder) => $builder->whereId($this->user->id))
            ->select($tableChat . '.*')
            ->selectRaw('coalesce(m.created_at, ' . $tableChat . '.created_at) AS timestamp')
            ->leftJoinLateral($subSelect, 'm')
            ->orderBy('timestamp', 'desc')
            ->with([
                'user' => fn ($builder) => $builder->where('id', '<>', $this->user->id)
            ]);
    }
}
