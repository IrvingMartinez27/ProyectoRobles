FROM serversideup/php:8.2-fpm-apache

USER root

# 1. Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalar extensión GD (El método oficial de ServerSideUp)
RUN install-php-extensions gd

# 3. Límites de subida (¡Ruta corregida y a prueba de fallos!)
RUN mkdir -p /usr/local/etc/php/conf.d/ \
    && echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/99-uploads.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/99-uploads.ini

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instalar dependencias del proyecto
RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && NODE_OPTIONS="--max-old-space-size=512" npm run build

# 4. Crear carpetas y asignar permisos al usuario correcto
RUN mkdir -p storage/app/livewire-tmp storage/logs \
    && chown -R webuser:webgroup /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 5. Exponer el puerto 8080 que usa esta imagen por defecto
EXPOSE 8080