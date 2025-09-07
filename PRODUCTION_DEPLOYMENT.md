# Production Deployment Guide - PostgreSQL Only

## Database Configuration

Your production server at `tarl.dashboardkh.com` MUST use PostgreSQL database only.

### PostgreSQL Connection Details
```
Host: 157.10.73.52
Port: 5432
Database: tarl_prathom
Username: admin
Password: P@ssw0rd
```

## Production Server Setup

### 1. Update .env File

SSH into your production server and update the `.env` file with these exact settings:

```bash
DB_CONNECTION=pgsql
DB_HOST=157.10.73.52
DB_PORT=5432
DB_DATABASE=tarl_prathom
DB_USERNAME=admin
DB_PASSWORD=P@ssw0rd
```

### 2. Clear All Caches

Run these commands in order:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting Connection Issues

### If you see "Connection refused" error:

The production server cannot reach the PostgreSQL database. This is a **network/firewall issue**.

#### Solution Steps:

1. **Check Server Firewall**
   ```bash
   sudo iptables -L -n | grep 5432
   sudo ufw status
   ```

2. **Test Connection from Production Server**
   ```bash
   telnet 157.10.73.52 5432
   nc -zv 157.10.73.52 5432
   ```

3. **Contact Your Hosting Provider**
   
   Ask them to:
   - Allow outbound connections to IP: `157.10.73.52` on port `5432`
   - Verify PHP `pdo_pgsql` extension is installed and enabled
   - Check if PostgreSQL client libraries are installed

### If you see "SQLSTATE[08006]" error:

This indicates the PostgreSQL server is rejecting the connection.

#### Check:
1. Credentials are correct in `.env`
2. Database `tarl_prathom` exists
3. User `admin` has access permissions

## Quick Diagnostic Commands

### From Production Server:

```bash
# Test network connectivity
ping 157.10.73.52

# Test PostgreSQL port
telnet 157.10.73.52 5432

# Test PostgreSQL connection
PGPASSWORD='P@ssw0rd' psql -h 157.10.73.52 -p 5432 -U admin -d tarl_prathom -c "SELECT 1"
```

### Check PHP Extensions:

```bash
php -m | grep pgsql
```

Should show:
- pdo_pgsql
- pgsql

## Required PHP Extensions

Ensure these are installed on production:
- `php-pgsql`
- `php-pdo-pgsql`

Install if missing:
```bash
# Ubuntu/Debian
sudo apt-get install php8.1-pgsql

# CentOS/RHEL
sudo yum install php-pgsql
```

## Database is Already Set Up

The PostgreSQL database at `157.10.73.52` already contains:
- 80 users
- 3,335 students  
- 30 pilot schools
- 20,007 assessments
- All necessary tables and data

**You do NOT need to run migrations or seeders on production.**

## Summary

1. **Local Development**: ✅ Working perfectly with PostgreSQL
2. **Production Issue**: Network/firewall blocking connection to PostgreSQL
3. **Solution**: Contact hosting provider to allow connection to `157.10.73.52:5432`

## Support Script

Run this on production to diagnose:

```bash
curl -O https://raw.githubusercontent.com/your-repo/postgresql-only-fix.sh
chmod +x postgresql-only-fix.sh
./postgresql-only-fix.sh
```

This will show exactly where the connection is failing.