# PHP Upgrade Guide for Ubuntu Server

## Current Issue
Your server has PHP < 8.2, but Laravel 11 requires PHP >= 8.2.0

## Solution 1: Upgrade PHP to 8.2

### Step 1: Add PHP Repository
```bash
sudo apt update
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
```

### Step 2: Install PHP 8.2
```bash
sudo apt install php8.2
```

### Step 3: Install Required PHP 8.2 Extensions
```bash
sudo apt install php8.2-cli php8.2-common php8.2-mysql php8.2-xml php8.2-xmlrpc php8.2-curl php8.2-gd php8.2-imagick php8.2-dev php8.2-imap php8.2-mbstring php8.2-opcache php8.2-redis php8.2-soap php8.2-zip php8.2-intl php8.2-bcmath
```

### Step 4: Install PHP 8.2 for Apache
```bash
sudo apt install libapache2-mod-php8.2
```

### Step 5: Disable Old PHP Module and Enable PHP 8.2
```bash
# First, check your current PHP version
php -v

# Disable the old PHP module (replace 7.4 with your version)
sudo a2dismod php7.4
# or
sudo a2dismod php8.0
# or
sudo a2dismod php8.1

# Enable PHP 8.2
sudo a2enmod php8.2

# Restart Apache
sudo systemctl restart apache2
```

### Step 6: Set PHP 8.2 as Default
```bash
sudo update-alternatives --config php
# Select PHP 8.2 from the list
```

### Step 7: Verify PHP Version
```bash
php -v
# Should show PHP 8.2.x
```

## Solution 2: Use Docker (Alternative)

If you can't upgrade PHP on the server, you can use Docker:

```dockerfile
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY . /var/www/html
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage
```

## Solution 3: Downgrade to Laravel 10 (Not Recommended)

If you absolutely cannot upgrade PHP, you would need to downgrade to Laravel 10 which supports PHP 8.1:

```bash
composer require laravel/framework:^10.0
```

But this would require significant code changes and is not recommended.

## After PHP Upgrade

Once PHP 8.2 is installed:

```bash
cd /path/to/your/tarl

# Clear composer cache
composer clear-cache

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate key
php artisan key:generate

# Set permissions
sudo chown -R www-data:www-data .
sudo chmod -R 777 storage bootstrap/cache

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link
```

## Quick Check

Create a file `public/phpversion.php`:
```php
<?php
echo "PHP Version: " . phpversion();
```

Access it at: https://plp.moeys.gov.kh/tarl/public/phpversion.php

This will confirm which PHP version Apache is using.

## If Using Multiple PHP Versions

You can run Laravel with PHP 8.2 while keeping other apps on older versions:

```bash
# Use PHP 8.2 specifically for artisan commands
/usr/bin/php8.2 artisan serve

# Use PHP 8.2 for composer
/usr/bin/php8.2 /usr/local/bin/composer install
```

## Important Notes

1. **Backup First**: Always backup your server before major upgrades
2. **Test**: Test the upgrade on a staging server first if possible
3. **Other Apps**: Check if other applications on the server are compatible with PHP 8.2
4. **Restart Services**: Always restart Apache after PHP changes
   ```bash
   sudo systemctl restart apache2
   ```