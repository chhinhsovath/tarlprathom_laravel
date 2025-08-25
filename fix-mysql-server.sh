#!/bin/bash
# MySQL Server Diagnostic and Fix Script
# Run this on the server: ssh ubuntu@157.10.73.52

echo "=== MySQL Server Diagnostic Script ==="
echo

# Check MySQL service status
echo "1. Checking MySQL service status..."
sudo systemctl status mysql --no-pager
echo

# Check if MySQL is listening on port 3306
echo "2. Checking if MySQL is listening on port 3306..."
sudo netstat -tlnp | grep 3306
echo

# Check MySQL configuration
echo "3. Checking MySQL bind-address configuration..."
sudo grep -n "bind-address" /etc/mysql/mysql.conf.d/mysqld.cnf 2>/dev/null || echo "Config file not found at standard location"
echo

# Check firewall status
echo "4. Checking firewall status..."
sudo ufw status
echo

# Check MySQL error logs
echo "5. Checking MySQL error logs (last 10 lines)..."
sudo tail -10 /var/log/mysql/error.log 2>/dev/null || echo "Error log not found"
echo

echo "=== Attempting fixes ==="

# Start MySQL service if not running
echo "6. Starting MySQL service..."
sudo systemctl start mysql
sudo systemctl enable mysql
echo

# Configure MySQL to accept external connections
echo "7. Configuring MySQL for external connections..."
sudo sed -i 's/bind-address.*=.*/bind-address = 0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf 2>/dev/null

# Allow port 3306 through firewall
echo "8. Configuring firewall to allow MySQL..."
sudo ufw allow 3306
echo

# Restart MySQL to apply changes
echo "9. Restarting MySQL service..."
sudo systemctl restart mysql
echo

# Final status check
echo "10. Final MySQL service status..."
sudo systemctl status mysql --no-pager
echo

echo "11. Verifying MySQL is listening on all interfaces..."
sudo netstat -tlnp | grep 3306
echo

# Test local MySQL connection
echo "12. Testing local MySQL connection..."
mysql -u tarl -pP@ssw0rd -e "SELECT 'Connection successful' as status;" 2>/dev/null || echo "Local connection failed - check user privileges"

echo
echo "=== Script completed ==="
echo "If issues persist, check:"
echo "- MySQL user 'tarl' exists and has proper permissions"
echo "- Database 'tarlprathom_laravel' exists" 
echo "- Password 'P@ssw0rd' is correct"