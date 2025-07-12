#!/bin/bash

# Deploy script for PHP 8.1 production server
# This script prepares the application for deployment on PHP 8.1.2

echo "Preparing TaRL Project for PHP 8.1 deployment..."

# Remove vendor directory and composer.lock to ensure clean install
echo "1. Cleaning existing dependencies..."
rm -rf vendor
rm -f composer.lock

# Install dependencies with PHP 8.1 compatibility
echo "2. Installing dependencies compatible with PHP 8.1..."
composer install --no-dev --optimize-autoloader

# Clear caches
echo "3. Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate optimized autoload files
echo "4. Optimizing autoloader..."
composer dump-autoload --optimize

# Run migrations (optional - comment out if not needed)
echo "5. Running database migrations..."
php artisan migrate --force

# Cache configuration for production
echo "6. Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment preparation complete!"
echo "The application is now ready for PHP 8.1.2 production server."