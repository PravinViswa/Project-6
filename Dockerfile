FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libpng-dev libonig-dev libxml2-dev git curl \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project files to the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install vendor packages
RUN composer install

EXPOSE 80
