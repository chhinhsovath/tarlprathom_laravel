#!/bin/bash

# Production Deployment Script for TaRL Laravel Application
# This script should be run on the production server after pulling the latest code

echo "Starting production deployment..."

# Create storage directories if they don't exist
echo "Creating storage directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/testing
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Clear all caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create session table if using database sessions
echo "Ensuring session table exists..."
php artisan session:table
php artisan migrate --force

echo "Deployment completed successfully!"
echo ""
echo "IMPORTANT: Make sure to:"
echo "1. Update .env file with production database credentials"
echo "2. Set APP_ENV=production"
echo "3. Set APP_DEBUG=false"
echo "4. Configure your web server to point to the /public directory"
echo "5. Ensure PHP extensions are installed: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json"