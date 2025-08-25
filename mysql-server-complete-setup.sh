#!/bin/bash

# Complete MySQL Server Setup Script
echo "=== Complete MySQL Server Setup ==="
echo "This script will:"
echo "1. Check MySQL installation and status"
echo "2. Fix MySQL configuration issues"
echo "3. Set up database and user for Laravel"
echo "4. Configure firewall and networking"
echo

# Function to run commands with sudo password prompt
run_sudo_command() {
    local cmd="$1"
    local description="$2"
    echo "Running: $description"
    echo "Command: sudo $cmd"
    echo "Enter server sudo password when prompted:"
    ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "sudo $cmd"
    echo "---"
}

# Function to run regular commands
run_command() {
    local cmd="$1" 
    local description="$2"
    echo "Running: $description"
    echo "Command: $cmd"
    ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "$cmd"
    echo "---"
}

echo "1. Checking current MySQL status..."
run_command "systemctl status mysql --no-pager" "Check MySQL service status"

echo "2. Checking MySQL processes..."
run_command "ps aux | grep mysql" "Check running MySQL processes"

echo "3. Checking disk space..."
run_command "df -h /" "Check disk space"

echo "4. Removing any stuck MySQL processes..."
run_sudo_command "pkill -f mysql" "Kill any stuck MySQL processes"

echo "5. Cleaning up MySQL data directory..."
run_sudo_command "systemctl stop mysql" "Stop MySQL service"
run_sudo_command "rm -rf /var/lib/mysql/mysql.sock*" "Remove socket files"
run_sudo_command "rm -rf /var/lib/mysql/ib_logfile*" "Remove log files"

echo "6. Resetting MySQL data directory..."
run_sudo_command "mysqld --initialize-insecure --user=mysql --datadir=/var/lib/mysql" "Initialize MySQL data directory"

echo "7. Starting MySQL service..."
run_sudo_command "systemctl start mysql" "Start MySQL service"
run_sudo_command "systemctl enable mysql" "Enable MySQL service"

echo "8. Checking MySQL status after restart..."
run_command "systemctl status mysql --no-pager" "Check MySQL status"

echo "9. Configuring MySQL for external connections..."
run_sudo_command "sed -i 's/bind-address.*=.*/bind-address = 0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf" "Update bind-address"

echo "10. Configuring firewall..."
run_sudo_command "ufw allow 3306" "Allow MySQL port through firewall"

echo "11. Restarting MySQL with new configuration..."
run_sudo_command "systemctl restart mysql" "Restart MySQL"

echo "12. Setting up database and user..."
run_sudo_command "mysql -e \"CREATE DATABASE IF NOT EXISTS tarlprathom_laravel;\"" "Create database"
run_sudo_command "mysql -e \"CREATE USER IF NOT EXISTS 'tarl'@'%' IDENTIFIED BY 'P@ssw0rd';\"" "Create user"
run_sudo_command "mysql -e \"GRANT ALL PRIVILEGES ON tarlprathom_laravel.* TO 'tarl'@'%';\"" "Grant privileges"
run_sudo_command "mysql -e \"FLUSH PRIVILEGES;\"" "Flush privileges"

echo "13. Final verification..."
run_command "systemctl status mysql --no-pager" "Final MySQL status"
run_command "netstat -tlnp | grep 3306" "Check MySQL listening port"

echo "14. Testing database connection..."
run_command "mysql -u tarl -pP@ssw0rd -e \"SELECT 'Database connection successful!' as status;\"" "Test database connection"

echo "=== Setup completed! ==="
echo "Now testing from local machine..."