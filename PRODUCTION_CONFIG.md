# Production Database Configuration

## IMPORTANT: Database Configuration Fix

The production server was incorrectly configured to use MySQL instead of PostgreSQL. 

### Update your production server's `.env` file with these settings:

```env
DB_CONNECTION=pgsql
DB_HOST=157.10.73.52
DB_PORT=5432
DB_DATABASE=tarl_prathom
DB_USERNAME=admin
DB_PASSWORD=P@ssw0rd
```

### Previous (incorrect) configuration:
```env
DB_CONNECTION=mysql  # ❌ Wrong
DB_HOST=127.0.0.1    # ❌ Wrong
DB_PORT=3306         # ❌ Wrong
```

## Steps to fix on production server:

1. SSH into your production server
2. Navigate to your application directory
3. Edit the `.env` file (not `.env.production`)
4. Update the DB_CONNECTION settings as shown above
5. Clear the configuration cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
6. The application should now connect to the correct PostgreSQL database

## Verification

The PostgreSQL database at `157.10.73.52` already has:
- The `pilot_school_id` column in the `users` table
- All necessary tables and data
- 30 pilot schools
- 3,335+ students
- 20,000+ assessments

No database migrations are needed - just the configuration fix.