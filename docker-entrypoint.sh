#!/bin/bash
set -e

echo "Configuring Apache to listen on port $PORT..."

# Update Apache to listen on Render's assigned port
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# Wait for database
echo "Waiting for database..."
sleep 5

# Run Laravel setup
php artisan migrate --force || true
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
