<?php

namespace App\Services\Chats;

use App\Exceptions\Chats\IsChatExistsException;
use App\Models\Chat;
use App\Models\User;
use App\Services\AbstractModelService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ChatService extends AbstractModelService
{
    private User $userAuthor;
    private User $userConnection;
    public array $relationList = [
        'user'
    ];
    /**
     * @return Model
     */
    public function model(): Model
    {
        return resolve(Chat::class);
    }

    public function createChat(array $fields, User $userAuthor, User $userConnection): Model
    {
        $this->userAuthor = $userAuthor;
        $this->userConnection = $userConnection;

        return $this->create($fields);
    }


    protected function beforeCreate()
    {
        if (
            ($this->userAuthor?->id ?? false) === false
            && ($this->userConnection?->id ?? false) === false
        ) {
            throw new Exception('Не корректное создание чата');
        }

        $isChatExists = Chat::query()
            ->whereHas('users', fn (Builder $builder) => $builder->whereId($this->userAuthor?->id))
            ->whereHas('users', fn (Builder $builder) => $builder->whereId($this->userConnection?->id))
            ->exists();

        if ($isChatExists) {
            throw new IsChatExistsException();
        }

    }

    /**
     * Получить чат по id пользователей
     *
     * @param User $userAuthor
     * @param User $userConnection
     *
     * @return Model
     */
    public function getChatFromUsers(User $userAuthor, User $userConnection): ?Model
    {
        return Chat::query()
            ->whereHas('users', fn (Builder $builder) => $builder->whereId($userAuthor->id))
            ->whereHas('users', fn (Builder $builder) => $builder->whereId($userConnection->id))
            ->first();
    }

    protected function afterCreate(Model $model): void
    {
        $model->users()->sync([
            $this->userAuthor->id,
            $this->userConnection->id
        ]);
    }
}
