# Laravel 10 Deployment Guide

## Changes Made for Laravel 10 Compatibility

I've downgraded your project from Laravel 11 to Laravel 10 to support PHP 8.0+. Here are the changes:

### 1. Updated composer.json
- Changed PHP requirement from `^8.2` to `^8.0`
- Changed Laravel framework from `^11.31` to `^10.0`
- Updated other dependencies to Laravel 10 compatible versions

### 2. Restored Laravel 10 Structure
- Created `app/Http/Kernel.php`
- Created `app/Console/Kernel.php`
- Created `app/Exceptions/Handler.php`
- Created all necessary middleware files
- Created `app/Providers/RouteServiceProvider.php`
- Updated `bootstrap/app.php` to Laravel 10 structure

## Deployment Steps

### 1. Upload Files to Server
Upload all files to your `/tarl/` directory on the server, including the new files created.

### 2. Create .env File
```bash
cd /path/to/tarl
cp .env.example .env
```

Edit the .env file:
```env
APP_NAME="TaRL Project"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://plp.moeys.gov.kh/tarl

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=137.184.109.21
DB_PORT=3306
DB_DATABASE=tarlprathom_laravel
DB_USERNAME=tarl
DB_PASSWORD=P@ssw0rd

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

APP_LOCALE=km
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

### 3. Install Dependencies
```bash
# Remove composer.lock if it exists
rm -f composer.lock

# Clear composer cache
composer clear-cache

# Install dependencies
composer install --no-dev --optimize-autoloader

# If you get errors, try:
composer update --no-dev --optimize-autoloader
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Set Permissions
```bash
# Set ownership
sudo chown -R www-data:www-data .

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Make storage and cache writable
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

### 6. Create Required Directories
```bash
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 777 storage
```

### 7. Clear and Cache Configuration
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Run Migrations
```bash
php artisan migrate --force
```

### 9. Create Storage Link
```bash
php artisan storage:link
```

### 10. Build Assets
```bash
npm install
npm run build
```

## Troubleshooting

### If you still get PHP version errors:
1. Check which PHP version Apache is using:
   ```bash
   <?php phpinfo(); ?>
   ```

2. Make sure you're using the right PHP binary:
   ```bash
   which php
   php -v
   ```

### If you get 500 errors:
1. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. Check Apache error logs:
   ```bash
   sudo tail -f /var/log/apache2/error.log
   ```

### Common Issues:
1. **White screen**: Usually permissions issue or missing .env file
2. **Database errors**: Check database credentials in .env
3. **Asset not loading**: Run `npm run build` and check storage link

## Server Requirements for Laravel 10

- PHP >= 8.0 (compatible with 8.0, 8.1, 8.2, 8.3)
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- PDO MySQL Extension
- Tokenizer PHP Extension
- XML PHP Extension