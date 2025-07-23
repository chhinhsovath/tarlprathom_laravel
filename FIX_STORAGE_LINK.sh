#!/bin/bash

# Fix storage:link issue on production server
# Run this script from the Laravel root directory (/var/www/html/tarl)

echo "=== Fixing Laravel Storage Link Issue ==="

# 1. Check current directory
echo -e "\n1. Current directory:"
pwd

# 2. Check if storage/app/public exists
echo -e "\n2. Checking if storage/app/public directory exists:"
if [ -d "storage/app/public" ]; then
    echo "✓ storage/app/public exists"
    ls -la storage/app/public/
else
    echo "✗ storage/app/public does NOT exist - Creating it..."
    mkdir -p storage/app/public
    echo "✓ Created storage/app/public"
fi

# 3. Check if public/storage exists (file, directory, or symlink)
echo -e "\n3. Checking public/storage:"
if [ -e "public/storage" ] || [ -L "public/storage" ]; then
    echo "✓ public/storage exists (removing it first)"
    ls -la public/storage
    rm -rf public/storage
    echo "✓ Removed existing public/storage"
else
    echo "✓ public/storage does not exist (good)"
fi

# 4. Create the symlink manually
echo -e "\n4. Creating symlink manually:"
ln -s $(pwd)/storage/app/public $(pwd)/public/storage

# 5. Verify the symlink
echo -e "\n5. Verifying symlink:"
if [ -L "public/storage" ]; then
    echo "✓ Symlink created successfully!"
    ls -la public/storage
else
    echo "✗ Failed to create symlink"
    exit 1
fi

# 6. Set proper permissions
echo -e "\n6. Setting permissions:"
chmod -R 775 storage
chown -R www-data:www-data storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data bootstrap/cache
echo "✓ Permissions set"

# 7. Create required upload directories
echo -e "\n7. Creating upload directories:"
mkdir -p storage/app/public/students/photos
mkdir -p storage/app/public/users
chown -R www-data:www-data storage/app/public/
chmod -R 775 storage/app/public/
echo "✓ Upload directories created"

# 8. Test write access
echo -e "\n8. Testing write access:"
sudo -u www-data touch storage/app/public/test_write.txt
if [ -f "storage/app/public/test_write.txt" ]; then
    echo "✓ Write test successful"
    rm storage/app/public/test_write.txt
else
    echo "✗ Write test failed - check permissions"
fi

echo -e "\n=== Storage link setup completed! ==="
echo "You should now be able to upload files."

# Optional: Clear Laravel caches
echo -e "\n9. Clearing Laravel caches:"
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo "✓ Caches cleared"

echo -e "\n=== All done! ===