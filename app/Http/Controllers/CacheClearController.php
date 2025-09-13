<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheClearController extends Controller
{
    /**
     * Show the cache clear page
     */
    public function index(Request $request)
    {
        // Simple security: require a token in the URL
        $token = $request->query('token');
        $validToken = 'clear2024tarl'; // Change this to something secure
        
        if ($token !== $validToken) {
            abort(403, 'Unauthorized. Please provide valid token.');
        }
        
        return view('cache-clear');
    }
    
    /**
     * Clear all caches
     */
    public function clear(Request $request)
    {
        // Simple security: require a token
        $token = $request->input('token');
        $validToken = 'clear2024tarl'; // Change this to something secure
        
        if ($token !== $validToken) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $results = [];
        
        try {
            // Clear application cache
            Artisan::call('cache:clear');
            $results['cache'] = '✅ Application cache cleared';
            
            // Clear route cache
            Artisan::call('route:clear');
            $results['route'] = '✅ Route cache cleared';
            
            // Clear config cache
            Artisan::call('config:clear');
            $results['config'] = '✅ Configuration cache cleared';
            
            // Clear compiled view files
            Artisan::call('view:clear');
            $results['view'] = '✅ Compiled views cleared';
            
            // Clear compiled classes
            Artisan::call('clear-compiled');
            $results['compiled'] = '✅ Compiled classes cleared';
            
            // Optimize clear (removes all caches including bootstrap/cache files)
            Artisan::call('optimize:clear');
            $results['optimize'] = '✅ All optimization caches cleared';
            
            // Rebuild caches for production (optional - comment out if not needed)
            if (app()->environment('production')) {
                Artisan::call('config:cache');
                $results['config_rebuild'] = '✅ Configuration cache rebuilt';
                
                Artisan::call('route:cache');
                $results['route_rebuild'] = '✅ Route cache rebuilt';
                
                Artisan::call('view:cache');
                $results['view_rebuild'] = '✅ View cache rebuilt';
            }
            
            $results['status'] = 'success';
            $results['message'] = 'All caches cleared successfully!';
            
        } catch (\Exception $e) {
            $results['status'] = 'error';
            $results['message'] = 'Error clearing caches: ' . $e->getMessage();
        }
        
        return response()->json($results);
    }
}