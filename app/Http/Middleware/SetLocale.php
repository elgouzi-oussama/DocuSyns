<?php
// app/Http/Middleware/SetLocale.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->cookie('locale', config('app.locale'));
        if (in_array($locale, config('app.supported_locales'))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
