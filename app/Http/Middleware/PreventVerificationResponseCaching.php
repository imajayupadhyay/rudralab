<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventVerificationResponseCaching
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->is('verify-certificate')) {
            return $response;
        }

        $response->headers->set(
            'Cache-Control',
            'private, no-store, no-cache, must-revalidate, max-age=0'
        );
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        $response->setVary(['X-Inertia', 'Accept-Encoding']);

        return $response;
    }
}
