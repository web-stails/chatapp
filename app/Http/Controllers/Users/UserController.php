<?php

namespace App\Http\Controllers\Users;

use App\Builders\Users\UserBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Services\Users\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Получить выборку пользователей.
     *
     * @param UserBuilder $builder
     *
     * @return UserCollection
     */
    public function index(UserBuilder $builder): UserCollection
    {
        return UserCollection::make($builder->handle());
    }

    /**
     * Создание пользователя.
     *
     * @param StoreRequest $request
     *
     * @return UserResource
     */
    public function store(StoreRequest $request): UserResource
    {
        $user = $this->userService->create($request->validated());

        return UserResource::make($user);
    }

    /**
     * Получить данные одного пользователя.
     *
     * @param User $user
     *
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    /**
     * Редактирование пользователя.
     *
     * @param User          $user
     * @param UpdateRequest $request
     *
     * @return UserResource
     */
    public function update(UpdateRequest $request, User $user): UserResource
    {
        $this->userService->update($user, $request->validated());

        return UserResource::make($user);
    }

    /**
     * Удаление пользователя.
     *
     * @param User $user
     *
     * @throws \Exception
     *
     * @return void
     */
    public function destroy(User $user): Response
    {
        $this->userService->delete($user);

        return response()->noContent();
    }
}
