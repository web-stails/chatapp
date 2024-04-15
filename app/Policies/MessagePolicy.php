<?php

namespace App\Policies;

use App\Enums\Permissions\MessagePermissionsEnum;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        /** @var Chat $chat */
        $chat = request()->route('chat');

        return $chat->isUser($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        /** @var Chat $chat */
        $chat = request()->route('chat');

        return $chat->isUser($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Message $message): bool
    {
        return $message->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $message): bool
    {
        return $message->user_id === $user->id;
    }
}
