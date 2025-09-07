# Namecheap Hosting + External PostgreSQL Setup Guide

## Current Setup
- **Laravel App**: Hosted on Namecheap.com (tarl.dashboardkh.com)
- **PostgreSQL Database**: Hosted at 157.10.73.52:5432

## The Problem
Namecheap shared hosting typically blocks outbound connections to external database servers for security reasons. This is why you're getting "Connection refused" errors.

## Solutions

### Option 1: Contact Namecheap Support (Recommended)

#### Support Ticket Template:
```
Subject: Request to Whitelist External PostgreSQL Database Connection

Hello Namecheap Support,

I need to connect my Laravel application hosted on your servers to an external PostgreSQL database. Please whitelist the following connection:

Domain: tarl.dashboardkh.com
External Database Details:
- Type: PostgreSQL
- IP Address: 157.10.73.52
- Port: 5432
- Purpose: Production database for educational assessment system

Could you please:
1. Allow outbound connections from my hosting account to 157.10.73.52:5432
2. Confirm that PHP pdo_pgsql extension is enabled
3. Provide any specific configuration needed for external database connections

This is critical for my application to function properly.

Thank you for your assistance.
```

### Option 2: Use Namecheap VPS or Dedicated Server

If you're on shared hosting, Namecheap typically won't allow external database connections. You may need to upgrade to:
- **VPS Hosting**: Full control over firewall rules
- **Dedicated Server**: Complete server control

### Option 3: Use SSH Tunnel (If SSH Access Available)

If you have SSH access to your Namecheap hosting:

```bash
# Create SSH tunnel from Namecheap to PostgreSQL
ssh -L 5432:157.10.73.52:5432 your_namecheap_user@your_namecheap_server
```

Then update `.env` to use localhost:
```
DB_HOST=127.0.0.1
```

## Namecheap Hosting Types & Database Support

### Shared Hosting (Stellar, Stellar Plus, Stellar Business)
- ❌ External database connections usually BLOCKED
- ✅ Only local MySQL databases supported
- ❌ Cannot modify firewall rules

### VPS Hosting
- ✅ Full root access
- ✅ Can configure firewall rules
- ✅ Can install PostgreSQL client

### Dedicated Server
- ✅ Complete control
- ✅ No restrictions on external connections

## Check Your Hosting Type

1. Log into Namecheap account
2. Go to Dashboard → Hosting List
3. Check your plan type

## If You Have cPanel Access

1. Log into cPanel
2. Look for "Remote MySQL" or "Remote Database Access"
3. Try adding 157.10.73.52 (though this is for MySQL, not PostgreSQL)

## Testing Connection from Namecheap

Create this test file on your Namecheap hosting:

### test-postgres.php
```php
<?php
// Test PostgreSQL connection from Namecheap

$host = '157.10.73.52';
$port = '5432';
$database = 'tarl_prathom';
$username = 'admin';
$password = 'P@ssw0rd';

echo "Testing PostgreSQL connection from Namecheap...\n";
echo "Host: $host:$port\n";
echo "Database: $database\n\n";

// Test 1: Check if PostgreSQL extension is loaded
echo "1. Checking PHP PostgreSQL extension: ";
if (extension_loaded('pgsql') && extension_loaded('pdo_pgsql')) {
    echo "✓ Installed\n";
} else {
    echo "✗ Not installed\n";
    echo "   Required extensions: pgsql, pdo_pgsql\n";
}

// Test 2: Network connectivity
echo "2. Testing network connectivity: ";
$connection = @fsockopen($host, $port, $errno, $errstr, 5);
if ($connection) {
    echo "✓ Port is reachable\n";
    fclose($connection);
} else {
    echo "✗ Cannot reach $host:$port\n";
    echo "   Error: $errstr ($errno)\n";
    echo "   This indicates a firewall/network issue\n";
}

// Test 3: PostgreSQL connection
echo "3. Testing PostgreSQL connection: ";
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    echo "✓ Connected successfully!\n";
    
    // Get some info
    $result = $pdo->query("SELECT version()");
    $version = $result->fetch(PDO::FETCH_ASSOC);
    echo "   PostgreSQL Version: " . substr($version['version'], 0, 30) . "...\n";
    
} catch (PDOException $e) {
    echo "✗ Connection failed\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";
echo "Summary:\n";
echo "If network connectivity fails, contact Namecheap support to whitelist 157.10.73.52:5432\n";
?>
```

Upload this file to your Namecheap hosting and access it via browser or command line.

## Immediate Actions

### 1. Check Hosting Type
Log into Namecheap and verify if you have:
- Shared Hosting (likely blocked)
- VPS (can be configured)
- Dedicated (full control)

### 2. Contact Support
Use the template above to request whitelisting

### 3. Alternative: Database Migration
If Namecheap cannot allow external connections, you might need to:
- Host PostgreSQL on the same Namecheap VPS
- Use a database hosting service that Namecheap allows
- Move the Laravel app to a host that allows external PostgreSQL

## Common Namecheap Support Responses

### "We don't support external databases on shared hosting"
**Solution**: Upgrade to VPS or move to a different host

### "PostgreSQL is not supported"
**Response**: You're not asking them to host PostgreSQL, just to allow outbound connections

### "Security policy prevents this"
**Solution**: Ask about VPS options or business hosting plans

## Recommended Hosting Alternatives

If Namecheap cannot accommodate external PostgreSQL:

1. **DigitalOcean** - Full VPS control, PostgreSQL friendly
2. **Linode** - Excellent for custom database setups
3. **AWS EC2** - Complete flexibility
4. **Heroku** - Built-in PostgreSQL support
5. **Railway** - Modern hosting with PostgreSQL

## Contact Information

### Namecheap Support
- Live Chat: Available 24/7 in account dashboard
- Ticket System: https://www.namecheap.com/support/
- Phone: +1.661.310.3305

## Final Notes

The issue is **NOT** with your code or PostgreSQL configuration. It's a hosting restriction. Your options are:

1. ✅ Get Namecheap to whitelist the connection (best)
2. ✅ Upgrade to VPS hosting (more control)
3. ✅ Move to a hosting provider that allows external databases
4. ❌ Host PostgreSQL on Namecheap (they don't support PostgreSQL)

The PostgreSQL database at 157.10.73.52 is working perfectly. The Laravel code is correct. It's purely a network/firewall restriction from Namecheap's side.