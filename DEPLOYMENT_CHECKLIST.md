# TaRL Project Deployment Checklist

## To Fix the 500 Internal Server Error

### 1. First, access your server and check the error logs:

```bash
# Check Apache error log
sudo tail -f /var/log/apache2/error.log

# Or if in a different location
sudo tail -f /var/log/httpd/error_log
```

### 2. Upload and run the server check:

1. Upload the `public/server-check.php` file to your server
2. Access it at: https://plp.moeys.gov.kh/tarl/public/server-check.php
3. This will show you exactly what's missing

### 3. Common causes of 500 error and fixes:

#### A. Missing .env file
```bash
cd /path/to/tarl
cp .env.example .env
php artisan key:generate
```

Then edit .env with your database credentials:
```
APP_NAME="TaRL Project"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://plp.moeys.gov.kh/tarl

DB_CONNECTION=mysql
DB_HOST=137.184.109.21
DB_PORT=3306
DB_DATABASE=tarlprathom_laravel
DB_USERNAME=tarl
DB_PASSWORD=P@ssw0rd
```

#### B. Missing vendor directory (Composer dependencies)
```bash
cd /path/to/tarl
composer install --no-dev --optimize-autoloader
```

#### C. Incorrect file permissions
```bash
cd /path/to/tarl
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
```

#### D. Missing storage directories
```bash
cd /path/to/tarl
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 777 storage
```

#### E. Missing compiled assets
```bash
npm install
npm run build
```

### 4. After fixing the issues:

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

# Run migrations
php artisan migrate --force
```

### 5. If still getting errors, create a test file:

Create a file `public/test.php`:
```php
<?php
phpinfo();
```

Access it at: https://plp.moeys.gov.kh/tarl/public/test.php

This will show if PHP is working correctly.

### 6. Alternative deployment method:

If the .htaccess redirect isn't working, you can:

1. Point Apache DocumentRoot directly to the public folder:
```apache
DocumentRoot /path/to/tarl/public
```

2. Or create a symbolic link:
```bash
cd /var/www/html  # or wherever your web root is
ln -s /path/to/tarl/public tarl
```

### 7. Required PHP extensions for Laravel 11:

- PHP >= 8.2
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- PDO MySQL Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

Install missing extensions:
```bash
sudo apt-get update
sudo apt-get install php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl
```

### 8. Enable Apache mod_rewrite:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### IMPORTANT: Delete these files after deployment:
- public/server-check.php
- public/test.php

These files should not be accessible in production!