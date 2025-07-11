# Debugging Guide - Tarlprathom Laravel

## 1. Laravel Logging Setup

### Configure Custom Log Channels

Edit `config/logging.php`:

```php
'channels' => [
    // ... existing channels ...
    
    'ajax' => [
        'driver' => 'daily',
        'path' => storage_path('logs/ajax.log'),
        'level' => 'debug',
        'days' => 7,
    ],
    
    'validation' => [
        'driver' => 'daily',
        'path' => storage_path('logs/validation.log'),
        'level' => 'debug',
        'days' => 7,
    ],
    
    'user_actions' => [
        'driver' => 'daily',
        'path' => storage_path('logs/user_actions.log'),
        'level' => 'info',
        'days' => 30,
    ],
],
```

### Implement Logging in Controllers

```php
// In AssessmentController.php
use Illuminate\Support\Facades\Log;

public function saveStudentAssessment(Request $request)
{
    try {
        Log::channel('ajax')->info('Assessment save requested', [
            'user_id' => auth()->id(),
            'data' => $request->all()
        ]);
        
        $validated = $request->validate([
            // validation rules
        ]);
        
        // Process assessment
        
        Log::channel('user_actions')->info('Assessment saved', [
            'user_id' => auth()->id(),
            'student_id' => $validated['student_id'],
            'assessment_id' => $assessment->id
        ]);
        
        return response()->json(['success' => true]);
        
    } catch (ValidationException $e) {
        Log::channel('validation')->warning('Assessment validation failed', [
            'user_id' => auth()->id(),
            'errors' => $e->errors()
        ]);
        throw $e;
    } catch (\Exception $e) {
        Log::channel('ajax')->error('Assessment save failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred'
        ], 500);
    }
}
```

## 2. Browser DevTools Debugging

### Network Tab Analysis

1. **Monitor AJAX Requests**
   ```javascript
   // Add to your main JS file
   $(document).ajaxSend(function(event, xhr, settings) {
       console.log('AJAX Request:', settings.url, settings.data);
   });
   
   $(document).ajaxComplete(function(event, xhr, settings) {
       console.log('AJAX Response:', xhr.status, xhr.responseJSON);
   });
   ```

2. **Check Request Headers**
   - Verify CSRF token is present
   - Check Content-Type is correct
   - Ensure Authorization header for API calls

3. **Analyze Response Data**
   - Check status codes
   - Verify JSON structure
   - Look for error messages

### Console Debugging

```javascript
// Debug helper functions
window.debugMode = true;

function debugLog(message, data) {
    if (window.debugMode) {
        console.group(message);
        console.log('Timestamp:', new Date().toISOString());
        console.log('Data:', data);
        console.trace();
        console.groupEnd();
    }
}

// Usage in AJAX calls
$.ajax({
    url: '/api/assessments/save-student',
    method: 'POST',
    data: formData,
    beforeSend: function() {
        debugLog('Saving assessment', formData);
    },
    success: function(response) {
        debugLog('Assessment saved', response);
    },
    error: function(xhr) {
        debugLog('Assessment error', {
            status: xhr.status,
            response: xhr.responseJSON,
            statusText: xhr.statusText
        });
    }
});
```

## 3. Laravel Debugging Tools

### Laravel Debugbar Installation

```bash
composer require barryvdh/laravel-debugbar --dev
```

### Custom Debug Middleware

Create `app/Http/Middleware/DebugAjaxRequests.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugAjaxRequests
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax() && config('app.debug')) {
            $startTime = microtime(true);
            
            Log::channel('ajax')->debug('AJAX Request Start', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload' => $request->all()
            ]);
            
            $response = $next($request);
            
            $duration = microtime(true) - $startTime;
            
            Log::channel('ajax')->debug('AJAX Request End', [
                'duration' => round($duration * 1000, 2) . 'ms',
                'status' => $response->getStatusCode(),
                'response_size' => strlen($response->getContent())
            ]);
            
            return $response;
        }
        
        return $next($request);
    }
}
```

Register in `app/Http/Kernel.php`:
```php
protected $middleware = [
    // ... other middleware ...
    \App\Http\Middleware\DebugAjaxRequests::class,
];
```

## 4. Error Tracking Setup

### Global JavaScript Error Handler

```javascript
// Add to your main layout
window.addEventListener('error', function(event) {
    console.error('Global error:', event.error);
    
    // Send to server for logging
    $.post('/api/log-client-error', {
        message: event.error.message,
        stack: event.error.stack,
        url: window.location.href,
        line: event.lineno,
        column: event.colno,
        userAgent: navigator.userAgent
    });
});

// jQuery AJAX global error handler
$(document).ajaxError(function(event, xhr, settings, error) {
    console.error('AJAX Error:', {
        url: settings.url,
        status: xhr.status,
        error: error,
        response: xhr.responseText
    });
    
    // Log to server
    $.post('/api/log-ajax-error', {
        url: settings.url,
        method: settings.type,
        status: xhr.status,
        error: error,
        response: xhr.responseText.substring(0, 1000) // Limit size
    });
});
```

### Server-side Error Logging Route

```php
// In routes/api.php
Route::post('/log-client-error', function (Request $request) {
    Log::channel('frontend')->error('Client-side error', $request->all());
    return response()->json(['logged' => true]);
});

Route::post('/log-ajax-error', function (Request $request) {
    Log::channel('ajax')->error('AJAX error reported by client', $request->all());
    return response()->json(['logged' => true]);
});
```

## 5. Database Query Debugging

### Enable Query Logging

```php
// In AppServiceProvider boot method
use Illuminate\Support\Facades\DB;

public function boot()
{
    if (config('app.debug')) {
        DB::listen(function ($query) {
            Log::channel('queries')->debug('SQL', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time . 'ms'
            ]);
        });
    }
}
```

### Slow Query Detection

```php
// In AppServiceProvider
DB::listen(function ($query) {
    if ($query->time > 1000) { // Queries taking more than 1 second
        Log::channel('slow_queries')->warning('Slow query detected', [
            'sql' => $query->sql,
            'time' => $query->time . 'ms',
            'connection' => $query->connectionName
        ]);
    }
});
```

## 6. Debugging Checklist

### Before Debugging
1. [ ] Clear Laravel cache: `php artisan cache:clear`
2. [ ] Clear config cache: `php artisan config:clear`
3. [ ] Check Laravel logs: `tail -f storage/logs/laravel.log`
4. [ ] Verify .env settings
5. [ ] Check file permissions

### During Debugging
1. [ ] Use browser DevTools Network tab
2. [ ] Check Console for JavaScript errors
3. [ ] Verify CSRF token in requests
4. [ ] Check Laravel logs for errors
5. [ ] Use `dd()` or `dump()` for quick debugging
6. [ ] Check database queries with Debugbar

### Common Issues and Solutions

1. **AJAX 419 Error (CSRF Token)**
   ```javascript
   // Ensure CSRF token in AJAX
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
   ```

2. **AJAX 422 Error (Validation)**
   ```javascript
   // Handle validation errors
   error: function(xhr) {
       if (xhr.status === 422) {
           const errors = xhr.responseJSON.errors;
           // Display errors to user
       }
   }
   ```

3. **500 Server Error**
   - Check Laravel logs
   - Enable debug mode temporarily
   - Check PHP error logs

## 7. Production Debugging

### Safe Production Logging

```php
// In production exception handler
public function report(Throwable $exception)
{
    if (app()->environment('production')) {
        // Log critical errors with context
        Log::critical('Production error', [
            'user_id' => auth()->id() ?? 'guest',
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'exception' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);
    }
    
    parent::report($exception);
}
```

### Monitoring Tools Integration

1. **Laravel Telescope** (for staging)
2. **Sentry** or **Bugsnag** (for production)
3. **New Relic** or **Datadog** (for performance)