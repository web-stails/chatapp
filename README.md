# тестовое задание

Разработать REST API сервис для онлайн чата (только backend). Предположим, что данный REST API сервис
будет использоваться абстрактным мобильным приложением.


### Поднять проект 
1. Скопировать `.env.example` в `.env`
   1. По необходимости отредактировать параметры портов и паролей БД
2. Запустить команду `docker compose -f docker-compose.dev.yml up -d`
3. Выполнить миграции `docker compose exec api php artisan migrate`

### Создание пользователя
1. Через консольную команду `docker compose exec api php artisan user:create`
2. через EndPoint `POST /api/auth/register` 
```json
{
   "email": "email@email.com",
   "password": "Password1",
   "firstName": "Surname",
   "LastName": "Name" 
}
```
