<?php

namespace App\Services\Messages;

use App\Http\Requests\Messages\MessageStoreRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\AbstractModelService;
use Illuminate\Database\Eloquent\Model;

class MessageService extends AbstractModelService
{
    public const RELATION_LIST = [
        'user'
    ];

    private Chat $chat;
    private User $user;

    /**
     * @return Model
     */
    public function model(): Model
    {
        return resolve(Message::class);
    }

    public function createMessage(MessageStoreRequest $request, Chat $chat, User $user): model
    {
        $this->chat = $chat;
        $this->user = $user;

        return $this->create($request->validated());
    }

    protected function beforeCreate(array &$attributes): void
    {
        $attributes['chat_id'] = $this->chat->id;
        $attributes['user_id'] = $this->user->id;
    }
}
