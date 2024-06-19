# Use an official PHP runtime as a parent image
FROM php:8.1-fpm-alpine

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install additional dependencies and extensions
RUN apk add --no-cache \
    zip \
    libpng \
    libpng-dev \
    libzip \
    libzip-dev \
    mysql-client \
    && docker-php-ext-install zip gd pdo pdo_mysql

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 to communicate with Nginx
EXPOSE 9000

# Start php-fpm
CMD ["php-fpm", "-F"]

# Install PostgreSQL extension
RUN apk add libpq-dev \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql
