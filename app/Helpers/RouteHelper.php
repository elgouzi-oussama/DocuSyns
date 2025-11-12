<?php

use Illuminate\Support\Facades\Gate;

if (!function_exists('userRoute')) {
    function userRoute(string $routeName): string
    {
        if (Gate::allows('isSuperAdmin')) {
            return "super_admin.$routeName";
        }
        return "admin.$routeName";
    }
}
