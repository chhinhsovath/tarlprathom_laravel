#\!/bin/bash

echo "🚀 Laravel TaRL - Production Deployment"
echo "======================================="
echo ""

cd /var/www/html/tarl

# 1. Install production dependencies
echo "1️⃣ Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Copy production environment
echo "2️⃣ Setting up production environment..."
if [ \! -f .env ]; then
    cp .env.production .env
    echo "✅ Environment file created from .env.production"
else
    echo "⚠️  .env file already exists - please verify settings"
fi

# 3. Generate application key if needed
echo "3️⃣ Checking application key..."
if \! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
    echo "✅ Application key generated"
else
    echo "✅ Application key already exists"
fi

# 4. Run database migrations
echo "4️⃣ Running database migrations..."
php artisan migrate --force

# 5. Clear and cache configuration
echo "5️⃣ Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# 6. Set final permissions
echo "6️⃣ Setting final permissions..."
sudo chown -R www-data:www-data /var/www/html/tarl
sudo chmod -R 755 /var/www/html/tarl
sudo chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Production deployment complete\!"
echo ""
echo "🌐 Your application should now be accessible at:"
echo "   https://plp.moeys.gov.kh/tarl/"
echo ""
echo "📋 Admin Login:"
echo "   Email: admin@tarlconnect.com"
echo "   Password: password"
