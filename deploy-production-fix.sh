#!/bin/bash

echo "====================================="
echo "Production Deployment Database Fix"
echo "====================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Step 1: Backing up current .env file...${NC}"
if [ -f .env ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo -e "${GREEN}✓ Backup created${NC}"
else
    echo -e "${RED}✗ No .env file found${NC}"
fi

echo ""
echo -e "${YELLOW}Step 2: Creating production-ready .env configuration...${NC}"
cat > .env.production.fixed << 'EOF'
APP_NAME="TaRL Prathom"
APP_ENV=production
APP_KEY=base64:4nrwMdJY2fBwL8E7Kf8gGONGIJXTYMbEGEhqGk6MnaQ=
APP_DEBUG=false
APP_URL=https://tarl.dashboardkh.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Primary Database Configuration - PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=157.10.73.52
DB_PORT=5432
DB_DATABASE=tarl_prathom
DB_USERNAME=admin
DB_PASSWORD=P@ssw0rd

# Fallback Database Configuration - SQLite (if PostgreSQL fails)
# DB_CONNECTION=sqlite
# DB_DATABASE=/path/to/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

echo -e "${GREEN}✓ Production configuration created${NC}"

echo ""
echo -e "${YELLOW}Step 3: Testing database connections...${NC}"

# Test PostgreSQL connection
echo -e "Testing PostgreSQL connection..."
PGPASSWORD='P@ssw0rd' psql -h 157.10.73.52 -p 5432 -U admin -d tarl_prathom -c "SELECT 1" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ PostgreSQL connection successful${NC}"
    USE_POSTGRES=true
else
    echo -e "${RED}✗ PostgreSQL connection failed${NC}"
    USE_POSTGRES=false
fi

echo ""
echo -e "${YELLOW}Step 4: Deployment Instructions${NC}"
echo "================================"

if [ "$USE_POSTGRES" = true ]; then
    echo -e "${GREEN}PostgreSQL is accessible. Follow these steps:${NC}"
    echo ""
    echo "1. Copy the fixed configuration:"
    echo "   cp .env.production.fixed .env"
    echo ""
    echo "2. Clear all caches:"
    echo "   php artisan config:clear"
    echo "   php artisan cache:clear"
    echo "   php artisan route:clear"
    echo "   php artisan view:clear"
    echo ""
    echo "3. Run migrations (if needed):"
    echo "   php artisan migrate --force"
    echo ""
    echo "4. Optimize for production:"
    echo "   php artisan config:cache"
    echo "   php artisan route:cache"
    echo "   php artisan view:cache"
else
    echo -e "${YELLOW}PostgreSQL is not accessible. Use SQLite fallback:${NC}"
    echo ""
    echo "1. Create SQLite database:"
    echo "   touch database/database.sqlite"
    echo ""
    echo "2. Update .env file:"
    echo "   Edit .env.production.fixed and uncomment SQLite lines"
    echo "   Comment out PostgreSQL lines"
    echo "   cp .env.production.fixed .env"
    echo ""
    echo "3. Clear caches:"
    echo "   php artisan config:clear"
    echo "   php artisan cache:clear"
    echo ""
    echo "4. Run migrations and seed:"
    echo "   php artisan migrate:fresh --seed --force"
    echo ""
    echo "5. Optimize for production:"
    echo "   php artisan config:cache"
    echo "   php artisan route:cache"
    echo "   php artisan view:cache"
fi

echo ""
echo -e "${YELLOW}Step 5: Server Requirements Check${NC}"
echo "================================="

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "PHP Version: $PHP_VERSION"
if [[ "$PHP_VERSION" == 8.* ]]; then
    echo -e "${GREEN}✓ PHP 8.x detected${NC}"
else
    echo -e "${RED}✗ PHP 8.x required${NC}"
fi

# Check required PHP extensions
echo ""
echo "Checking PHP extensions:"
for ext in pdo pdo_pgsql pdo_sqlite mbstring openssl tokenizer xml ctype json bcmath fileinfo; do
    if php -m | grep -q "^$ext$"; then
        echo -e "${GREEN}✓ $ext${NC}"
    else
        echo -e "${RED}✗ $ext missing${NC}"
    fi
done

echo ""
echo -e "${GREEN}====================================="
echo "Deployment script completed!"
echo "=====================================${NC}"
echo ""
echo "IMPORTANT: If on production server:"
echo "1. Ensure firewall allows connection to 157.10.73.52:5432"
echo "2. Contact hosting provider if database connection fails"
echo "3. Use SQLite as temporary fallback if needed"