<?php
echo "<h2>PHP Version Information</h2>";
echo "<p><strong>Current PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Required PHP Version:</strong> >= 8.2.0</p>";

if (version_compare(phpversion(), '8.2.0', '>=')) {
    echo "<p style='color: green;'>✅ PHP version is compatible with Laravel 11</p>";
} else {
    echo "<p style='color: red;'>❌ PHP version is too old for Laravel 11. Please upgrade to PHP 8.2 or higher.</p>";
}

echo "<hr>";
echo "<h3>PHP Configuration:</h3>";
echo "<p><strong>PHP Binary:</strong> " . PHP_BINARY . "</p>";
echo "<p><strong>PHP Config File:</strong> " . php_ini_loaded_file() . "</p>";
echo "<p><strong>Server API:</strong> " . php_sapi_name() . "</p>";