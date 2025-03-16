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
    supervisor \
    sqlite3 \
    build-essential \
    autoconf \
    gcc \
    make \
    libtool

# Configure และติดตั้ง PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_sqlite  # เปลี่ยนเป็น pdo_sqlite สำหรับ SQLite

# เพิ่ม memory limit และ display errors สำหรับ debug
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "display_errors=On" > /usr/local/etc/php/conf.d/errors.ini

# ตั้งค่า Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# คัดลอกโค้ด Laravel ไปยัง container
WORKDIR /var/www
COPY . .

# รัน composer install
RUN composer install --optimize-autoloader --no-dev --verbose || { echo "Composer install failed"; exit 1; }

# สร้าง directory และไฟล์ database.sqlite
RUN mkdir -p /var/www/database
RUN touch /var/www/database/database.sqlite
RUN chown www-data:www-data /var/www/database/database.sqlite
RUN chmod 664 /var/www/database/database.sqlite

# ตั้งค่า .env และ Laravel
RUN touch .env
RUN echo "APP_KEY=$(php artisan key:generate --show)" >> .env
RUN echo "APP_DEBUG=true" >> .env
RUN echo "APP_ENV=production" >> .env
RUN echo "APP_URL=https://your-render-url.onrender.com" >> .env
RUN echo "DB_CONNECTION=sqlite" >> .env
RUN echo "DB_DATABASE=/var/www/database/database.sqlite" >> .env
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage
RUN chmod -R 775 /var/www/bootstrap/cache

# คัดลอกและตั้งค่า Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# ตรวจสอบ config และรัน
RUN nginx -t
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Expose port 80
EXPOSE 80
