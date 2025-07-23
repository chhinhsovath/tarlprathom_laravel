# Fix for Images Not Displaying in Production

The issue is that your production site is hosted at `https://plp.moeys.gov.kh/tarl/public` (in a subdirectory), but the storage URLs are being generated incorrectly.

## Solution 1: Update .env Configuration (Recommended)

On your production server, update the `.env` file:

```bash
APP_URL=https://plp.moeys.gov.kh/tarl/public
```

This ensures that `Storage::url()` generates the correct URLs including the subdirectory.

## Solution 2: Create a Custom Storage Link

If Solution 1 doesn't work, create a custom symlink that accounts for the subdirectory:

```bash
cd /var/www/html/tarl
rm -rf public/storage
ln -s /var/www/html/tarl/storage/app/public /var/www/html/tarl/public/storage
```

## Solution 3: Use Apache/Nginx Alias

### For Apache (.htaccess in public folder):
```apache
# Add this to your public/.htaccess file
RewriteRule ^storage/(.*)$ /tarl/public/storage/$1 [L]
```

### For Nginx:
```nginx
location /tarl/public/storage {
    alias /var/www/html/tarl/storage/app/public;
}
```

## Solution 4: Create a Helper Function (Already Added)

I've created a `StorageHelper` class, but the simplest fix is to ensure your `.env` file has the correct `APP_URL`.

## Verification Steps

1. Check current APP_URL:
   ```bash
   cd /var/www/html/tarl
   grep APP_URL .env
   ```

2. Update it to include the full path:
   ```bash
   # Edit .env and set:
   APP_URL=https://plp.moeys.gov.kh/tarl/public
   ```

3. Clear caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. Test by uploading a new image and checking if it displays correctly.

## Debug Image URLs

To debug, check what URL is being generated:

1. SSH into your server
2. Run: `php artisan tinker`
3. Test URL generation:
   ```php
   \Storage::url('students/photos/test.jpg')
   ```

This should return: `/storage/students/photos/test.jpg`

The actual URL should be: `https://plp.moeys.gov.kh/tarl/public/storage/students/photos/test.jpg`

## Important Notes

- The `APP_URL` in your `.env` file must match your actual production URL including any subdirectories
- After changing `.env`, always run `php artisan config:clear`
- Make sure the storage link exists: `ls -la public/storage`
- Check that uploaded files exist: `ls -la storage/app/public/students/photos/`