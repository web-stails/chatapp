<?php

namespace App\Builders\Chats;

use App\Models\Chat;
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
        return Chat::query()
            ->whereHas('users', fn (Builder $builder) => $builder->whereId($this->user->id));
    }
}
