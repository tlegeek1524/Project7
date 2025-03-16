# ใช้ PHP image ที่มี Nginx และ PHP-FPM
FROM php:8.0-fpm

# ติดตั้ง dependencies และ build tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    build-essential

# ติดตั้ง PHP extensions
RUN docker-php-ext-install \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip

# ตั้งค่า Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# คัดลอกโค้ด Laravel ไปยัง container
WORKDIR /var/www
COPY . .
RUN composer install --optimize-autoloader --no-dev
RUN php artisan key:generate
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# ตั้งค่า Nginx
COPY nginx.conf /etc/nginx/sites-available/default
CMD service nginx start && php-fpm

# Expose port 80
EXPOSE 80
