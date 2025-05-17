FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

RUN apt-get update && apt-get install -y nodejs npm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
COPY . .
RUN composer install
RUN npm install && npm run build

COPY .env.example .env
RUN php artisan key:generate

EXPOSE 9000
CMD ["php-fpm"]
