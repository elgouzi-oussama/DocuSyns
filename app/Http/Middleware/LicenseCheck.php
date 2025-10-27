<?php
// app/Http/Middleware/LicenseCheck.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LicenseCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (Gate::allows('noLicense')) {
            return response()->json(['message' => 'License is required.'], 403);
        }
        return $next($request);
    }
}
