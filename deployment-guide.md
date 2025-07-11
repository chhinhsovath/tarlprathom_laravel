# Deployment Guide for TaRL Connect on Apache Server

## Fixing "Forbidden" Error on plp.moeys.gov.kh/tarl/

When you get a "Forbidden - You don't have permission to access this resource" error, follow these steps:

### 1. Check Directory Permissions

```bash
# Navigate to your project directory
cd /path/to/your/tarl/directory

# Set proper permissions for Laravel
sudo chmod -R 755 .
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
```

### 2. Update Apache Configuration

You need to configure Apache to point to the `public` directory, not the root Laravel directory.

#### Option A: Virtual Host Configuration (Recommended)

Edit your Apache virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName plp.moeys.gov.kh
    DocumentRoot /path/to/your/tarl/public
    
    <Directory /path/to/your/tarl/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/tarl-error.log
    CustomLog ${APACHE_LOG_DIR}/tarl-access.log combined
</VirtualHost>
```

#### Option B: If Using Subdirectory (/tarl/)

If you're deploying in a subdirectory, create an alias in your Apache configuration:

```apache
Alias /tarl /path/to/your/tarl/public

<Directory /path/to/your/tarl/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### 3. Enable Required Apache Modules

```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

### 4. Update .env File for Production

```env
APP_NAME="TaRL Project"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://plp.moeys.gov.kh/tarl

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=137.184.109.21
DB_PORT=3306
DB_DATABASE=tarlprathom_laravel
DB_USERNAME=tarl
DB_PASSWORD=P@ssw0rd

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 5. Run Laravel Production Commands

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key if not already set
php artisan key:generate

# Clear and cache configurations
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link
```

### 6. If Still Getting Forbidden Error

Create an index.php file in the /tarl/ directory that redirects to the public folder:

```php
<?php
header('Location: public/');
exit();
```

### 7. Alternative: Using .htaccess in Root Directory

If you can't modify Apache configuration, create a .htaccess file in your /tarl/ directory:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 8. Check SELinux (if enabled)

If SELinux is enabled on your server:

```bash
# Check if SELinux is enabled
getenforce

# If enabled, set proper context
sudo chcon -R -t httpd_sys_content_t /path/to/your/tarl/
sudo chcon -R -t httpd_sys_rw_content_t /path/to/your/tarl/storage
sudo chcon -R -t httpd_sys_rw_content_t /path/to/your/tarl/bootstrap/cache
```

### Common Issues and Solutions

1. **White Page / 500 Error**
   - Check Laravel logs: `tail -f storage/logs/laravel.log`
   - Check Apache error logs: `tail -f /var/log/apache2/error.log`

2. **Assets Not Loading**
   - Run: `npm install && npm run build`
   - Ensure storage link exists: `php artisan storage:link`

3. **Database Connection Error**
   - Verify database credentials in .env
   - Ensure MySQL server allows remote connections

4. **Session/Cache Errors**
   - Clear all caches: `php artisan cache:clear && php artisan config:clear`
   - Check permissions on storage/framework/sessions

Remember to restart Apache after making configuration changes:
```bash
sudo systemctl restart apache2
```