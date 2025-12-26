#!/bin/bash
set -e

echo "Configuring Apache to listen on port ${PORT:-80}..."

# Update Apache to listen on the assigned port
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf

# Ensure Laravel directories are writable
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure .env exists
if [ ! -f /var/www/html/.env ]; then
    echo ".env not found! Copying .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate APP_KEY if missing
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating APP_KEY..."
    php /var/www/html/artisan key:generate
fi

# Install composer dependencies if missing
if [ ! -d /var/www/html/vendor ]; then
    echo "Installing composer dependencies..."
    composer install --optimize-autoloader --no-dev
fi

# Wait for database to be ready (replace host/port as needed)
if [ "$DB_HOST" ]; then
    echo "Waiting for database at $DB_HOST:$DB_PORT..."
    until php -r "new PDO('pgsql:host=$DB_HOST;port=${DB_PORT:-5432}', '$DB_USERNAME', '$DB_PASSWORD');" 2>/dev/null; do
        sleep 1
    done
fi

# Run Laravel setup commands
echo "Running Laravel setup..."
php artisan migrate --force || true
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
