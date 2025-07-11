<?php
/**
 * Laravel Server Requirements Checker
 * Upload this file to your server to check if it meets Laravel requirements
 */

echo "<h2>Laravel Server Requirements Check</h2>";

// Check PHP Version
echo "<h3>PHP Version</h3>";
$phpVersion = phpversion();
$phpRequired = '8.2.0';
if (version_compare($phpVersion, $phpRequired, '>=')) {
    echo "✅ PHP $phpVersion (Required: >= $phpRequired)<br>";
} else {
    echo "❌ PHP $phpVersion (Required: >= $phpRequired)<br>";
}

// Check Required PHP Extensions
echo "<h3>Required PHP Extensions</h3>";
$extensions = [
    'ctype',
    'curl',
    'dom',
    'fileinfo',
    'filter',
    'hash',
    'mbstring',
    'openssl',
    'pcre',
    'pdo',
    'pdo_mysql',
    'session',
    'tokenizer',
    'xml',
];

foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext<br>";
    } else {
        echo "❌ $ext (MISSING)<br>";
    }
}

// Check Directory Permissions
echo "<h3>Directory Permissions</h3>";
$directories = [
    '../storage',
    '../storage/app',
    '../storage/framework',
    '../storage/framework/cache',
    '../storage/framework/sessions',
    '../storage/framework/views',
    '../storage/logs',
    '../bootstrap/cache',
];

foreach ($directories as $dir) {
    if (is_writable($dir)) {
        echo "✅ $dir is writable<br>";
    } else {
        echo "❌ $dir is NOT writable<br>";
    }
}

// Check .env file
echo "<h3>Environment File</h3>";
if (file_exists('../.env')) {
    echo "✅ .env file exists<br>";
    
    // Check if APP_KEY is set
    $env = file_get_contents('../.env');
    if (strpos($env, 'APP_KEY=') !== false && strpos($env, 'APP_KEY=base64:') !== false) {
        echo "✅ APP_KEY is set<br>";
    } else {
        echo "❌ APP_KEY is not set (run: php artisan key:generate)<br>";
    }
} else {
    echo "❌ .env file is missing<br>";
}

// Check vendor directory
echo "<h3>Dependencies</h3>";
if (is_dir('../vendor')) {
    echo "✅ vendor directory exists<br>";
} else {
    echo "❌ vendor directory is missing (run: composer install)<br>";
}

// Test Database Connection
echo "<h3>Database Connection</h3>";
if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    if (isset($env['DB_HOST']) && isset($env['DB_DATABASE']) && isset($env['DB_USERNAME'])) {
        try {
            $pdo = new PDO(
                "mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']};port=" . ($env['DB_PORT'] ?? 3306),
                $env['DB_USERNAME'],
                $env['DB_PASSWORD'] ?? ''
            );
            echo "✅ Database connection successful<br>";
        } catch (PDOException $e) {
            echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "❌ Database configuration missing in .env<br>";
    }
}

echo "<br><hr><br>";
echo "<strong>After fixing any issues above:</strong><br>";
echo "1. Delete this file (server-check.php) for security<br>";
echo "2. Run: composer install<br>";
echo "3. Run: php artisan key:generate<br>";
echo "4. Run: php artisan migrate<br>";
echo "5. Run: php artisan storage:link<br>";
echo "6. Run: php artisan config:cache<br>";