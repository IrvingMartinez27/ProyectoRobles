FROM serversideup/php:8.2-fpm-apache

USER root

# 1. Instalar Node.js, dependencias y php8.2-gd (Método Ubuntu/ServerSideUp)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs php8.2-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Límites de subida (Ajustados a la ruta correcta de PHP-FPM en esta imagen)
RUN echo "upload_max_filesize = 10M" > /etc/php/8.2/fpm/conf.d/99-uploads.ini \
    && echo "post_max_size = 12M" >> /etc/php/8.2/fpm/conf.d/99-uploads.ini

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instalar dependencias del proyecto
RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && NODE_OPTIONS="--max-old-space-size=512" npm run build

# 3. Crear carpetas y asignar permisos al usuario correcto (webuser:webgroup)
RUN mkdir -p storage/app/livewire-tmp storage/logs \
    && chown -R webuser:webgroup /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 4. Exponer el puerto 8080 que usa esta imagen por defecto
EXPOSE 8080