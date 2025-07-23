# Production File Upload Fix Guide

## Common Issues and Solutions

### 1. Storage Directory Permissions
In production, ensure proper permissions for storage directories:

```bash
# Set correct permissions for storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set correct ownership (replace www-data with your web server user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 2. Create Storage Symlink
Make sure the storage symlink exists in production:

```bash
php artisan storage:link
```

### 3. Check PHP Configuration
Verify these PHP settings in `php.ini`:

```ini
; Maximum allowed file size
upload_max_filesize = 10M

; Maximum POST data size (should be >= upload_max_filesize)
post_max_size = 10M

; Maximum execution time
max_execution_time = 300

; Maximum input time
max_input_time = 300

; Memory limit
memory_limit = 256M
```

### 4. Nginx Configuration (if using Nginx)
Add to your Nginx server block:

```nginx
client_max_body_size 10M;
```

### 5. Apache Configuration (if using Apache)
Add to your `.htaccess` or Apache config:

```apache
LimitRequestBody 10485760
```

### 6. Environment Variables
Ensure these are set correctly in production `.env`:

```env
FILESYSTEM_DISK=public
APP_URL=https://yourdomain.com
```

### 7. SELinux (if enabled)
If SELinux is enabled, allow httpd to write:

```bash
# Check SELinux status
getenforce

# If enabled, set context for storage
chcon -R -t httpd_sys_rw_content_t storage
chcon -R -t httpd_sys_rw_content_t bootstrap/cache
```

### 8. Verify Upload Directory Structure
Ensure these directories exist with proper permissions:

```bash
mkdir -p storage/app/public/students/photos
mkdir -p storage/app/public/users
chmod -R 775 storage/app/public
```

### 9. Debug Upload Issues
Add temporary debugging to your controller:

```php
// In StudentController@store or similar
if ($request->hasFile('photo')) {
    $photo = $request->file('photo');
    
    // Debug info
    \Log::info('Upload attempt', [
        'valid' => $photo->isValid(),
        'error' => $photo->getError(),
        'error_message' => $photo->getErrorMessage(),
        'size' => $photo->getSize(),
        'mime' => $photo->getMimeType(),
    ]);
}
```

### 10. Common Error Messages and Solutions

- **"The uploaded file exceeds the upload_max_filesize"**: Increase `upload_max_filesize` in php.ini
- **"413 Request Entity Too Large"**: Increase `client_max_body_size` in Nginx
- **"Unable to write to disk"**: Check storage permissions and disk space
- **"Failed to open stream: Permission denied"**: Fix directory permissions

## Quick Diagnostic Commands

```bash
# Check disk space
df -h

# Check storage permissions
ls -la storage/app/public/

# Check PHP upload settings
php -i | grep -E "upload_max_filesize|post_max_size"

# Check web server user
ps aux | grep -E "nginx|apache|httpd"

# Test write permissions
sudo -u www-data touch storage/app/public/test.txt
```

## Recommended Production Setup Script

```bash
#!/bin/bash

# Run as root or with sudo

# Set permissions
find storage -type d -exec chmod 755 {} \;
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type d -exec chmod 755 {} \;

# Set ownership (adjust www-data to your web server user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# Create storage link
php artisan storage:link

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "Storage setup completed!"
```