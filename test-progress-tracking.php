#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/', 'GET')
);

// Authenticate as the test user
$user = \App\Models\User::where('username', 'koe.kimsou.bat')->first();
if ($user) {
    Auth::login($user);
    
    // Create a mock request
    $request = new Illuminate\Http\Request();
    
    // Get the controller
    $controller = new \App\Http\Controllers\ReportController();
    
    // Test the progressTracking method
    try {
        $response = $controller->progressTracking($request);
        echo "✅ progressTracking method executed successfully\n";
        
        // Get the view data
        $data = $response->getData();
        
        echo "\nView variables passed:\n";
        foreach ($data as $key => $value) {
            $type = gettype($value);
            if (is_object($value)) {
                $type = get_class($value);
            }
            echo "  - \$$key (" . $type . ")\n";
        }
        
        // Check view exists
        $viewName = $response->getName();
        echo "\nView name: " . $viewName . "\n";
        
        if (!view()->exists($viewName)) {
            echo "❌ View file does not exist: " . $viewName . "\n";
        } else {
            echo "✅ View file exists\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ User not found\n";
}