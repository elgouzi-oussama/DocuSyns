<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserOrGuest
{
    public function handle(Request $request, Closure $next)
    {
        // If guest, allow
        if (!Auth::check()) {
            return $next($request);
        }

        // If logged in and role = 'user', allow
        if (Auth::user()->role === 'user') {
            return $next($request);
        }

        // If logged in and role = 'admin', redirect to dashboard
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }

        // Otherwise block anyone else
        abort(403, 'Accès refusé');
    }
}
