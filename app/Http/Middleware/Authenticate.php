<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Default guard is 'web' for users or 'admin' for admin users
        $guards = empty($guards) ? ['web'] : $guards;

        // If the user is authenticated for the guard, continue with the request
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request); // Continue to the next request if authenticated
            }
        }

        // If not authenticated, redirect to the login page
        return redirect()->route('admin.login');
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Redirect to login page for unauthenticated admin users
        if (!Auth::guard('admin')->check()) {
            return route('admin.login');
        }

        return null;
    }
}
