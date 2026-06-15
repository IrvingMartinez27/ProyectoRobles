FROM webdevops/php-nginx:8.2

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    zip unzip nodejs npm \
    && docker-php-ext-install pdo_mysql bcmath gd zip exif pcntl \
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

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]