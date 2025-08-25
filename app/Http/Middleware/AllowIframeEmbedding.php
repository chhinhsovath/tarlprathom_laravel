<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowIframeEmbedding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Allow iframe embedding from specific domains
        $allowedOrigins = [
            'https://plp.moeys.gov.kh',
            'https://tarl.dashbboardkh.com'
        ];
        
        $origin = $request->headers->get('origin');
        $referer = $request->headers->get('referer');
        
        // Check if the request is from an allowed origin
        $isAllowedOrigin = false;
        foreach ($allowedOrigins as $allowedOrigin) {
            if ($origin === $allowedOrigin || str_starts_with($referer ?? '', $allowedOrigin)) {
                $isAllowedOrigin = true;
                break;
            }
        }
        
        if ($isAllowedOrigin) {
            // Remove X-Frame-Options header to allow iframe embedding
            $response->headers->remove('X-Frame-Options');
            
            // Set Content Security Policy to allow framing from specific domains
            $response->headers->set('Content-Security-Policy', "frame-ancestors 'self' https://plp.moeys.gov.kh https://tarl.dashbboardkh.com");
            
            // Set CORS headers
            $response->headers->set('Access-Control-Allow-Origin', $origin ?: '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
        } else {
            // For other requests, use default security headers
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'");
        }
        
        return $response;
    }
}