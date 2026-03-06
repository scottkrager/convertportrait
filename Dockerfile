FROM php:8.2-fpm

# Install system deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    ffmpeg \
    libsqlite3-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_sqlite pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP config for large uploads
RUN echo "upload_max_filesize = 500M\npost_max_size = 500M\nmax_execution_time = 600\nmemory_limit = 512M" \
    > /usr/local/etc/php/conf.d/uploads.ini

# Nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (cache layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package files (cache layer)
COPY package.json package-lock.json ./
RUN npm ci

# Copy rest of app
COPY . .

# Build frontend
RUN npm run build

# Make entrypoint executable
RUN chmod +x docker-entrypoint.sh

# Storage dirs
RUN mkdir -p storage/app/temp storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 8080

ENTRYPOINT ["./docker-entrypoint.sh"]
