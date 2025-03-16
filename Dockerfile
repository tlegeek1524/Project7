# ใช้ PHP image ที่มี Nginx และ PHP-FPM
FROM php:8.2-fpm

# ติดตั้ง dependencies และ build tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libjpeg-dev \
    libicu-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    zlib1g-dev \
    libc-dev \
    pkg-config \
    zip \
    unzip \
    nginx \
    build-essential \
    autoconf \
    gcc \
    make \
    libtool

# Configure และติดตั้ง PHP extensions (แยกคำสั่งเพื่อ debug)
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
# ลบคำสั่ง RUN docker-php-ext-install json ออก

# เพิ่ม memory limit สำหรับ PHP
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini

# ตั้งค่า Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# คัดลอกโค้ด Laravel ไปยัง container
WORKDIR /var/www
COPY . .

# รัน composer install ด้วย retry และเพิ่ม verbosity เพื่อ debug
RUN composer install --optimize-autoloader --no-dev --verbose || { echo "Composer install failed"; exit 1; }

# ตั้งค่า Laravel
RUN php artisan key:generate
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# ตั้งค่า Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# ใช้ JSON format สำหรับ CMD เพื่อแก้ warning
CMD ["sh", "-c", "service nginx start && php-fpm"]

# Expose port 80
EXPOSE 80
