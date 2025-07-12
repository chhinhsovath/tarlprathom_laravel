#\!/bin/bash

echo "🔧 Fixing Production Server Permissions and Apache Configuration"
echo "=============================================================="
echo ""

# 1. Fix file ownership and permissions
echo "1️⃣ Setting proper ownership..."
sudo chown -R www-data:www-data /var/www/html/tarl
sudo chmod -R 755 /var/www/html/tarl
sudo chmod -R 775 /var/www/html/tarl/storage
sudo chmod -R 775 /var/www/html/tarl/bootstrap/cache

echo "✅ Ownership and permissions set"
echo ""

# 2. Backup and update .htaccess
echo "2️⃣ Updating .htaccess for subdirectory deployment..."
sudo cp /var/www/html/tarl/public/.htaccess /var/www/html/tarl/public/.htaccess.backup

# Create new .htaccess with RewriteBase
sudo tee /var/www/html/tarl/public/.htaccess > /dev/null << 'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteBase /tarl/

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} \!-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} \!-d
    RewriteCond %{REQUEST_FILENAME} \!-f
    RewriteRule ^ index.php [L]
</IfModule>
HTACCESS

echo "✅ .htaccess updated with RewriteBase /tarl/"
echo ""

# 3. Update Apache virtual host configuration
echo "3️⃣ Updating Apache configuration..."
sudo tee -a /etc/apache2/sites-available/000-default.conf > /dev/null << 'APACHE'

    # Laravel TaRL Application
    Alias /tarl /var/www/html/tarl/public
    
    <Directory /var/www/html/tarl/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        
        # Enable rewrite module
        RewriteEngine On
        
        # Handle Laravel pretty URLs
        RewriteCond %{REQUEST_FILENAME} \!-f
        RewriteCond %{REQUEST_FILENAME} \!-d
        RewriteRule ^(.*)$ index.php [QSA,L]
    </Directory>
APACHE

echo "✅ Apache configuration updated"
echo ""

# 4. Enable required Apache modules
echo "4️⃣ Enabling Apache modules..."
sudo a2enmod rewrite
sudo a2enmod alias

echo "✅ Apache modules enabled"
echo ""

# 5. Test Apache configuration
echo "5️⃣ Testing Apache configuration..."
sudo apache2ctl configtest

# 6. Reload Apache
echo "6️⃣ Reloading Apache..."
sudo systemctl reload apache2

echo ""
echo "🎉 Production server configuration complete\!"
echo ""
echo "Next steps:"
echo "1. Run: cd /var/www/html/tarl && composer install --no-dev --optimize-autoloader"
echo "2. Run: php artisan config:cache"
echo "3. Run: php artisan route:cache"
echo "4. Run: php artisan view:cache"
echo "5. Test: https://plp.moeys.gov.kh/tarl/"
