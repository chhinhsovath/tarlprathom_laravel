#!/bin/bash

echo "==================================="
echo "Production Database Connection Fix"
echo "==================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Step 1: Testing network connectivity to PostgreSQL server...${NC}"
if nc -zv 157.10.73.52 5432 2>/dev/null; then
    echo -e "${GREEN}✓ Network connection successful${NC}"
else
    echo -e "${RED}✗ Cannot connect to 157.10.73.52:5432${NC}"
    echo "Possible issues:"
    echo "  - Firewall blocking connection"
    echo "  - PostgreSQL server is down"
    echo "  - Network routing issue"
    exit 1
fi

echo ""
echo -e "${YELLOW}Step 2: Testing PostgreSQL authentication...${NC}"
PGPASSWORD='P@ssw0rd' psql -h 157.10.73.52 -p 5432 -U admin -d tarl_prathom -c "SELECT 1" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ PostgreSQL authentication successful${NC}"
else
    echo -e "${RED}✗ PostgreSQL authentication failed${NC}"
    echo "Possible issues:"
    echo "  - Wrong password"
    echo "  - User 'admin' doesn't exist"
    echo "  - Database 'tarl_prathom' doesn't exist"
    echo "  - PostgreSQL pg_hba.conf not allowing this connection"
fi

echo ""
echo -e "${YELLOW}Step 3: Current .env database configuration:${NC}"
if [ -f .env ]; then
    grep "^DB_" .env | head -6
else
    echo -e "${RED}✗ .env file not found${NC}"
fi

echo ""
echo -e "${YELLOW}Step 4: Recommended actions:${NC}"
echo ""
echo "Option A: Fix PostgreSQL Connection (Recommended)"
echo "================================================"
echo "1. Update your .env file with:"
cat << 'EOF'
DB_CONNECTION=pgsql
DB_HOST=157.10.73.52
DB_PORT=5432
DB_DATABASE=tarl_prathom
DB_USERNAME=admin
DB_PASSWORD=P@ssw0rd
EOF

echo ""
echo "2. Clear all caches:"
echo "   php artisan config:clear"
echo "   php artisan cache:clear"
echo ""

echo "Option B: Use SQLite as Temporary Fallback"
echo "=========================================="
echo "1. Create SQLite database:"
echo "   touch database/database.sqlite"
echo ""
echo "2. Update .env file:"
echo "   DB_CONNECTION=sqlite"
echo "   DB_DATABASE=database/database.sqlite"
echo ""
echo "3. Run migrations:"
echo "   php artisan migrate"
echo "   php artisan db:seed"
echo ""

echo "Option C: Check Server Firewall"
echo "================================"
echo "If running on production server, check if PostgreSQL port is allowed:"
echo "   sudo ufw status"
echo "   sudo iptables -L"
echo ""
echo "Contact your hosting provider to whitelist:"
echo "   - IP: 157.10.73.52"
echo "   - Port: 5432"
echo ""

echo -e "${GREEN}==================================="
echo "Script completed!"
echo "===================================${NC}"