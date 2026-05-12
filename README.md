# API задач (Laravel)

REST API для управления задачами: создание, просмотр, обновление и удаление записей в SQLite. Сборка рассчитана на **PHP 8.3** и **Laravel 13**.

## Возможности

- CRUD по сущности «задача» (`title`, опционально `description`, `status`).
- Валидация статусов при создании и обновлении.
- Маршруты доступны с префиксом `/api/...` и дублируются без префикса (`/tasks` — то же самое, что `/api/tasks`).

## Требования

| Вариант | Что нужно |
|--------|-----------|
| Docker | Docker и Docker Compose |
| Локально | PHP 8.3 с расширением `pdo_sqlite`, Composer |

## Запуск в Docker

Из корня репозитория:

```bash
docker compose up --build
```

После старта контейнер:

- поднимает `php artisan serve` на порту **8002** внутри контейнера;
- создаёт при необходимости `database/database.sqlite` в томе;
- выполняет миграции при каждом запуске.

Приложение с хоста: **http://localhost:8002**

Проверка живости Laravel: **http://localhost:8002/up**

## Локальный запуск без Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

По умолчанию сервер слушает **http://127.0.0.1:8000**. При необходимости укажите порт: `php artisan serve --port=8002`.

Убедитесь, что в `.env` задано `DB_CONNECTION=sqlite` и при необходимости `DB_DATABASE` указывает на файл SQLite (для типового `.env.example` достаточно пустого `DB_DATABASE` и файла `database/database.sqlite`).

## API (кратко)

Базовые URL (пример для Docker на порту 8002):

- `GET    http://localhost:8002/api/tasks` — список задач
- `POST   http://localhost:8002/api/tasks` — создать задачу
- `GET    http://localhost:8002/api/tasks/{id}` — одна задача
- `PUT` / `PATCH http://localhost:8002/api/tasks/{id}` — обновить
- `DELETE http://localhost:8002/api/tasks/{id}` — удалить

Те же пути без префикса `api`: `/tasks`, `/tasks/{id}`.

### Создание задачи (`POST`)

Тело JSON:

```json
{
  "title": "Название",
  "description": "Описание (необязательно)",
  "status": "new"
}
```

Допустимые значения `status` при **создании**: `new`, `pending`, `in_progress`, `completed`.

### Обновление (`PUT` / `PATCH`)

Поля опциональны, но если передаёте `status`, допустимы только: `pending`, `in_progress`, `completed` (без `new`).

## Тесты

```bash
composer test
```

или

```bash
php artisan test
```

## Лицензия

Проект основан на шаблоне Laravel; фреймворк распространяется под [лицензией MIT](https://opensource.org/licenses/MIT).
