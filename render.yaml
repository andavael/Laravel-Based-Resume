services:
  - type: web
    name: laravel-resume-app
    env: php
    plan: free
    buildCommand: |
      echo "Installing Composer dependencies with memory limit fix..."
      export COMPOSER_MEMORY_LIMIT=-1
      composer clear-cache
      composer install --no-dev --optimize-autoloader
      echo "Generating APP_KEY if missing..."
      php artisan key:generate || true
      echo "Running Laravel migrations and caching configs..."
      php artisan migrate --force || true
      php artisan config:clear
      php artisan config:cache
      php artisan route:cache
      php artisan view:cache
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: DB_CONNECTION
        value: pgsql
      - key: SESSION_DRIVER
        value: database
      - key: CACHE_DRIVER
        value: database
      - key: PHP_VERSION
        value: 8.2

databases:
  - name: laravel-db
    databaseName: laravel_resume
    user: laravel_user
