#!/bin/bash

echo "=== Server Disk Cleanup Script ==="
echo "Current disk usage:"
ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "df -h /"
echo

echo "WARNING: This script will clean up large files to free disk space."
echo "Files that will be cleaned:"
echo "- Old system logs"
echo "- Temporary files"  
echo "- Package cache"
echo

read -p "Do you want to continue? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Cleanup cancelled."
    exit 1
fi

echo "Starting cleanup..."

# Function to run sudo commands
run_sudo() {
    local cmd="$1"
    local desc="$2"
    echo "Running: $desc"
    ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "sudo $cmd"
}

# Clean package cache
echo "1. Cleaning package cache..."
run_sudo "apt-get clean" "Clean apt package cache"
run_sudo "apt-get autoremove -y" "Remove unused packages"

# Clean logs (keep recent ones)
echo "2. Cleaning old log files..."
run_sudo "journalctl --vacuum-time=7d" "Clean systemd journal older than 7 days"
run_sudo "find /var/log -type f -name '*.log.*' -mtime +7 -delete" "Remove old rotated logs"
run_sudo "truncate -s 0 /var/log/btmp" "Clear btmp log"
run_sudo "truncate -s 0 /var/log/btmp.1" "Clear btmp.1 log"

# Clean temporary files
echo "3. Cleaning temporary files..."
run_sudo "rm -rf /tmp/*" "Clean /tmp directory"
run_sudo "rm -rf /var/tmp/*" "Clean /var/tmp directory"

# Clean old kernels (keep current)
echo "4. Cleaning old kernels..."
run_sudo "apt-get autoremove --purge -y" "Remove old kernels"

# Check the CSV directory - if it's not actively being used, we might archive it
echo "5. Checking /var/csv directory (1.2GB)..."
ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "ls -la /var/csv/mentor_api/ | head -10"

echo
echo "Current disk usage after cleanup:"
ssh -i ~/.ssh/server_key ubuntu@157.10.73.52 "df -h /"

echo
echo "If more space is needed, consider:"
echo "- Moving /var/csv/mentor_api to external storage"
echo "- Compressing old files in /var/csv/"
echo "- Adding more disk space to the server"