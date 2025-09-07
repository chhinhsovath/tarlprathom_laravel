#!/bin/bash

echo "=============================================="
echo "PostgreSQL to MySQL Complete Data Sync"
echo "=============================================="
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

# MySQL connection details
MYSQL_HOST="157.10.73.52"
MYSQL_PORT="3306"
MYSQL_DB="tarl_prathom_mysql"
MYSQL_USER="admin"
MYSQL_PASS="P@ssw0rd"

# Create directories for dumps
mkdir -p database/sync_dumps
DUMP_DIR="database/sync_dumps"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo -e "${BLUE}Step 1: Exporting ALL PostgreSQL data...${NC}"

# List of ALL tables to sync
TABLES=(
    "users"
    "students"
    "schools"
    "pilot_schools"
    "assessments"
    "assessment_histories"
    "mentoring_visits"
    "translations"
    "geographic"
    "provinces"
    "pilot_provinces"
    "sessions"
    "classes"
    "mentor_school"
    "student_assessment_eligibility"
    "resources"
    "attendance_records"
    "learning_outcomes"
    "student_learning_outcomes"
    "intervention_programs"
    "student_interventions"
    "teaching_activities"
    "lesson_plans"
    "progress_tracking"
)

# Export each table from PostgreSQL
for TABLE in "${TABLES[@]}"; do
    echo "Exporting $TABLE from PostgreSQL..."
    
    # Export as CSV with headers
    PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -c "\COPY (SELECT * FROM $TABLE) TO STDOUT WITH CSV HEADER" > "$DUMP_DIR/${TABLE}_pg_$TIMESTAMP.csv" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        ROW_COUNT=$(tail -n +2 "$DUMP_DIR/${TABLE}_pg_$TIMESTAMP.csv" | wc -l | tr -d ' ')
        echo -e "  ${GREEN}✓${NC} $TABLE: $ROW_COUNT rows exported"
    else
        echo -e "  ${YELLOW}⚠${NC} $TABLE: No data or table doesn't exist"
    fi
done

echo ""
echo -e "${BLUE}Step 2: Clearing MySQL tables and importing fresh data...${NC}"

# Disable foreign key checks in MySQL
mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB -e "SET FOREIGN_KEY_CHECKS=0;" 2>/dev/null

# Import each table to MySQL
for TABLE in "${TABLES[@]}"; do
    CSV_FILE="$DUMP_DIR/${TABLE}_pg_$TIMESTAMP.csv"
    
    if [ ! -f "$CSV_FILE" ] || [ ! -s "$CSV_FILE" ]; then
        echo -e "  ${YELLOW}⚠${NC} Skipping $TABLE (no data)"
        continue
    fi
    
    # Get row count
    ROW_COUNT=$(tail -n +2 "$CSV_FILE" | wc -l | tr -d ' ')
    
    if [ "$ROW_COUNT" -eq 0 ]; then
        echo -e "  ${YELLOW}⚠${NC} $TABLE is empty"
        continue
    fi
    
    echo "Importing $TABLE to MySQL ($ROW_COUNT rows)..."
    
    # Clear existing data
    mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB -e "TRUNCATE TABLE $TABLE;" 2>/dev/null
    
    # Create a SQL file from CSV
    SQL_FILE="$DUMP_DIR/${TABLE}_import_$TIMESTAMP.sql"
    
    # Get headers
    HEADERS=$(head -1 "$CSV_FILE")
    
    # Convert CSV to SQL INSERT statements
    echo "SET FOREIGN_KEY_CHECKS=0;" > "$SQL_FILE"
    echo "TRUNCATE TABLE $TABLE;" >> "$SQL_FILE"
    
    # Process CSV and create INSERT statements
    tail -n +2 "$CSV_FILE" | while IFS= read -r line; do
        # Escape single quotes and handle NULL values
        VALUES=$(echo "$line" | sed "s/'/''/g" | sed 's/^,/NULL,/g' | sed 's/,,/,NULL,/g' | sed 's/,$/,NULL/g')
        
        # Build INSERT statement
        echo "INSERT INTO $TABLE VALUES (" >> "$SQL_FILE"
        
        # Process each field
        IFS=',' read -ra FIELDS <<< "$VALUES"
        FIRST=true
        for field in "${FIELDS[@]}"; do
            if [ "$FIRST" = true ]; then
                FIRST=false
            else
                echo -n "," >> "$SQL_FILE"
            fi
            
            # Handle NULL, boolean, and string values
            if [ "$field" = "NULL" ] || [ "$field" = "" ]; then
                echo -n "NULL" >> "$SQL_FILE"
            elif [ "$field" = "t" ] || [ "$field" = "true" ]; then
                echo -n "1" >> "$SQL_FILE"
            elif [ "$field" = "f" ] || [ "$field" = "false" ]; then
                echo -n "0" >> "$SQL_FILE"
            elif [[ "$field" =~ ^[0-9]+(\.[0-9]+)?$ ]]; then
                echo -n "$field" >> "$SQL_FILE"
            else
                # Remove quotes if present and re-add them
                field="${field%\"}"
                field="${field#\"}"
                echo -n "'$field'" >> "$SQL_FILE"
            fi
        done
        echo ");" >> "$SQL_FILE"
    done
    
    echo "SET FOREIGN_KEY_CHECKS=1;" >> "$SQL_FILE"
    
    # Import SQL file to MySQL
    mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB < "$SQL_FILE" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        # Verify count
        IMPORTED=$(mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB -e "SELECT COUNT(*) FROM $TABLE;" 2>/dev/null | tail -1)
        echo -e "  ${GREEN}✓${NC} $TABLE: $IMPORTED rows imported"
    else
        echo -e "  ${RED}✗${NC} $TABLE: Import failed, trying alternative method..."
        
        # Alternative: Use LOAD DATA LOCAL INFILE
        mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" --local-infile=1 $MYSQL_DB -e "
            TRUNCATE TABLE $TABLE;
            LOAD DATA LOCAL INFILE '$CSV_FILE'
            INTO TABLE $TABLE
            FIELDS TERMINATED BY ','
            ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 ROWS;" 2>/dev/null
            
        if [ $? -eq 0 ]; then
            IMPORTED=$(mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB -e "SELECT COUNT(*) FROM $TABLE;" 2>/dev/null | tail -1)
            echo -e "  ${GREEN}✓${NC} $TABLE: $IMPORTED rows imported (alternative method)"
        fi
    fi
done

# Re-enable foreign key checks
mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB -e "SET FOREIGN_KEY_CHECKS=1;" 2>/dev/null

echo ""
echo -e "${BLUE}Step 3: Final verification...${NC}"
echo ""

echo "PostgreSQL vs MySQL comparison:"
echo "--------------------------------"

for TABLE in "${TABLES[@]}"; do
    PG_COUNT=$(PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -t -c "SELECT COUNT(*) FROM $TABLE" 2>/dev/null | tr -d ' ')
    MYSQL_COUNT=$(mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p"$MYSQL_PASS" $MYSQL_DB -e "SELECT COUNT(*) FROM $TABLE;" 2>/dev/null | tail -1)
    
    if [ "$PG_COUNT" = "$MYSQL_COUNT" ]; then
        echo -e "$TABLE: PostgreSQL=$PG_COUNT, MySQL=$MYSQL_COUNT ${GREEN}✓ MATCH${NC}"
    else
        echo -e "$TABLE: PostgreSQL=$PG_COUNT, MySQL=$MYSQL_COUNT ${RED}✗ MISMATCH${NC}"
    fi
done

echo ""
echo -e "${GREEN}=============================================="
echo "Sync Complete!"
echo "=============================================="
echo ""
echo "Key tables status:"
echo "  translations: Check if 480 records are imported"
echo "  assessment_histories: Check if 20,340 records are imported"
echo "  schools: Check if 35 records are imported"
echo ""
echo -e "${NC}"