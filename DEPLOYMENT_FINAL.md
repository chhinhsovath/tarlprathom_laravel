# Final Deployment Instructions for Laravel 10

## Your project has been successfully downgraded to Laravel 10

### What Changed:
1. **PHP Requirement**: Now supports PHP 8.0+ (instead of PHP 8.2+)
2. **Laravel Version**: Downgraded from 11 to 10
3. **Structure**: Restored Laravel 10 file structure with Kernel files and middleware

### Quick Deployment Steps on Your Server:

```bash
# 1. Navigate to your project directory
cd /path/to/tarl

# 2. Create .env file
cp .env.example .env
nano .env  # Edit with your database credentials

# 3. Install composer dependencies
composer install --no-dev --optimize-autoloader

# 4. Generate application key
php artisan key:generate

# 5. Set permissions
sudo chown -R www-data:www-data .
chmod -R 777 storage bootstrap/cache

# 6. Run migrations
php artisan migrate --force

# 7. Create storage link
php artisan storage:link

# 8. Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Install and build frontend assets
npm install
npm run build
```

### Verify PHP Version on Server:

Create a test file `public/test.php`:
```php
<?php
echo "PHP Version: " . phpversion();
```

Access: https://plp.moeys.gov.kh/tarl/public/test.php

If PHP version is 8.0 or higher, the application will work!

### If You Still Get Errors:

1. **Check error logs**:
   ```bash
   tail -f storage/logs/laravel.log
   tail -f /var/log/apache2/error.log
   ```

2. **Ensure .htaccess is working**:
   - The .htaccess file in root directory redirects to public/
   - Apache must have `AllowOverride All` enabled

3. **Common fixes**:
   ```bash
   # Clear everything
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   
   # Recreate cache
   php artisan config:cache
   php artisan route:cache
   ```

### Database Info (from your .env):
```
DB_HOST=137.184.109.21
DB_DATABASE=tarlprathom_laravel
DB_USERNAME=tarl
DB_PASSWORD=P@ssw0rd
```

### After Successful Deployment:
1. Delete `public/test.php`
2. Delete `public/phpversion.php`
3. Delete `public/server-check.php`
4. Set `APP_DEBUG=false` in .env

The application is now compatible with PHP 8.0+ and should work on your server!