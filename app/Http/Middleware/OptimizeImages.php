<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class OptimizeImages
{
    /**
     * Handle an incoming request for image optimization based on connection speed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only process image requests
        if ($this->isImageRequest($request)) {
            return $this->optimizeImageResponse($request, $response);
        }
        
        return $response;
    }
    
    /**
     * Check if the request is for an image
     */
    private function isImageRequest(Request $request): bool
    {
        $path = $request->path();
        return preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $path);
    }
    
    /**
     * Optimize image based on connection speed and device capabilities
     */
    private function optimizeImageResponse(Request $request, Response $response): Response
    {
        $path = $request->path();
        $fullPath = public_path($path);
        
        if (!file_exists($fullPath)) {
            return $response;
        }
        
        // Detect connection speed from headers or query parameters
        $connectionSpeed = $this->detectConnectionSpeed($request);
        $isMobile = $this->isMobileDevice($request);
        
        // Generate cache key
        $cacheKey = md5($path . $connectionSpeed . ($isMobile ? 'mobile' : 'desktop'));
        $cachePath = storage_path('app/image-cache/' . $cacheKey . '.webp');
        
        // Return cached version if exists
        if (file_exists($cachePath)) {
            return response()->file($cachePath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000',
                'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
            ]);
        }
        
        // Create optimized version
        try {
            $optimizedImage = $this->createOptimizedImage($fullPath, $connectionSpeed, $isMobile);
            
            // Ensure cache directory exists
            $cacheDir = dirname($cachePath);
            if (!file_exists($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
            
            // Save optimized image
            $optimizedImage->save($cachePath, 85, 'webp');
            
            return response()->file($cachePath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000',
                'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
            ]);
            
        } catch (\Exception $e) {
            // Return original file if optimization fails
            \Log::warning('Image optimization failed: ' . $e->getMessage());
            return $response;
        }
    }
    
    /**
     * Detect connection speed from various indicators
     */
    private function detectConnectionSpeed(Request $request): string
    {
        // Check for explicit connection speed parameter
        if ($request->has('speed')) {
            return $request->get('speed');
        }
        
        // Check Save-Data header (Chrome data saver)
        if ($request->header('Save-Data') === 'on') {
            return 'slow';
        }
        
        // Check Network-Information API headers (if available)
        $networkInfo = $request->header('Network-Information');
        if ($networkInfo) {
            if (strpos($networkInfo, '2g') !== false || strpos($networkInfo, 'slow-2g') !== false) {
                return 'slow';
            }
            if (strpos($networkInfo, '3g') !== false) {
                return 'medium';
            }
        }
        
        // Check for mobile user agents as proxy for potentially slower connections
        if ($this->isMobileDevice($request)) {
            return 'medium';
        }
        
        // Default to fast for desktop
        return 'fast';
    }
    
    /**
     * Check if the request comes from a mobile device
     */
    private function isMobileDevice(Request $request): bool
    {
        $userAgent = $request->header('User-Agent');
        return preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent);
    }
    
    /**
     * Create optimized image based on connection speed and device
     */
    private function createOptimizedImage(string $imagePath, string $speed, bool $isMobile)
    {
        $image = Image::make($imagePath);
        
        // Get original dimensions
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        
        // Define optimization settings based on connection speed
        $settings = [
            'slow' => [
                'maxWidth' => $isMobile ? 400 : 800,
                'maxHeight' => $isMobile ? 400 : 600,
                'quality' => 60,
            ],
            'medium' => [
                'maxWidth' => $isMobile ? 600 : 1200,
                'maxHeight' => $isMobile ? 600 : 900,
                'quality' => 75,
            ],
            'fast' => [
                'maxWidth' => $isMobile ? 800 : 1600,
                'maxHeight' => $isMobile ? 800 : 1200,
                'quality' => 85,
            ],
        ];
        
        $config = $settings[$speed] ?? $settings['medium'];
        
        // Resize if necessary
        if ($originalWidth > $config['maxWidth'] || $originalHeight > $config['maxHeight']) {
            $image->resize($config['maxWidth'], $config['maxHeight'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Prevent upscaling
            });
        }
        
        return $image;
    }
}
