<?php

namespace App\Providers;

use App\Helpers\SystemHelper;
use App\Models\License;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Prevent errors BEFORE migrations run
        if (! app()->runningInConsole() || (app()->runningInConsole() && $this->isMigrating())) {
            return;
        }

        // Run this code ONLY if the table exists
        if (Schema::hasTable('licenses')) {
            $serverId = SystemHelper::getServerId();
            $license = License::first();

            if (! $license) {
                License::create([
                    'server_id' => $serverId,
                    'auasae_'   => Carbon::now(),
                ]);
            }
        }


        Schema::defaultStringLength(191);
    }





    private function isMigrating()
    {
        $argv = $_SERVER['argv'] ?? [];
        return in_array('migrate', $argv) || in_array('migrate:fresh', $argv);
    }
}
