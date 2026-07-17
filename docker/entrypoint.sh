#!/bin/bash
set -e

cd /var/www

echo "==> Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear  2>/dev/null || true
php artisan view:clear   2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force --no-interaction

echo "==> Creating storage symlink..."
php artisan storage:link 2>/dev/null || true

echo "==> Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "==> Starting PHP-FPM..."
exec "$@"
