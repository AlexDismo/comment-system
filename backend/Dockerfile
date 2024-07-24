FROM php:8.2-fpm
WORKDIR /var/www
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    libzip-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN groupadd -g 1001 ubuntu && useradd -u 1001 -g ubuntu -m ubuntu
USER ubuntu
COPY --chown=ubuntu:ubuntu . /var/www
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN php artisan storage:link
RUN php artisan config:cache
RUN php artisan route:cache
RUN chown -R ubuntu:ubuntu /var/www/storage /var/www/bootstrap/cache
EXPOSE 8000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000"]