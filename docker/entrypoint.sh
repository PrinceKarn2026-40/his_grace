#!/bin/bash

cd /var/www/html

php artisan migrate --force --no-interaction 2>/dev/null || true
php artisan storage:link 2>/dev/null || true

exec "$@"
