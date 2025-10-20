<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('signin');
        }

        $user = Auth::user();

        // If the user's role does not match the required role
        if ($user->role !== $role) {
            // Redirect based on their real role
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('super_admin.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'user':
                    return redirect()->route('index');
                default:
                    abort(403, 'Accès refusé.');
            }
        }

        // Continue request if role is correct
        return $next($request);
    }
}
