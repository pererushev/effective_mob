#!/bin/sh
set -e
cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
  php artisan key:generate --force --no-interaction
fi

mkdir -p database
touch database/database.sqlite

php artisan migrate --force --no-interaction

exec php artisan serve --host=0.0.0.0 --port=8002
