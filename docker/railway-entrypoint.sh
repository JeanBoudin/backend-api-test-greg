#!/bin/sh
set -e

echo "Starting Railway deployment..."

# Set default PORT if not provided
if [ -z "$PORT" ]; then
    export PORT=8080
fi

echo "Using PORT: $PORT"

# Replace PORT in nginx config
sed -i "s/\$PORT/$PORT/g" /etc/nginx/conf.d/default.conf

# Run migrations if DATABASE_URL is set
if [ -n "$DATABASE_URL" ]; then
    echo "Running database migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction || echo "Migration failed, continuing..."
fi

# Clear cache
echo "Clearing cache..."
php bin/console cache:clear --env=prod --no-debug || echo "Cache clear failed, continuing..."

# Warm up cache
echo "Warming up cache..."
php bin/console cache:warmup --env=prod --no-debug || echo "Cache warmup failed, continuing..."

# Install assets
echo "Installing assets..."
php bin/console assets:install --env=prod --no-debug || echo "Assets install failed, continuing..."

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start nginx in foreground
echo "Starting nginx on port $PORT..."
nginx -g "daemon off;"