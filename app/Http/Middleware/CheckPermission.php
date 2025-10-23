<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if user has the required permission
        if (auth()->user()->hasPermission($permission)) {
            return $next($request);
        } elseif (auth()->user()->role === 'super_admin') {
            return $next($request);
        }
        abort(403, 'You do not have permission to access this resource.');
    }
}
