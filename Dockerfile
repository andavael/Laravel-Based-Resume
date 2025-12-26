# Use official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# --- Copy composer files first for caching ---
COPY composer.json composer.lock ./

# Create a directory for cached vendor
RUN mkdir -p /var/www/html/vendor-cache

# --- Install dependencies with vendor cache ---
RUN composer clear-cache && \
    php -d memory_limit=-1 /usr/bin/composer install \
        --optimize-autoloader --no-dev --prefer-dist --no-scripts \
        --vendor-dir=/var/www/html/vendor-cache

# --- Copy application files ---
COPY . /var/www/html

# Symlink vendor-cache to actual vendor directory
RUN rm -rf /var/www/html/vendor && \
    ln -s /var/www/html/vendor-cache /var/www/html/vendor

# Run Laravel package discovery now that artisan exists
RUN php artisan package:discover --ansi

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache DocumentRoot to Laravel's public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Update Apache configuration to allow .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Copy entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 10000
EXPOSE 10000

# Use entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
