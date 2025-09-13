<?php
/**
 * Emergency Cache Clear Script for FTP Deployment
 * 
 * USAGE: Visit https://yourdomain.com/deploy-clear-cache.php?token=deploy2024tarl
 * 
 * IMPORTANT: DELETE THIS FILE AFTER USE FOR SECURITY!
 */

// Security check
if (!isset($_GET['token']) || $_GET['token'] !== 'deploy2024tarl') {
    die('Unauthorized. Please provide valid token in URL: ?token=deploy2024tarl');
}

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Run Artisan commands
echo "<h2>🚀 Laravel Cache Clear for FTP Deployment</h2>";
echo "<pre style='background: #f4f4f4; padding: 20px; border-radius: 5px;'>";

try {
    // Clear route cache
    Artisan::call('route:clear');
    echo "✅ Route cache cleared\n";
    
    // Clear config cache
    Artisan::call('config:clear');
    echo "✅ Config cache cleared\n";
    
    // Clear view cache
    Artisan::call('view:clear');
    echo "✅ View cache cleared\n";
    
    // Clear application cache
    Artisan::call('cache:clear');
    echo "✅ Application cache cleared\n";
    
    // Clear compiled
    Artisan::call('clear-compiled');
    echo "✅ Compiled classes cleared\n";
    
    // Optimize clear
    Artisan::call('optimize:clear');
    echo "✅ All optimization caches cleared\n";
    
    echo "\n<strong>🎉 SUCCESS! All caches cleared!</strong>\n";
    echo "\n<strong>Routes now available:</strong>\n";
    
    // List routes to confirm
    Artisan::call('route:list', ['--path' => 'quick-login']);
    $output = Artisan::output();
    if (strpos($output, 'quick-login') !== false) {
        echo "✅ /quick-login route is registered!\n";
    } else {
        echo "⚠️  /quick-login route not found - check if files were uploaded correctly\n";
    }
    
    Artisan::call('route:list', ['--path' => 'cache-clear']);
    $output = Artisan::output();
    if (strpos($output, 'cache-clear') !== false) {
        echo "✅ /cache-clear route is registered!\n";
    } else {
        echo "⚠️  /cache-clear route not found - check if files were uploaded correctly\n";
    }
    
    // Rebuild caches for production
    if (app()->environment('production')) {
        echo "\n<strong>Rebuilding production caches...</strong>\n";
        
        Artisan::call('config:cache');
        echo "✅ Config cache rebuilt\n";
        
        Artisan::call('route:cache');
        echo "✅ Route cache rebuilt\n";
        
        Artisan::call('view:cache');
        echo "✅ View cache rebuilt\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<div style='background: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin-top: 20px; border-radius: 5px;'>";
echo "<strong>⚠️ SECURITY WARNING:</strong><br>";
echo "DELETE THIS FILE (deploy-clear-cache.php) immediately after use!<br>";
echo "This file should not remain on your production server.";
echo "</div>";

echo "<div style='margin-top: 20px;'>";
echo "<h3>✅ Next Steps:</h3>";
echo "<ol>";
echo "<li>Test quick login: <a href='/quick-login'>Go to Quick Login</a></li>";
echo "<li>Test cache clear page: <a href='/cache-clear?token=clear2024tarl'>Go to Cache Clear</a></li>";
echo "<li><strong>DELETE this file (deploy-clear-cache.php) from the server!</strong></li>";
echo "</ol>";
echo "</div>";
?>