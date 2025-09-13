<?php
/**
 * Complete Deployment Helper for FTP Users
 * 
 * USAGE: Visit https://yourdomain.com/deploy-helper.php?token=deploy2024tarl
 * 
 * This script will:
 * 1. Clear all caches
 * 2. Run pending migrations
 * 3. Rebuild caches for production
 * 
 * IMPORTANT: DELETE THIS FILE AFTER USE FOR SECURITY!
 */

// Security check
if (!isset($_GET['token']) || $_GET['token'] !== 'deploy2024tarl') {
    die('<h1>Unauthorized</h1><p>Please provide valid token in URL: <code>?token=deploy2024tarl</code></p>');
}

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Simple HTML output
?>
<!DOCTYPE html>
<html>
<head>
    <title>Deployment Helper - TaRL System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 { color: #333; margin-top: 0; }
        .btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px 5px;
        }
        .btn:hover { background: #4338ca; }
        .btn:disabled { background: #9ca3af; cursor: not-allowed; }
        .output {
            background: #1e293b;
            color: #10b981;
            padding: 15px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 13px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            margin: 20px 0;
        }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .warning {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            padding: 15px;
            border-radius: 6px;
            color: #92400e;
            margin: 20px 0;
        }
        .section {
            border-left: 4px solid #4f46e5;
            padding-left: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🚀 TaRL Deployment Helper</h1>
        <p>This tool helps you deploy changes when using FTP. Click the buttons below to perform deployment tasks.</p>
        
        <div class="section">
            <h3>Quick Actions</h3>
            <button class="btn" onclick="runAction('all')">🎯 Run Complete Deployment</button>
            <button class="btn" onclick="runAction('cache')">🧹 Clear Caches Only</button>
            <button class="btn" onclick="runAction('migrate')">📊 Run Migrations Only</button>
            <button class="btn" onclick="runAction('autoload')">📦 Dump Autoload</button>
        </div>
        
        <div id="output" class="output" style="display:none;"></div>
        
        <div class="warning">
            <strong>⚠️ Security Warning:</strong> Delete this file immediately after deployment!
        </div>
    </div>
    
    <div class="card">
        <h3>📋 Current Status</h3>
        <?php
        try {
            // Check migration status
            Artisan::call('migrate:status');
            $output = Artisan::output();
            $pendingCount = substr_count($output, 'Pending');
            $ranCount = substr_count($output, 'Ran');
            
            echo "<p>✅ <strong>Migrations:</strong> $ranCount executed, $pendingCount pending</p>";
            
            // Check if routes are cached
            $routeCached = file_exists(base_path('bootstrap/cache/routes-v7.php'));
            echo "<p>" . ($routeCached ? "✅" : "⚠️") . " <strong>Route Cache:</strong> " . ($routeCached ? "Cached" : "Not cached") . "</p>";
            
            // Check if config is cached
            $configCached = file_exists(base_path('bootstrap/cache/config.php'));
            echo "<p>" . ($configCached ? "✅" : "⚠️") . " <strong>Config Cache:</strong> " . ($configCached ? "Cached" : "Not cached") . "</p>";
            
            // Environment
            echo "<p>📍 <strong>Environment:</strong> " . app()->environment() . "</p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>Error checking status: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <script>
    function runAction(action) {
        const output = document.getElementById('output');
        output.style.display = 'block';
        output.innerHTML = '⏳ Processing...\n';
        
        // Disable all buttons
        document.querySelectorAll('.btn').forEach(btn => btn.disabled = true);
        
        fetch('deploy-helper.php?token=deploy2024tarl&action=' + action, {
            method: 'POST'
        })
        .then(response => response.text())
        .then(data => {
            output.innerHTML = data;
            // Re-enable buttons
            document.querySelectorAll('.btn').forEach(btn => btn.disabled = false);
            
            if (data.includes('Success')) {
                setTimeout(() => {
                    if (confirm('Deployment complete! Refresh page to see updated status?')) {
                        window.location.reload();
                    }
                }, 1000);
            }
        })
        .catch(error => {
            output.innerHTML += '\n❌ Error: ' + error;
            document.querySelectorAll('.btn').forEach(btn => btn.disabled = false);
        });
    }
    </script>
</body>
</html>

<?php
// Handle actions
if (isset($_GET['action']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain');
    $action = $_GET['action'];
    
    try {
        switch ($action) {
            case 'all':
                echo "🎯 COMPLETE DEPLOYMENT PROCESS\n";
                echo "=====================================\n\n";
                
                // Step 1: Clear caches
                echo "Step 1: Clearing all caches...\n";
                Artisan::call('cache:clear');
                echo "✅ Application cache cleared\n";
                
                Artisan::call('config:clear');
                echo "✅ Config cache cleared\n";
                
                Artisan::call('route:clear');
                echo "✅ Route cache cleared\n";
                
                Artisan::call('view:clear');
                echo "✅ View cache cleared\n";
                
                Artisan::call('optimize:clear');
                echo "✅ All optimization caches cleared\n\n";
                
                // Step 2: Run migrations
                echo "Step 2: Running migrations...\n";
                Artisan::call('migrate', ['--force' => true]);
                $migrationOutput = Artisan::output();
                echo $migrationOutput . "\n";
                
                // Step 3: Rebuild caches for production
                if (app()->environment('production')) {
                    echo "Step 3: Rebuilding production caches...\n";
                    
                    Artisan::call('config:cache');
                    echo "✅ Config cache rebuilt\n";
                    
                    Artisan::call('route:cache');
                    echo "✅ Route cache rebuilt\n";
                    
                    Artisan::call('view:cache');
                    echo "✅ View cache rebuilt\n";
                }
                
                echo "\n=====================================\n";
                echo "🎉 Success! Complete deployment finished!\n\n";
                echo "Next steps:\n";
                echo "1. Test the quick login at /quick-login\n";
                echo "2. Test teacher workflow at /teacher/profile/setup\n";
                echo "3. DELETE this file (deploy-helper.php) from server!\n";
                break;
                
            case 'cache':
                echo "🧹 CLEARING CACHES\n";
                echo "=====================================\n\n";
                
                Artisan::call('cache:clear');
                echo "✅ Application cache cleared\n";
                
                Artisan::call('config:clear');
                echo "✅ Config cache cleared\n";
                
                Artisan::call('route:clear');
                echo "✅ Route cache cleared\n";
                
                Artisan::call('view:clear');
                echo "✅ View cache cleared\n";
                
                Artisan::call('optimize:clear');
                echo "✅ All optimization caches cleared\n";
                
                // Rebuild for production
                if (app()->environment('production')) {
                    echo "\nRebuilding production caches...\n";
                    
                    Artisan::call('config:cache');
                    echo "✅ Config cache rebuilt\n";
                    
                    Artisan::call('route:cache');
                    echo "✅ Route cache rebuilt\n";
                    
                    Artisan::call('view:cache');
                    echo "✅ View cache rebuilt\n";
                }
                
                echo "\n🎉 Success! All caches cleared and rebuilt!\n";
                break;
                
            case 'migrate':
                echo "📊 RUNNING MIGRATIONS\n";
                echo "=====================================\n\n";
                
                Artisan::call('migrate', ['--force' => true]);
                $output = Artisan::output();
                echo $output;
                
                echo "\n🎉 Success! Migrations completed!\n";
                break;
                
            case 'autoload':
                echo "📦 DUMPING AUTOLOAD\n";
                echo "=====================================\n\n";
                
                // Run composer dump-autoload
                $composerPath = base_path('composer.phar');
                if (file_exists($composerPath)) {
                    $cmd = "cd " . base_path() . " && php composer.phar dump-autoload -o 2>&1";
                } else {
                    $cmd = "cd " . base_path() . " && composer dump-autoload -o 2>&1";
                }
                
                $output = shell_exec($cmd);
                echo $output;
                
                // Clear compiled
                Artisan::call('clear-compiled');
                echo "\n✅ Compiled classes cleared\n";
                
                // Clear cache to ensure new autoload is used
                Artisan::call('cache:clear');
                echo "✅ Application cache cleared\n";
                
                echo "\n🎉 Success! Autoload files regenerated!\n";
                echo "This is important after adding new helper files.\n";
                break;
                
            default:
                echo "❌ Unknown action: $action\n";
        }
        
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n\n";
        echo "Stack trace:\n" . $e->getTraceAsString();
    }
    
    exit;
}
?>