#!/bin/bash
# Remote MySQL Fix Script

SERVER="157.10.73.52"
USER="ubuntu"
PASSWORD="en_&xdX#!N(^OqCQzc3RE0B)m6ogU!"

echo "Connecting to server and running MySQL diagnostics and fixes..."
echo "Server: $SERVER"
echo "User: $USER"
echo

# Create the remote script content
REMOTE_SCRIPT='#!/bin/bash
echo "=== MySQL Server Diagnostic and Fix Script ==="
echo

# Check MySQL service status
echo "1. Checking MySQL service status..."
sudo systemctl status mysql --no-pager
echo

# Check if MySQL is listening on port 3306
echo "2. Checking if MySQL is listening on port 3306..."
sudo netstat -tlnp | grep 3306 || echo "MySQL not listening on 3306"
echo

# Check MySQL configuration
echo "3. Checking MySQL bind-address configuration..."
sudo grep -n "bind-address" /etc/mysql/mysql.conf.d/mysqld.cnf 2>/dev/null || echo "Config file not found at standard location"
echo

# Check firewall status
echo "4. Checking firewall status..."
sudo ufw status
echo

echo "=== Attempting fixes ==="

# Start MySQL service if not running
echo "5. Starting MySQL service..."
sudo systemctl start mysql
sudo systemctl enable mysql
echo

# Configure MySQL to accept external connections
echo "6. Configuring MySQL for external connections..."
sudo sed -i "s/bind-address.*=.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf 2>/dev/null
echo

# Allow port 3306 through firewall
echo "7. Configuring firewall to allow MySQL..."
sudo ufw allow 3306
echo

# Restart MySQL to apply changes
echo "8. Restarting MySQL service..."
sudo systemctl restart mysql
echo

# Final status check
echo "9. Final verification..."
sudo systemctl status mysql --no-pager
sudo netstat -tlnp | grep 3306

echo "=== MySQL fix script completed ==="'

# Execute the script on remote server
sshpass -p "$PASSWORD" ssh -o StrictHostKeyChecking=no $USER@$SERVER "$REMOTE_SCRIPT"

echo
echo "Remote script execution completed."
echo "Testing connection from local machine..."

# Test the connection from local machine
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful!'; } catch(Exception \$e) { echo 'Connection failed: ' . \$e->getMessage(); }"