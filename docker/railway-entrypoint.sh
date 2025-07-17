#!/bin/sh
set -e

echo "Starting Railway deployment..."

# Set default PORT if not provided
if [ -z "$PORT" ]; then
    export PORT=8080
fi

echo "Using PORT: $PORT"

# Replace PORT in nginx config
sed -i "s/8080/$PORT/g" /etc/nginx/nginx.conf

# Wait for database to be ready (Railway PostgreSQL)
if [ -n "$DATABASE_URL" ]; then
    echo "Waiting for database..."
    until pg_isready -d "$DATABASE_URL"; do
        echo "Database is not ready - sleeping"
        sleep 2
    done
    echo "Database is ready!"
    
    echo "Running database migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction || echo "Migration failed, continuing..."
fi

# Set environment based on APP_ENV
ENV_MODE="${APP_ENV:-prod}"
echo "Running in environment: $ENV_MODE"

# Clear cache
echo "Clearing cache..."
php bin/console cache:clear --env="$ENV_MODE" --no-debug || echo "Cache clear failed, continuing..."

# Rebuild autoloader for production
echo "Rebuilding autoloader..."
composer dump-autoload --optimize --no-dev || echo "Autoloader rebuild failed, continuing..."

# Warm up cache
echo "Warming up cache..."
php bin/console cache:warmup --env="$ENV_MODE" --no-debug || echo "Cache warmup failed, continuing..."

# Install assets
echo "Installing assets..."
php bin/console assets:install --env="$ENV_MODE" --no-debug || echo "Assets install failed, continuing..."

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data var/cache var/log public/assets || echo "Permission setting failed, continuing..."
chmod -R 775 var/cache var/log public/assets || echo "Permission setting failed, continuing..."

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start nginx in foreground
echo "Starting nginx on port $PORT..."
nginx -g "daemon off;"