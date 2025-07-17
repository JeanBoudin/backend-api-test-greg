#!/bin/sh
set -e

echo "Starting entrypoint script..."

# Use production bundles configuration
echo "Setting up production bundles configuration..."
cp /var/www/html/config/bundles.prod.php /var/www/html/config/bundles.php

# Remove debug configuration files for production
echo "Removing debug configuration files..."
rm -f /var/www/html/config/packages/debug.yaml
rm -f /var/www/html/config/packages/dev/debug.yaml
rm -f /var/www/html/config/packages/web_profiler.yaml
rm -f /var/www/html/config/packages/dev/web_profiler.yaml
rm -f /var/www/html/config/routes/web_profiler.yaml

# Wait for database to be ready
echo "Waiting for database..."
until nc -z database 5432; do
    echo "Database is not ready - sleeping"
    sleep 2
done

echo "Database is ready!"

# Run migrations
echo "Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

# Clear cache
echo "Clearing cache..."
php bin/console cache:clear --env=prod --no-debug

# Run composer dump-autoload to ensure clean autoload
echo "Rebuilding autoload..."
composer dump-autoload --optimize --no-dev

# Warm up cache
echo "Warming up cache..."
php bin/console cache:warmup --env=prod --no-debug

# Install assets
echo "Installing assets..."
php bin/console assets:install --env=prod --no-debug

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data var/cache var/log public/assets
chmod -R 775 var/cache var/log public/assets

# Create supervisor log directory
echo "Creating supervisor log directory..."
mkdir -p /var/log/supervisor

echo "Entrypoint script completed successfully!"

# Execute the main command
exec "$@"