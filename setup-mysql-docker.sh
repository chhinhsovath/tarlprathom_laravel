#!/bin/bash

echo "=== Setting up MySQL via Docker (no sudo required) ==="
echo "This will create a MySQL container that your Laravel app can connect to"
echo

# Function to run commands on server
run_remote() {
    local cmd="$1"
    local desc="$2"
    echo "Running: $desc"
    ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "$cmd"
}

echo "1. Checking Docker status..."
run_remote "docker --version" "Check Docker version"

echo "2. Stopping any existing MySQL container..."
run_remote "docker stop mysql-server 2>/dev/null || echo 'No existing container to stop'" "Stop existing MySQL container"
run_remote "docker rm mysql-server 2>/dev/null || echo 'No existing container to remove'" "Remove existing MySQL container"

echo "3. Creating MySQL data directory..."
run_remote "mkdir -p ~/mysql-data" "Create MySQL data directory"

echo "4. Starting MySQL container..."
run_remote "docker run -d \
  --name mysql-server \
  --restart unless-stopped \
  -e MYSQL_ROOT_PASSWORD=root123 \
  -e MYSQL_DATABASE=tarlprathom_laravel \
  -e MYSQL_USER=tarl \
  -e MYSQL_PASSWORD=P@ssw0rd \
  -p 3306:3306 \
  -v ~/mysql-data:/var/lib/mysql \
  mysql:8.0" "Start MySQL container"

echo "5. Waiting for MySQL to start..."
sleep 10

echo "6. Checking container status..."
run_remote "docker ps | grep mysql-server" "Check MySQL container status"

echo "7. Testing MySQL connection from server..."
sleep 5
run_remote "docker exec mysql-server mysql -u tarl -pP@ssw0rd -e 'SELECT \"MySQL Docker container working!\" as status;'" "Test MySQL connection"

echo "8. Checking if MySQL is listening on port 3306..."
run_remote "netstat -tlnp | grep 3306 || ss -tlnp | grep 3306" "Check MySQL port"

echo "=== Docker MySQL setup completed! ==="
echo "MySQL should now be accessible from your Laravel application"