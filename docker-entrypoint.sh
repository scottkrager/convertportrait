#!/bin/bash
set -e

# Parse DATABASE_URL into Laravel env vars if present
if [ -n "$DATABASE_URL" ]; then
    export DB_CONNECTION=pgsql
    # Extract components from postgresql://user:pass@host:port/dbname
    proto="$(echo $DATABASE_URL | grep :// | sed -e 's,^\(.*://\).*,\1,g')"
    url="$(echo ${DATABASE_URL/$proto/})"
    userpass="$(echo $url | grep @ | cut -d@ -f1)"
    hostport="$(echo ${url/$userpass@/} | cut -d/ -f1)"
    export DB_HOST="$(echo $hostport | cut -d: -f1)"
    export DB_PORT="$(echo $hostport | cut -d: -f2)"
    export DB_DATABASE="$(echo $url | grep / | cut -d/ -f2 | cut -d? -f1)"
    export DB_USERNAME="$(echo $userpass | cut -d: -f1)"
    export DB_PASSWORD="$(echo $userpass | cut -d: -f2)"
    # DO managed DBs require SSL
    export DB_SSLMODE=require
fi

# Run migrations
php artisan migrate --force

# Cache config with real env vars
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure storage is writable
mkdir -p /var/www/html/storage/app/temp
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start Inertia SSR server in background
node /var/www/html/bootstrap/ssr/ssr.js &

# Start PHP-FPM in background
php-fpm &

# Start nginx in foreground
exec nginx -g "daemon off;"
