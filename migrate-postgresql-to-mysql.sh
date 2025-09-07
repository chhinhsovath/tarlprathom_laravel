#!/bin/bash

echo "=============================================="
echo "PostgreSQL to MySQL Migration Tool"
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

# MySQL connection details (local)
MYSQL_HOST="127.0.0.1"
MYSQL_PORT="3306"
MYSQL_DB="tarl_prathom_mysql"
MYSQL_USER="root"
MYSQL_PASS=""

# Create directories for dumps
mkdir -p database/dumps
DUMP_DIR="database/dumps"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo -e "${BLUE}Step 1: Testing PostgreSQL connection...${NC}"
PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -c "SELECT 1" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ PostgreSQL connection successful${NC}"
else
    echo -e "${RED}✗ Cannot connect to PostgreSQL${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Step 2: Exporting PostgreSQL data...${NC}"

# Export schema and data separately
echo "Exporting schema..."
PGPASSWORD=$PG_PASS pg_dump -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB \
    --schema-only \
    --no-owner \
    --no-privileges \
    --no-tablespaces \
    --no-unlogged-table-data \
    --quote-all-identifiers \
    -f "$DUMP_DIR/postgres_schema_$TIMESTAMP.sql"

echo "Exporting data..."
PGPASSWORD=$PG_PASS pg_dump -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB \
    --data-only \
    --no-owner \
    --no-privileges \
    --disable-triggers \
    --quote-all-identifiers \
    -f "$DUMP_DIR/postgres_data_$TIMESTAMP.sql"

# Export in a more portable format
echo "Creating portable export..."
PGPASSWORD=$PG_PASS pg_dump -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB \
    --no-owner \
    --no-privileges \
    --no-tablespaces \
    --no-unlogged-table-data \
    --quote-all-identifiers \
    --inserts \
    --column-inserts \
    -f "$DUMP_DIR/postgres_full_$TIMESTAMP.sql"

echo -e "${GREEN}✓ PostgreSQL data exported${NC}"

echo ""
echo -e "${BLUE}Step 3: Getting table statistics...${NC}"

# Get row counts for verification
PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -t << EOF > "$DUMP_DIR/table_counts_$TIMESTAMP.txt"
SELECT 'users: ' || COUNT(*) FROM users
UNION ALL
SELECT 'students: ' || COUNT(*) FROM students
UNION ALL
SELECT 'schools: ' || COUNT(*) FROM schools
UNION ALL
SELECT 'pilot_schools: ' || COUNT(*) FROM pilot_schools
UNION ALL
SELECT 'assessments: ' || COUNT(*) FROM assessments
UNION ALL
SELECT 'mentoring_visits: ' || COUNT(*) FROM mentoring_visits
UNION ALL
SELECT 'geographic: ' || COUNT(*) FROM geographic
UNION ALL
SELECT 'translations: ' || COUNT(*) FROM translations;
EOF

cat "$DUMP_DIR/table_counts_$TIMESTAMP.txt"

echo ""
echo -e "${BLUE}Step 4: Exporting data as CSV (most reliable method)...${NC}"

# List of tables to export
TABLES=(
    "users"
    "students"
    "schools"
    "pilot_schools"
    "assessments"
    "mentoring_visits"
    "geographic"
    "translations"
    "sessions"
    "classes"
    "mentor_school"
    "student_assessment_eligibility"
    "assessment_histories"
    "resources"
    "attendance_records"
    "learning_outcomes"
    "student_learning_outcomes"
    "intervention_programs"
    "student_interventions"
    "teaching_activities"
    "lesson_plans"
    "progress_tracking"
    "provinces"
    "pilot_provinces"
)

mkdir -p "$DUMP_DIR/csv"

for TABLE in "${TABLES[@]}"; do
    echo "Exporting $TABLE..."
    PGPASSWORD=$PG_PASS psql -h $PG_HOST -p $PG_PORT -U $PG_USER -d $PG_DB -c "\COPY $TABLE TO '$DUMP_DIR/csv/${TABLE}_$TIMESTAMP.csv' WITH CSV HEADER" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo -e "  ${GREEN}✓${NC} $TABLE exported"
    else
        echo -e "  ${YELLOW}⚠${NC} $TABLE might not exist or is empty"
    fi
done

echo ""
echo -e "${GREEN}=============================================="
echo "PostgreSQL Export Complete!"
echo "=============================================="
echo ""
echo "Files created in $DUMP_DIR:"
echo "  - postgres_schema_$TIMESTAMP.sql (schema only)"
echo "  - postgres_data_$TIMESTAMP.sql (data only)"
echo "  - postgres_full_$TIMESTAMP.sql (full dump with INSERT statements)"
echo "  - table_counts_$TIMESTAMP.txt (row counts for verification)"
echo "  - csv/*.csv (CSV exports for each table)"
echo ""
echo "Next steps:"
echo "1. Run: ./convert-to-mysql.sh"
echo "2. Import into MySQL"
echo "3. Update .env file"
echo ""
echo -e "${NC}"