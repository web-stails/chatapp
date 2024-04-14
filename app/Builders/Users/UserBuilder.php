<?php

namespace App\Builders\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder
{
    /**
     * Получить выборку пользователей.
     *
     * @return Builder
     */
    public function handle(): Builder
    {
        return User::query();
    }
}
