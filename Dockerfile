FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev \
    libxml2-dev libzip-dev nginx supervisor \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && apt-get clean

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar archivos
COPY . .

# Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Instalar dependencias JS y compilar
RUN npm install && NODE_OPTIONS="--max-old-space-size=512" npm run build

# Crear carpetas necesarias y permisos
RUN mkdir -p storage/app/livewire-tmp storage/logs \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Configurar Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Configurar Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]