# Fix for Mixed Content Errors on Production

## Issue
The production site is served over HTTPS but trying to load resources over HTTP, causing Mixed Content errors.

## Solution

### 1. Update .env file on production server
```bash
# Change this:
APP_URL=http://plp.moeys.gov.kh/tarl/public

# To this:
APP_URL=https://plp.moeys.gov.kh/tarl/public
```

### 2. Force HTTPS in Production
Add this to your `app/Providers/AppServiceProvider.php`:

```php
public function boot()
{
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
}
```

### 3. Update config/app.php
Make sure the URL uses HTTPS:
```php
'url' => env('APP_URL', 'https://plp.moeys.gov.kh/tarl/public'),
```

### 4. Clear all caches on production
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 5. Update your .htaccess in public folder
Add these lines to force HTTPS:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Force HTTPS
    RewriteCond %{HTTPS} !=on
    RewriteCond %{HTTP:X-Forwarded-Proto} !https
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Existing Laravel rules...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 6. If using a proxy or load balancer
Add this to `app/Http/Middleware/TrustProxies.php`:
```php
protected $proxies = '*';
protected $headers = Request::HEADER_X_FORWARDED_ALL;
```

## Quick Fix (Immediate)
On your production server, run:
```bash
# Update .env
sed -i 's|APP_URL=http://|APP_URL=https://|g' .env

# Clear config cache
php artisan config:cache

# Restart PHP-FPM (if using)
sudo service php8.1-fpm restart
```

## Verification
After applying these changes, all resources should load over HTTPS and the Mixed Content errors should disappear.