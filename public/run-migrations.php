<?php
/**
 * Migration Runner for FTP Deployment
 * 
 * USAGE: Visit https://yourdomain.com/run-migrations.php?token=migrate2024tarl
 * 
 * IMPORTANT: DELETE THIS FILE AFTER USE FOR SECURITY!
 */

// Security check
if (!isset($_GET['token']) || $_GET['token'] !== 'migrate2024tarl') {
    die('Unauthorized. Please provide valid token in URL: ?token=migrate2024tarl');
}

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Migration Runner - TaRL System</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        h1 {
            color: #1a202c;
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .icon {
            width: 32px;
            height: 32px;
        }
        .status-box {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .migration-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin: 8px 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .migration-name {
            flex: 1;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        .migration-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-ran {
            background: #d1fae5;
            color: #065f46;
        }
        .status-running {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }
        .btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        .output {
            background: #1a202c;
            color: #10b981;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
        }
        .warning {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            display: flex;
            align-items: start;
            gap: 12px;
        }
        .warning-icon {
            color: #f59e0b;
            flex-shrink: 0;
        }
        .success {
            background: #d1fae5;
            border: 1px solid #34d399;
            color: #065f46;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .error {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .spinner {
            border: 3px solid #e5e7eb;
            border-top: 3px solid #4f46e5;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
            </svg>
            Database Migration Runner
        </h1>
        
        <div class="status-box">
            <h3>📊 Migration Status</h3>
            <?php
            try {
                // Get migration status
                Artisan::call('migrate:status');
                $output = Artisan::output();
                
                // Parse the output to show pretty status
                $lines = explode("\n", $output);
                $pendingCount = 0;
                $ranCount = 0;
                
                foreach ($lines as $line) {
                    if (strpos($line, 'Pending') !== false) {
                        $pendingCount++;
                        $name = trim(substr($line, 0, strpos($line, '...')));
                        if ($name) {
                            echo '<div class="migration-item">';
                            echo '<span class="migration-name">' . htmlspecialchars($name) . '</span>';
                            echo '<span class="migration-status status-pending">Pending</span>';
                            echo '</div>';
                        }
                    } elseif (strpos($line, 'Ran') !== false) {
                        $ranCount++;
                    }
                }
                
                if ($pendingCount === 0) {
                    echo '<div class="success">✅ All migrations are up to date! Total: ' . $ranCount . ' migrations</div>';
                } else {
                    echo '<div style="margin-top: 20px; color: #4b5563;">';
                    echo '📈 <strong>Summary:</strong> ' . $pendingCount . ' pending, ' . $ranCount . ' already ran';
                    echo '</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error">❌ Error checking migration status: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
        
        <?php if ($pendingCount > 0): ?>
        <div style="text-align: center; margin: 30px 0;">
            <button id="runBtn" class="btn" onclick="runMigrations()">
                🚀 Run Pending Migrations
            </button>
        </div>
        <?php endif; ?>
        
        <div id="output" style="display: none;">
            <h3>📝 Migration Output</h3>
            <div class="output" id="outputContent"></div>
        </div>
        
        <div id="result"></div>
        
        <div class="warning">
            <svg class="warning-icon" width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <strong>⚠️ Security Warning:</strong><br>
                DELETE this file (run-migrations.php) immediately after use!<br>
                This file provides direct database access and should not remain on production.
            </div>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <h4>📌 After Running Migrations:</h4>
            <ol style="color: #4b5563; line-height: 1.8;">
                <li>Verify your application works correctly</li>
                <li>Test the new teacher workflow at <code>/teacher/profile/setup</code></li>
                <li><strong style="color: #dc2626;">DELETE this file from the server!</strong></li>
            </ol>
        </div>
    </div>
    
    <script>
    function runMigrations() {
        const btn = document.getElementById('runBtn');
        const outputDiv = document.getElementById('output');
        const outputContent = document.getElementById('outputContent');
        const resultDiv = document.getElementById('result');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Running migrations...';
        outputDiv.style.display = 'block';
        outputContent.innerHTML = 'Starting migration process...\n';
        
        // Make AJAX request to run migrations
        fetch('run-migrations.php?action=migrate&token=migrate2024tarl', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.text())
        .then(data => {
            outputContent.innerHTML = data;
            btn.innerHTML = '✅ Migrations Complete';
            
            // Reload page after 3 seconds to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        })
        .catch(error => {
            outputContent.innerHTML += '\n❌ Error: ' + error;
            btn.innerHTML = '❌ Migration Failed';
            btn.disabled = false;
        });
    }
    </script>
</body>
</html>

<?php
// Handle migration execution
if (isset($_GET['action']) && $_GET['action'] === 'migrate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain');
    
    try {
        echo "🚀 Starting database migrations...\n";
        echo "=====================================\n\n";
        
        // Run migrations
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        
        // Format output
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            if (strpos($line, 'DONE') !== false) {
                echo "✅ " . $line . "\n";
            } elseif (strpos($line, 'FAIL') !== false) {
                echo "❌ " . $line . "\n";
            } elseif (strpos($line, 'INFO') !== false) {
                echo "ℹ️ " . $line . "\n";
            } else {
                echo $line . "\n";
            }
        }
        
        echo "\n=====================================\n";
        echo "✅ Migration process completed!\n\n";
        
        // Clear caches after migration
        echo "🧹 Clearing caches...\n";
        Artisan::call('cache:clear');
        echo "✅ Cache cleared\n";
        
        Artisan::call('config:clear');
        echo "✅ Config cache cleared\n";
        
        Artisan::call('view:clear');
        echo "✅ View cache cleared\n";
        
        echo "\n🎉 All operations completed successfully!";
        
    } catch (Exception $e) {
        echo "❌ Migration Error: " . $e->getMessage() . "\n";
        echo "\nStack trace:\n" . $e->getTraceAsString();
    }
    
    exit;
}
?>