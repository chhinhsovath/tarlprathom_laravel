<?php
/**
 * PostgreSQL Connection Test for Namecheap Hosting
 * Upload this file to your Namecheap public_html or public directory
 * Access via: https://tarl.dashboardkh.com/test-postgres-connection.php
 * DELETE THIS FILE after testing for security!
 */

// Disable error reporting to screen (for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$config = [
    'host' => '157.10.73.52',
    'port' => '5432',
    'database' => 'tarl_prathom',
    'username' => 'admin',
    'password' => 'P@ssw0rd'
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>PostgreSQL Connection Test - Namecheap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2196F3;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .error {
            color: #f44336;
            font-weight: bold;
        }
        .warning {
            color: #ff9800;
            font-weight: bold;
        }
        .info {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .code {
            background: #263238;
            color: #aed581;
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }
        .recommendation {
            background: #fff3e0;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 PostgreSQL Connection Test for Namecheap Hosting</h1>
        
        <div class="info">
            <strong>Testing connection to:</strong><br>
            Host: <?php echo $config['host']; ?><br>
            Port: <?php echo $config['port']; ?><br>
            Database: <?php echo $config['database']; ?>
        </div>

        <?php
        // Test 1: PHP Version
        echo '<div class="test-section">';
        echo '<h3>Test 1: PHP Version</h3>';
        $phpVersion = phpversion();
        $phpMajor = (int)explode('.', $phpVersion)[0];
        if ($phpMajor >= 8) {
            echo '<span class="success">✓ PHP ' . $phpVersion . ' (Compatible)</span>';
        } else {
            echo '<span class="warning">⚠ PHP ' . $phpVersion . ' (Upgrade recommended to PHP 8+)</span>';
        }
        echo '</div>';

        // Test 2: PostgreSQL Extensions
        echo '<div class="test-section">';
        echo '<h3>Test 2: PostgreSQL PHP Extensions</h3>';
        $pgsqlInstalled = extension_loaded('pgsql');
        $pdoPgsqlInstalled = extension_loaded('pdo_pgsql');
        
        if ($pgsqlInstalled && $pdoPgsqlInstalled) {
            echo '<span class="success">✓ All PostgreSQL extensions installed</span><br>';
            echo 'pgsql: ' . ($pgsqlInstalled ? '✓' : '✗') . '<br>';
            echo 'pdo_pgsql: ' . ($pdoPgsqlInstalled ? '✓' : '✗');
        } else {
            echo '<span class="error">✗ Missing PostgreSQL extensions</span><br>';
            echo 'pgsql: ' . ($pgsqlInstalled ? '✓' : '✗ Missing') . '<br>';
            echo 'pdo_pgsql: ' . ($pdoPgsqlInstalled ? '✓' : '✗ Missing') . '<br>';
            echo '<div class="recommendation">';
            echo '<strong>Action Required:</strong> Contact Namecheap support and request to enable PHP PostgreSQL extensions (pgsql and pdo_pgsql)';
            echo '</div>';
        }
        echo '</div>';

        // Test 3: Network Connectivity
        echo '<div class="test-section">';
        echo '<h3>Test 3: Network Connectivity to PostgreSQL Server</h3>';
        
        $startTime = microtime(true);
        $connection = @fsockopen($config['host'], $config['port'], $errno, $errstr, 5);
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        
        if ($connection) {
            echo '<span class="success">✓ Network connection successful (' . $responseTime . 'ms)</span>';
            fclose($connection);
            $networkOk = true;
        } else {
            echo '<span class="error">✗ Cannot connect to ' . $config['host'] . ':' . $config['port'] . '</span><br>';
            echo 'Error #' . $errno . ': ' . $errstr . '<br>';
            echo '<div class="recommendation">';
            echo '<strong>This is a firewall issue!</strong><br><br>';
            echo 'Namecheap is blocking outbound connections to port 5432.<br><br>';
            echo '<strong>Solution:</strong> Contact Namecheap support with this message:<br>';
            echo '<div class="code">';
            echo 'Please whitelist outbound connections from my hosting account to:<br>';
            echo 'IP: 157.10.73.52<br>';
            echo 'Port: 5432<br>';
            echo 'Protocol: TCP<br>';
            echo 'Purpose: PostgreSQL database connection';
            echo '</div>';
            echo '</div>';
            $networkOk = false;
        }
        echo '</div>';

        // Test 4: PostgreSQL Connection (only if network is OK)
        if ($networkOk && $pdoPgsqlInstalled) {
            echo '<div class="test-section">';
            echo '<h3>Test 4: PostgreSQL Database Connection</h3>';
            
            try {
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                $pdo = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);
                
                echo '<span class="success">✓ PostgreSQL connection successful!</span><br><br>';
                
                // Get version
                $stmt = $pdo->query("SELECT version()");
                $version = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<strong>Database Info:</strong><br>';
                echo 'Version: ' . substr($version['version'], 0, 50) . '...<br>';
                
                // Get table count
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = 'public'");
                $tables = $stmt->fetch(PDO::FETCH_ASSOC);
                echo 'Tables: ' . $tables['count'] . '<br>';
                
                // Get some stats
                $stats = [];
                $tableNames = ['users', 'students', 'pilot_schools', 'assessments'];
                foreach ($tableNames as $table) {
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $stats[$table] = $result['count'];
                    } catch (Exception $e) {
                        $stats[$table] = 'N/A';
                    }
                }
                
                echo '<br><strong>Data Statistics:</strong><br>';
                foreach ($stats as $table => $count) {
                    echo ucfirst($table) . ': ' . $count . ' records<br>';
                }
                
                echo '<div class="recommendation" style="background: #c8e6c9;">';
                echo '<strong>🎉 Success!</strong> Your PostgreSQL connection is working perfectly!<br>';
                echo 'The issue has been resolved or the connection is working from this location.';
                echo '</div>';
                
            } catch (PDOException $e) {
                echo '<span class="error">✗ Database connection failed</span><br>';
                echo 'Error: ' . htmlspecialchars($e->getMessage()) . '<br>';
                echo '<div class="recommendation">';
                echo '<strong>Possible causes:</strong><br>';
                echo '• Wrong credentials (check .env file)<br>';
                echo '• Database server is down<br>';
                echo '• User permissions issue';
                echo '</div>';
            }
            echo '</div>';
        }

        // Test 5: Server Information
        echo '<div class="test-section">';
        echo '<h3>Test 5: Server Information</h3>';
        echo 'Server IP: ' . $_SERVER['SERVER_ADDR'] . '<br>';
        echo 'Server Software: ' . $_SERVER['SERVER_SOFTWARE'] . '<br>';
        echo 'Server Name: ' . $_SERVER['SERVER_NAME'] . '<br>';
        
        // Check if we're on Namecheap
        if (strpos($_SERVER['SERVER_NAME'], 'namecheap') !== false || 
            strpos(gethostname(), 'namecheap') !== false ||
            file_exists('/home/' . get_current_user() . '/.namecheap')) {
            echo '<span class="info">Detected: Namecheap Hosting</span>';
        }
        echo '</div>';

        // Summary and Recommendations
        echo '<div class="test-section" style="border-left-color: #ff9800;">';
        echo '<h3>📋 Summary & Next Steps</h3>';
        
        if (!$pgsqlInstalled || !$pdoPgsqlInstalled) {
            echo '<div class="error">❌ Missing PHP PostgreSQL extensions</div>';
            echo '→ Contact Namecheap to enable pgsql and pdo_pgsql extensions<br><br>';
        }
        
        if (!$networkOk) {
            echo '<div class="error">❌ Network connection blocked</div>';
            echo '→ Contact Namecheap to whitelist 157.10.73.52:5432<br><br>';
            
            echo '<strong>Support Ticket Template:</strong>';
            echo '<div class="code">';
            echo 'Subject: Whitelist External PostgreSQL Database Connection<br><br>';
            echo 'I need to connect to an external PostgreSQL database from my hosting account.<br>';
            echo 'Please whitelist: IP 157.10.73.52, Port 5432, Protocol TCP<br>';
            echo 'Domain: ' . $_SERVER['SERVER_NAME'] . '<br>';
            echo 'This is required for my Laravel application to function.';
            echo '</div>';
        }
        
        if ($networkOk && $pdoPgsqlInstalled) {
            echo '<div class="success">✅ Everything is working!</div>';
            echo 'Your Laravel application should now be able to connect to PostgreSQL.';
        }
        echo '</div>';
        ?>
        
        <div class="recommendation" style="background: #ffebee; margin-top: 30px;">
            <strong>🔒 Security Notice:</strong><br>
            Delete this test file immediately after testing to prevent exposing database information!<br>
            <code>rm test-postgres-connection.php</code>
        </div>
    </div>
</body>
</html>