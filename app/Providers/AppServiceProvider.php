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

        if ($this->app->runningInConsole()) {
            return;
        }
        if (!Schema::hasTable('licenses')) {
            return;
        }

        if (Schema::hasTable('licenses')) {
            $serverId = SystemHelper::getServerId();
            $license = License::first();
            if (! $license) {
                $license = License::create([
                    'server_id' => $serverId,
                    'auasae_'   => Carbon::now(),
                ]);
            }
        }


        Schema::defaultStringLength(191);
    }
}
