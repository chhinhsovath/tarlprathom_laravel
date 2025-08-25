<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Optimization Settings for Remote Connections
    |--------------------------------------------------------------------------
    |
    | These settings help optimize database performance when connecting to
    | a remote database server, especially across different networks.
    |
    */

    'remote_optimizations' => [
        // Enable query caching
        'enable_query_cache' => env('DB_ENABLE_QUERY_CACHE', true),

        // Cache query results for (seconds)
        'query_cache_duration' => env('DB_QUERY_CACHE_DURATION', 300),

        // Enable persistent connections
        'persistent_connections' => env('DB_PERSISTENT_CONNECTIONS', true),

        // Connection pool settings
        'pool_min' => env('DB_POOL_MIN', 2),
        'pool_max' => env('DB_POOL_MAX', 10),

        // Timeout settings (seconds)
        'connection_timeout' => env('DB_CONNECTION_TIMEOUT', 10),
        'command_timeout' => env('DB_COMMAND_TIMEOUT', 60),

        // Retry settings
        'retry_times' => env('DB_RETRY_TIMES', 3),
        'retry_delay' => env('DB_RETRY_DELAY', 100), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Optimization Rules
    |--------------------------------------------------------------------------
    */

    'query_optimizations' => [
        // Automatically add indexes for common queries
        'auto_index' => env('DB_AUTO_INDEX', false),

        // Log slow queries (milliseconds)
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000),

        // Enable query result caching for these tables
        'cacheable_tables' => [
            'users',
            'schools',
            'provinces',
            'districts',
            'communes',
            'villages',
            'translations',
            'settings',
        ],

        // Cache duration for each table (seconds)
        'table_cache_duration' => [
            'translations' => 3600,  // 1 hour
            'settings' => 1800,      // 30 minutes
            'provinces' => 86400,    // 24 hours
            'districts' => 86400,    // 24 hours
            'communes' => 86400,     // 24 hours
            'villages' => 86400,     // 24 hours
            'default' => 300,        // 5 minutes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Connection Monitoring
    |--------------------------------------------------------------------------
    */

    'monitoring' => [
        // Enable connection monitoring
        'enabled' => env('DB_MONITORING_ENABLED', true),

        // Alert when connection time exceeds (milliseconds)
        'alert_threshold' => env('DB_ALERT_THRESHOLD', 5000),

        // Log all database connections
        'log_connections' => env('DB_LOG_CONNECTIONS', false),

        // Monitor query performance
        'monitor_queries' => env('DB_MONITOR_QUERIES', true),
    ],
];
