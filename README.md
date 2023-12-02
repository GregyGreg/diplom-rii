
## О проекте

Дипломный проект "Система приема и обработки заявок"

## Развертывание проекта

После выгрузки проекта на сервер требуется выполнить следующие команды:

`cp .env.example .env`
`composer install`
`php artisan key:generate`

Далее требуется заполнить конфигурационный файл .env, в частности следующие пункты:

```
APP_NAME="Название проекта"
APP_ENV=local
APP_DEBUG=false

DB_CONNECTION=pgsql //mysql, sqlite, sqlsrv ВЫБРАТЬ НУЖНОЕ СУБД
DB_HOST=127.0.0.1
DB_PORT=            // Порт подключения к БД
DB_DATABASE=        // Название базы
DB_USERNAME=        // Имя пользователя в БД
DB_PASSWORD=        // Пароль пользователя БД

FILAMENT_PATH="zayavki_rii" // Путь в адресной строке
```

Следующая команда активирует миграцию таблиц в БД:

`php artisan migrate`

Далее создаем супер-пользователя в системе командой:

`php artisan shield:super-admin`

Создаем права доступа:

`php artisan shield:install` Везде отвечаем 'yes'

Всю дополнительную информацию можно глянуть в документации [Laravel](https://laravel.com/docs) и [FillamentPHP](https://filamentphp.com/docs)
