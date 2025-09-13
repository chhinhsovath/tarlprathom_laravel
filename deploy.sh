#!/bin/bash

# Deployment script for TaRL Laravel application
# Run this on your production server

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /home/dashfiyn/tarl.dashboardkh.com

# Pull latest changes from GitHub
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# Install/update composer dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# Clear all Laravel caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches for production
echo "🔨 Building production caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations if needed
echo "🗄️ Checking for database migrations..."
php artisan migrate --force

# Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment complete!"
echo "🌐 Quick login is now available at: https://tarl.dashboardkh.com/quick-login"