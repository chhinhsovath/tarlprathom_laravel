#!/bin/bash

echo "=========================================="
echo "PostgreSQL-Only Production Configuration"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# PostgreSQL connection details
PG_HOST="157.10.73.52"
PG_PORT="5432"
PG_DB="tarl_prathom"
PG_USER="admin"
PG_PASS="P@ssw0rd"

echo -e "${BLUE}=== PostgreSQL Connection Diagnostics ===${NC}"
echo ""

# Step 1: Network connectivity test
echo -e "${YELLOW}Step 1: Testing network connectivity to PostgreSQL server...${NC}"
if nc -zv $PG_HOST $PG_PORT 2>/dev/null; then
    echo -e "${GREEN}✓ Network connection to $PG_HOST:$PG_PORT successful${NC}"
    NETWORK_OK=true
else
    echo -e "${RED}✗ Cannot connect to $PG_HOST:$PG_PORT${NC}"
    NETWORK_OK=false
fi

# Step 2: DNS resolution test
echo ""
echo -e "${YELLOW}Step 2: Testing DNS resolution...${NC}"
if ping -c 1 $PG_HOST >/dev/null 2>&1; then
    echo -e "${GREEN}✓ DNS resolution successful${NC}"
    DNS_OK=true
else
    echo -e "${RED}✗ DNS resolution failed${NC}"
    DNS_OK=false
fi

# Step 3: PostgreSQL authentication test
echo ""
echo -e "${YELLOW}Step 3: Testing PostgreSQL authentication...${NC}"
PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -c "SELECT version();" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ PostgreSQL authentication successful${NC}"
    AUTH_OK=true
    
    # Get PostgreSQL version
    PG_VERSION=$(PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -t -c "SELECT version();" 2>/dev/null | head -1)
    echo -e "${GREEN}  PostgreSQL Version: ${PG_VERSION:0:50}...${NC}"
else
    echo -e "${RED}✗ PostgreSQL authentication failed${NC}"
    AUTH_OK=false
fi

# Step 4: Table existence check
echo ""
echo -e "${YELLOW}Step 4: Checking database tables...${NC}"
if [ "$AUTH_OK" = true ]; then
    TABLE_COUNT=$(PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -t -c "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'public';" 2>/dev/null | tr -d ' ')
    echo -e "${GREEN}✓ Found $TABLE_COUNT tables in database${NC}"
    
    # Check key tables
    for table in users students schools pilot_schools assessments mentoring_visits; do
        EXISTS=$(PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -t -c "SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '$table');" 2>/dev/null | tr -d ' ')
        if [ "$EXISTS" = "t" ]; then
            ROW_COUNT=$(PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -t -c "SELECT COUNT(*) FROM $table;" 2>/dev/null | tr -d ' ')
            echo -e "  ${GREEN}✓${NC} $table: $ROW_COUNT rows"
        else
            echo -e "  ${RED}✗${NC} $table: missing"
        fi
    done
fi

echo ""
echo -e "${BLUE}=== Laravel Configuration ===${NC}"
echo ""

# Step 5: Check current .env configuration
echo -e "${YELLOW}Step 5: Current .env database configuration:${NC}"
if [ -f .env ]; then
    grep "^DB_" .env | grep -E "(CONNECTION|HOST|PORT|DATABASE|USERNAME)" | while read line; do
        echo "  $line"
    done
else
    echo -e "${RED}✗ .env file not found${NC}"
fi

# Step 6: Create proper .env configuration
echo ""
echo -e "${YELLOW}Step 6: Creating correct PostgreSQL configuration...${NC}"
cat > .env.postgresql << EOF
# Database Configuration - PostgreSQL ONLY
DB_CONNECTION=pgsql
DB_HOST=$PG_HOST
DB_PORT=$PG_PORT
DB_DATABASE=$PG_DB
DB_USERNAME=$PG_USER
DB_PASSWORD=$PG_PASS
EOF
echo -e "${GREEN}✓ Configuration saved to .env.postgresql${NC}"

echo ""
echo -e "${BLUE}=== Diagnosis Summary ===${NC}"
echo ""

if [ "$NETWORK_OK" = true ] && [ "$AUTH_OK" = true ]; then
    echo -e "${GREEN}✓ PostgreSQL connection is WORKING${NC}"
    echo ""
    echo "To apply this configuration:"
    echo "1. Update your .env file with PostgreSQL settings:"
    echo "   cat .env.postgresql >> .env"
    echo ""
    echo "2. Clear Laravel caches:"
    echo "   php artisan config:clear"
    echo "   php artisan cache:clear"
    echo ""
    echo "3. Test the connection:"
    echo "   php artisan tinker"
    echo "   >>> \\DB::connection()->getPdo();"
    echo "   >>> exit"
elif [ "$NETWORK_OK" = false ]; then
    echo -e "${RED}✗ NETWORK ISSUE DETECTED${NC}"
    echo ""
    echo "The PostgreSQL server at $PG_HOST:$PG_PORT is not reachable."
    echo ""
    echo "Possible causes:"
    echo "1. Firewall blocking connection"
    echo "2. PostgreSQL server is down"
    echo "3. Network routing issue"
    echo "4. Wrong IP address or port"
    echo ""
    echo "Solutions for PRODUCTION SERVER:"
    echo "1. Check if your hosting provider blocks outbound connections to port 5432"
    echo "2. Request whitelisting of IP $PG_HOST"
    echo "3. Check server firewall rules:"
    echo "   sudo iptables -L -n | grep 5432"
    echo "   sudo ufw status"
    echo ""
    echo "4. Test from production server:"
    echo "   telnet $PG_HOST $PG_PORT"
    echo "   nc -zv $PG_HOST $PG_PORT"
elif [ "$AUTH_OK" = false ]; then
    echo -e "${RED}✗ AUTHENTICATION ISSUE DETECTED${NC}"
    echo ""
    echo "Network is OK but PostgreSQL authentication failed."
    echo ""
    echo "Possible causes:"
    echo "1. Wrong username/password"
    echo "2. User '$PG_USER' doesn't exist"
    echo "3. Database '$PG_DB' doesn't exist"
    echo "4. PostgreSQL pg_hba.conf not allowing this connection"
    echo ""
    echo "Solutions:"
    echo "1. Verify credentials with database administrator"
    echo "2. Check PostgreSQL logs on the database server"
fi

echo ""
echo -e "${BLUE}=== Production Server Deployment ===${NC}"
echo ""
echo "For production deployment (tarl.dashboardkh.com):"
echo ""
echo "1. SSH into your production server"
echo "2. Navigate to project directory"
echo "3. Update .env file with PostgreSQL settings ONLY:"
echo ""
cat << 'EOF'
DB_CONNECTION=pgsql
DB_HOST=157.10.73.52
DB_PORT=5432
DB_DATABASE=tarl_prathom
DB_USERNAME=admin
DB_PASSWORD=P@ssw0rd
EOF
echo ""
echo "4. Run these commands:"
echo "   php artisan config:clear"
echo "   php artisan cache:clear"
echo "   php artisan config:cache"
echo "   php artisan route:cache"
echo "   php artisan view:cache"
echo ""
echo "5. If connection still fails, contact your hosting provider to:"
echo "   - Allow outbound connections to 157.10.73.52:5432"
echo "   - Check if PostgreSQL client libraries are installed"
echo "   - Verify PHP pdo_pgsql extension is enabled"
echo ""
echo -e "${GREEN}=========================================="
echo "PostgreSQL-Only Configuration Complete!"
echo "==========================================${NC}"