#!/bin/bash

echo "=============================================="
echo "MySQL to Remote MySQL Migration"
echo "=============================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Local MySQL details
LOCAL_HOST="127.0.0.1"
LOCAL_PORT="3306"
LOCAL_DB="tarl_prathom_mysql"
LOCAL_USER="root"
LOCAL_PASS=""

# Remote MySQL details
REMOTE_HOST="157.10.73.52"
REMOTE_PORT="3306"
REMOTE_DB="tarl_prathom_mysql"
REMOTE_USER="admin"
REMOTE_PASS="P@ssw0rd"

# Create backup directory
mkdir -p database/mysql_dumps
DUMP_FILE="database/mysql_dumps/tarl_prathom_mysql_$(date +%Y%m%d_%H%M%S).sql"

echo -e "${BLUE}Step 1: Testing local MySQL connection...${NC}"
mysql -h $LOCAL_HOST -P $LOCAL_PORT -u $LOCAL_USER -e "SELECT 1" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Local MySQL connection successful${NC}"
else
    echo -e "${RED}✗ Cannot connect to local MySQL${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Step 2: Testing remote MySQL connection...${NC}"
mysql -h $REMOTE_HOST -P $REMOTE_PORT -u $REMOTE_USER -p"$REMOTE_PASS" -e "SELECT 1" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Remote MySQL connection successful${NC}"
else
    echo -e "${RED}✗ Cannot connect to remote MySQL at $REMOTE_HOST${NC}"
    echo "Please check:"
    echo "  - Network connectivity to $REMOTE_HOST:$REMOTE_PORT"
    echo "  - MySQL credentials"
    echo "  - Firewall settings"
    exit 1
fi

echo ""
echo -e "${BLUE}Step 3: Getting local database statistics...${NC}"
LOCAL_STATS=$(mysql -h $LOCAL_HOST -P $LOCAL_PORT -u $LOCAL_USER $LOCAL_DB -e "
SELECT 
    (SELECT COUNT(*) FROM users) as users,
    (SELECT COUNT(*) FROM students) as students,
    (SELECT COUNT(*) FROM pilot_schools) as pilot_schools,
    (SELECT COUNT(*) FROM assessments) as assessments,
    (SELECT COUNT(*) FROM mentoring_visits) as mentoring_visits,
    (SELECT COUNT(*) FROM translations) as translations
" 2>/dev/null | tail -1)

echo "Local database contains:"
echo "$LOCAL_STATS" | awk '{print "  Users: " $1 "\n  Students: " $2 "\n  Pilot Schools: " $3 "\n  Assessments: " $4 "\n  Mentoring Visits: " $5 "\n  Translations: " $6}'

echo ""
echo -e "${BLUE}Step 4: Exporting local MySQL database...${NC}"
mysqldump -h $LOCAL_HOST -P $LOCAL_PORT -u $LOCAL_USER \
    --single-transaction \
    --routines \
    --triggers \
    --add-drop-table \
    --create-options \
    --extended-insert \
    --lock-tables=false \
    --quick \
    --set-charset \
    $LOCAL_DB > "$DUMP_FILE"

if [ $? -eq 0 ]; then
    FILE_SIZE=$(ls -lh "$DUMP_FILE" | awk '{print $5}')
    echo -e "${GREEN}✓ Database exported successfully ($FILE_SIZE)${NC}"
    echo "  Dump file: $DUMP_FILE"
else
    echo -e "${RED}✗ Failed to export database${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Step 5: Creating database on remote server (if needed)...${NC}"
mysql -h $REMOTE_HOST -P $REMOTE_PORT -u $REMOTE_USER -p"$REMOTE_PASS" -e "CREATE DATABASE IF NOT EXISTS $REMOTE_DB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Remote database ready${NC}"
else
    echo -e "${YELLOW}⚠ Could not create database (may already exist)${NC}"
fi

echo ""
echo -e "${BLUE}Step 6: Importing data to remote MySQL server...${NC}"
echo "This may take a few minutes depending on data size and network speed..."

# Import with progress indication
pv "$DUMP_FILE" 2>/dev/null | mysql -h $REMOTE_HOST -P $REMOTE_PORT -u $REMOTE_USER -p"$REMOTE_PASS" $REMOTE_DB 2>/dev/null

# If pv is not installed, use regular mysql import
if [ ${PIPESTATUS[0]} -ne 0 ]; then
    echo "Importing (this may take a while)..."
    mysql -h $REMOTE_HOST -P $REMOTE_PORT -u $REMOTE_USER -p"$REMOTE_PASS" $REMOTE_DB < "$DUMP_FILE"
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Data imported successfully${NC}"
    else
        echo -e "${RED}✗ Failed to import data${NC}"
        echo "Trying alternative import method..."
        
        # Try importing with max_allowed_packet adjustment
        mysql -h $REMOTE_HOST -P $REMOTE_PORT -u $REMOTE_USER -p"$REMOTE_PASS" $REMOTE_DB \
            --max_allowed_packet=1073741824 < "$DUMP_FILE"
        
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✓ Data imported successfully with adjusted settings${NC}"
        else
            echo -e "${RED}✗ Import failed. Please check the error messages above.${NC}"
            exit 1
        fi
    fi
else
    echo -e "${GREEN}✓ Data imported successfully${NC}"
fi

echo ""
echo -e "${BLUE}Step 7: Verifying remote database...${NC}"
REMOTE_STATS=$(mysql -h $REMOTE_HOST -P $REMOTE_PORT -u $REMOTE_USER -p"$REMOTE_PASS" $REMOTE_DB -e "
SELECT 
    (SELECT COUNT(*) FROM users) as users,
    (SELECT COUNT(*) FROM students) as students,
    (SELECT COUNT(*) FROM pilot_schools) as pilot_schools,
    (SELECT COUNT(*) FROM assessments) as assessments,
    (SELECT COUNT(*) FROM mentoring_visits) as mentoring_visits,
    (SELECT COUNT(*) FROM translations) as translations
" 2>/dev/null | tail -1)

echo "Remote database now contains:"
echo "$REMOTE_STATS" | awk '{print "  Users: " $1 "\n  Students: " $2 "\n  Pilot Schools: " $3 "\n  Assessments: " $4 "\n  Mentoring Visits: " $5 "\n  Translations: " $6}'

echo ""
echo -e "${GREEN}=============================================="
echo "Migration Complete!"
echo "=============================================="
echo ""
echo "Remote MySQL Database Details:"
echo "  Host: $REMOTE_HOST"
echo "  Port: $REMOTE_PORT"
echo "  Database: $REMOTE_DB"
echo "  Username: $REMOTE_USER"
echo ""
echo "To use this database in your Laravel application, update .env:"
echo ""
echo "DB_CONNECTION=mysql"
echo "DB_HOST=$REMOTE_HOST"
echo "DB_PORT=$REMOTE_PORT"
echo "DB_DATABASE=$REMOTE_DB"
echo "DB_USERNAME=$REMOTE_USER"
echo "DB_PASSWORD=$REMOTE_PASS"
echo ""
echo -e "${NC}"