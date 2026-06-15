FROM serversideup/php:8.2-fpm-apache

USER root

# Instalar Node.js
RUN apk add --no-cache nodejs npm

# Límites de subida
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/uploads.ini

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction

RUN npm install && NODE_OPTIONS="--max-old-space-size=512" npm run build

RUN mkdir -p storage/app/livewire-tmp storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 80