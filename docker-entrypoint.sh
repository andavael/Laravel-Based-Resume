#!/bin/bash
set -e

PORT=${PORT:-10000}
echo "Configuring Apache to listen on port $PORT..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf

# Set permissions
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure .env exists
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# ✅ Install Composer dependencies BEFORE running any artisan commands
if [ ! -d /var/www/html/vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --optimize-autoloader --no-dev
fi

# Generate APP_KEY if missing
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    php /var/www/html/artisan key:generate
fi

# Run Laravel commands
php /var/www/html/artisan migrate --force || true
php /var/www/html/artisan config:clear
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# Start Apache
exec apache2-foreground
