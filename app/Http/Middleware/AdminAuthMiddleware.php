<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
    protected function redirectTo(Request $request): ?string
{
    // Redirect to admin login if not authenticated
    if (!$request->expectsJson()) {
        return route('admin.login'); // Ensure this is pointing to the correct route
    }
}

}
