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




        Gate::define('permission.show', function (User $authUser, ?User $target = null) {
            // dd($authUser);
            return $authUser->hasPermission('permission.show');
        });
        Gate::define('permission.edit', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('permission.edit');
        });
        // ✅ Profile Permissions Gates 
        Gate::define('profile.show', function (User $authUser, ?User $target = null) {
            // dd($authUser);
            return $authUser->hasPermission('profile.show');
        });
        Gate::define('profile.edit', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('profile.edit');
        });
        // ✅ User Permissions Gates
        Gate::define('user.index', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('user.index');
        });
        Gate::define('user.show', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('user.show');
        });
        Gate::define('user.create', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('user.create');
        });
        Gate::define('user.edit', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('user.edit');
        });
        Gate::define('user.delete', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('user.delete');
        });
        // ✅ Invoice Permissions Gates
        Gate::define('invoice.index', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.index');
        });
        Gate::define('invoice.show', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.show');
        });
        Gate::define('invoice.approve', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.approve');
        });
        Gate::define('invoice.reject', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.reject');
        });
        Gate::define('invoice.create', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.create');
        });
        Gate::define('invoice.edit', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.edit');
        });
        Gate::define('invoice.delete', function (User $authUser, ?User $target = null) {
            return $authUser->hasPermission('invoice.delete');
        });
    }
}
