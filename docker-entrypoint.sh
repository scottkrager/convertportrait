#!/bin/bash
set -e

# Create SQLite DB if it doesn't exist
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite

# Run migrations
php artisan migrate --force

# Cache config with real env vars
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure storage is writable
mkdir -p /var/www/html/storage/app/temp
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

exec apache2-foreground
