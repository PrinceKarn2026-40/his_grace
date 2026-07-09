#!/bin/bash

cd /var/www/html

# Clear any cached config that might have wrong values
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Run migrations
php artisan migrate --force --no-interaction 2>/dev/null || true

# Create storage symlink
php artisan storage:link 2>/dev/null || true

# Set permissions
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec "$@"
