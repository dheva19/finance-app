FROM node:24-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.4-alpine
RUN apk add --no-cache curl zip unzip libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo_mysql
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
COPY --from=frontend /app/public/build ./public/build
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
&& chmod -R 775 /var/www/html/storage \
&& chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
