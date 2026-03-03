FROM php:8.2-apache

# Install system deps + FFmpeg
RUN apt-get update && apt-get install -y --no-install-recommends \
    ffmpeg \
    libsqlite3-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_sqlite zip \
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

# Apache config
RUN a2enmod rewrite headers
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN echo '<Directory /var/www/html/public>\n    AllowOverride All\n</Directory>' > /etc/apache2/conf-available/allowoverride.conf \
    && a2enconf allowoverride

# Use port 8080 for DO App Platform
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

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
