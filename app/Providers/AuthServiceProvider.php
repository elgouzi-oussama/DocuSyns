<?php

namespace App\Providers;

use App\Helpers\SystemHelper;
use App\Models\License;
use App\Models\LicensesType;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Carbon\Carbon;

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






        // Define license gates
        Gate::define('isPro', function ($user = null) {
            return $this->hasLicenseType('Pro');
        });

        Gate::define('isBasic', function ($user = null) {
            return $this->hasLicenseType('Basic');
        });

        Gate::define('isEnterprise', function ($user = null) {
            return $this->hasLicenseType('Enterprise');
        });

        Gate::define('isTrial', function ($user = null) {
            $license = License::first();
            if (!$license || $license->akaeay_ !== null) {
                return false;
            }

            $trialStartDate = Carbon::parse($license->auasae_);
            $daysUsed = $trialStartDate->diffInDays(Carbon::now());

            return $daysUsed < 30;
        });
        Gate::define('isTrialEnded', function ($user = null) {
            $license = License::first();

            if (!$license || $license->akaeay_ !== null) {
                return false;
            }

            $trialStartDate = Carbon::parse($license->auasae_);
            $daysUsed = $trialStartDate->diffInDays(Carbon::now());

            // ✅ Trial has expired
            return $daysUsed >= 30;
        });
        // 🧩 Define fallback check — if not any license active
        Gate::define('noLicense', function ($user = null) {
            return !(
                Gate::check('isPro') ||
                Gate::check('isBasic') ||
                Gate::check('isEnterprise') ||
                Gate::check('isTrial')
            );
        });
        Gate::define('isLicense', function () {
            if (Gate::check('isPro')) {
                return true;
            } elseif (Gate::check('isBasic')) {
                return true;
            } elseif (Gate::check('isEnterprise')) {
                return true;
            }
        });
    }


    private function hasLicenseType(string $typeName): bool
    {
        $serverId = SystemHelper::getServerId();
        $license = License::where('server_id', $serverId)->first();

        if (!$license || $license->akaeay_ === null) {
            return false;
        }

        $licenseType = LicensesType::where('_name', $typeName)->first();

        return $licenseType && $license->akaeay_ === $licenseType->akaeay_;
    }
}
