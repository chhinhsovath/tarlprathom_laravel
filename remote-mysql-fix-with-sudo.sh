#!/bin/bash
# Remote MySQL Fix Script with sudo password

SERVER="157.10.73.52"
USER="ubuntu"
SSH_PASSWORD="en_&xdX#!N(^OqCQzc3RE0B)m6ogU!"

echo "Connecting to server and running MySQL diagnostics and fixes..."
echo "Server: $SERVER"
echo "User: $USER"
echo

# Create the remote script that handles sudo password
REMOTE_SCRIPT='#!/bin/bash
echo "=== MySQL Server Diagnostic and Fix Script ==="
echo

# First check what we have
echo "1. Checking if MySQL packages are installed..."
dpkg -l | grep mysql || echo "MySQL packages not found"
echo

echo "2. Checking if MariaDB packages are installed..."
dpkg -l | grep mariadb || echo "MariaDB packages not found"
echo

echo "3. Installing MySQL server if not present..."
export DEBIAN_FRONTEND=noninteractive
sudo apt update
sudo apt install -y mysql-server
echo

echo "4. Starting MySQL service..."
sudo systemctl start mysql
sudo systemctl enable mysql
echo

echo "5. Checking MySQL service status..."
sudo systemctl status mysql --no-pager
echo

echo "6. Checking if MySQL is listening..."
sudo netstat -tlnp | grep 3306 || echo "MySQL not listening on 3306"
echo

echo "7. Configuring MySQL for external connections..."
sudo sed -i "s/bind-address.*=.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf 2>/dev/null
echo

echo "8. Configuring firewall..."
sudo ufw allow 3306
echo

echo "9. Restarting MySQL..."
sudo systemctl restart mysql
echo

echo "10. Creating database and user..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS tarlprathom_laravel;"
sudo mysql -e "CREATE USER IF NOT EXISTS '\''tarl'\''@'\''%'\'' IDENTIFIED BY '\''P@ssw0rd'\'';"
sudo mysql -e "GRANT ALL PRIVILEGES ON tarlprathom_laravel.* TO '\''tarl'\''@'\''%'\'';"
sudo mysql -e "FLUSH PRIVILEGES;"
echo

echo "11. Final verification..."
sudo systemctl status mysql --no-pager
sudo netstat -tlnp | grep 3306

echo "=== MySQL setup completed ==="'

# Execute the script on remote server
sshpass -p "$SSH_PASSWORD" ssh -o StrictHostKeyChecking=no $USER@$SERVER "$REMOTE_SCRIPT"

echo
echo "Remote script execution completed."
echo "Waiting 5 seconds for MySQL to fully start..."
sleep 5

echo "Testing connection from local machine..."