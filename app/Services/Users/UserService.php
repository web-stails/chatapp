<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\AbstractModelService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService extends AbstractModelService
{
    /**
     * @return Model
     */
    public function model(): Model
    {
        return resolve(User::class);
    }

    /**
     * Правим массив параметров перед созданием пользователя.
     *
     * @param $attributes
     *
     * @return array
     */
    public function beforeCreate(array $attributes): array
    {
        $attributes['last_name'] = $attributes['lastName'];
        $attributes['first_name'] = $attributes['firstName'];

        $attributes['password'] = Hash::make($attributes['password']);

        return $this->filterFieldsModel($attributes, ['lastName', 'firstName']);
    }


    /**
     * Правим массив параметров перед сохранением данных пользователя.
     *
     * @param array $attributes
     *
     * @return array
     */
    public function beforeUpdate(array $attributes): array
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        if (isset($attributes['lastName'])) {
            $attributes['last_name'] = $attributes['lastName'];
        }

        if (isset($attributes['firstName'])) {
            $attributes['first_name'] = $attributes['firstName'];
        }

        return $this->filterFieldsModel($attributes, ['lastName', 'firstName']);
    }

    /**
     * Получить пользователя по Email
     *
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::whereEmail($email)->first();
    }
}
