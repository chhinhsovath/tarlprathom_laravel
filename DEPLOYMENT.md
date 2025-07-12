# TaRL Laravel Project - Production Deployment Guide

## Server Requirements Verification ✅

Based on https://plp.moeys.gov.kh/phpinfo.php analysis:

### PHP Version
- **Required**: PHP 8.0+
- **Server Has**: PHP 8.1.2 ✅

### Required PHP Extensions
All required extensions are available on the server:
- ✅ BCMath
- ✅ Ctype
- ✅ cURL
- ✅ DOM
- ✅ Fileinfo
- ✅ JSON
- ✅ Mbstring
- ✅ OpenSSL
- ✅ PCRE
- ✅ PDO
- ✅ PDO MySQL
- ✅ Tokenizer
- ✅ XML
- ✅ GD (for image processing)
- ✅ ZIP (for Excel exports)

### Server Configuration
- **Memory Limit**: 128M (sufficient)
- **Max Execution Time**: 300s (good for long operations)
- **Upload Max Filesize**: 20M (configured in our app for 5M limits)
- **Post Max Size**: 25M (sufficient)

## Pre-Deployment Checklist

### 1. Local Preparation
```bash
# Run tests
php artisan test

# Check code style
./vendor/bin/pint --test

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Install production dependencies only
composer install --no-dev --optimize-autoloader

# Compile assets
npm run build
```

### 2. Files to Upload
- All project files EXCEPT:
  - `.git/` directory
  - `node_modules/` directory
  - `.env` file (use `.env.production` instead)
  - `storage/app/public/*` (user uploads)
  - `storage/logs/*` (log files)
  - `tests/` directory
  - Development files (`.editorconfig`, `.gitignore`, etc.)

### 3. Server Setup Steps

#### Step 1: Upload Files
```bash
# Upload to server (example using rsync)
rsync -avz --exclude-from='.deployignore' ./ user@plp.moeys.gov.kh:/var/www/tarl/
```

#### Step 2: Set Permissions
```bash
# Set directory permissions
find /var/www/tarl -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/tarl -type f -exec chmod 644 {} \;

# Make storage and cache writable
chmod -R 775 /var/www/tarl/storage
chmod -R 775 /var/www/tarl/bootstrap/cache

# Set ownership (adjust user/group as needed)
chown -R www-data:www-data /var/www/tarl
```

#### Step 3: Environment Configuration
```bash
# Copy production environment file
cp .env.production .env

# Generate application key
php artisan key:generate

# Update .env with actual database credentials
nano .env
```

#### Step 4: Database Setup
```bash
# Run migrations
php artisan migrate --force

# Seed initial data (if needed)
php artisan db:seed --force
```

#### Step 5: Optimization
```bash
# Create symbolic link for storage
php artisan storage:link

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

#### Step 6: Web Server Configuration

**For Apache (.htaccess is included)**
Ensure mod_rewrite is enabled.

**For Nginx:**
```nginx
server {
    listen 80;
    server_name plp.moeys.gov.kh;
    root /var/www/tarl/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Post-Deployment Verification

### 1. Check Requirements
Navigate to: https://plp.moeys.gov.kh/tarl/check-requirements.php
(Remember to delete this file after checking!)

### 2. Test Core Functions
- [ ] Login page loads
- [ ] Can login with admin credentials
- [ ] Dashboard displays correctly
- [ ] Language switching works (Khmer/English)
- [ ] File uploads work (student photos, etc.)
- [ ] Assessment data entry works
- [ ] Reports generate correctly
- [ ] Excel exports work

### 3. Security Checklist
- [ ] Debug mode is OFF (`APP_DEBUG=false`)
- [ ] Remove `check-requirements.php`
- [ ] Remove any test files
- [ ] Ensure `.env` file permissions are 644
- [ ] SSL certificate is configured (HTTPS)

## Maintenance Commands

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Backup Database
```bash
mysqldump -u tarl_user -p tarl_production > backup_$(date +%Y%m%d).sql
```

## Troubleshooting

### 500 Error
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check PHP error logs: `/var/log/php8.1-fpm.log`
3. Verify file permissions
4. Check `.env` configuration

### Database Connection Error
1. Verify database credentials in `.env`
2. Check if MySQL is running
3. Verify database exists and user has permissions

### File Upload Issues
1. Check `upload_max_filesize` in PHP configuration
2. Verify storage directory permissions
3. Ensure storage symlink exists: `php artisan storage:link`

## Important Notes

1. **Database**: Update the production `.env` with actual database credentials
2. **APP_KEY**: Generate a new one on production using `php artisan key:generate`
3. **Timezone**: Set to `Asia/Phnom_Penh` for Cambodia
4. **Language**: Default is Khmer (`km`) with English fallback
5. **File Storage**: All user uploads go to `storage/app/public/`

## Support Contacts

For deployment issues:
- Laravel Documentation: https://laravel.com/docs/10.x/deployment
- Server Admin: [Contact your system administrator]
- Application Support: [Your contact information]