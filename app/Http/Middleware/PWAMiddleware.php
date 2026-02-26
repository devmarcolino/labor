<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PWAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add headers for PWA
        $response->header('Service-Worker-Allowed', '/');
        $response->header('Cache-Control', 'public, max-age=3600');

        // Add specific headers for manifest
        if ($request->is('manifest.webmanifest')) {
            $response->header('Content-Type', 'application/manifest+json; charset=utf-8');
            $response->header('Cache-Control', 'public, max-age=3600');
        }

        // Add headers for service worker
        if ($request->is('sw.js')) {
            $response->header('Content-Type', 'application/javascript; charset=utf-8');
            $response->header('Service-Worker-Allowed', '/');
            $response->header('Cache-Control', 'public, max-age=3600');
        }

        return $response;
    }
}
