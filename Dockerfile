FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    git curl pkg-config libssl-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Install PECL extensions
RUN pecl install mongodb && docker-php-ext-enable mongodb \
    && pecl install redis && docker-php-ext-enable redis

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy your project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install vendor packages
RUN composer install

EXPOSE 80
