FROM php:8.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libcurl4-openssl-dev \
    libonig-dev \
    libsqlite3-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install curl mbstring pdo_sqlite xml zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && printf '\n<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader \
    && rm -rf public/storage \
    && ln -s ../storage/app/public public/storage \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80

CMD ["apache2-foreground"]