FROM webdevops/php-nginx:8.2

ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    zip unzip nodejs npm \
    && echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction

RUN npm install && NODE_OPTIONS="--max-old-space-size=512" npm run build

RUN mkdir -p storage/app/livewire-tmp storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

COPY docker/nginx.conf /opt/docker/etc/nginx/vhost.conf

EXPOSE 80