<?php

namespace App\Console\Commands\Users;

use App\Services\Users\UserService;
use Illuminate\Console\Command;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание пользователя.';

    /**
     * Execute the console command.
     */
    public function handle(UserService $userService, ?string $email = null)
    {
        do {
            if (!$email) {
                $email = $this->ask('Укажите Email');
            }

            if ($user = $userService->findByEmail($email)) {
                $this->info('email=' . $email . ' Занят');

                $email = null;
            }
        } while ($user);

        $options = [
            'email'     => $email,
            'lastName'  => $this->ask('Укажите имя.'),
            'firstName' => $this->ask('Укажите Фамилию.'),
            'password'  => $this->secret('Укажите пароль.'),
        ];

        try {
            $userService->create($options);

            $this->info('Пользователь создан');
        } catch (\Exception $e) {
            $this->info('Возникли проблемы при создании пользователя:' . $e->getMessage());
        }
    }
}
