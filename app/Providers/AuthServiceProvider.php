<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ✅ Super Admin Gate
        Gate::define('isSuperAdmin', function (User $user) {
            return $user->role === 'super_admin';
        });

        // ✅ Admin Gate
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        // ✅ User Gate
        Gate::define('isUser', function (User $user) {
            return $user->role === 'user';
        });
    }
}
