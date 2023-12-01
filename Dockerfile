# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /F:\server8\htdocs\dockerproject\dockertest


# Copy the composer.json and composer.lock
COPY composer.json composer.lock /F:\server8\htdocs\dockerproject\dockertest/

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install --no-scripts

# Copy the application code
COPY . F:\server8\htdocs\dockerproject\dockertest

# Set permissions
#RUN chown -R www-data:www-data /storage /bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
