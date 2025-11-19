<?php

namespace App\Providers;

use App\Helpers\SystemHelper;
use App\Models\License;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);

        if (Schema::hasTable('licenses')) {
            $path = storage_path('app/license_init.flag');

            if (!file_exists($path)) {
                $serverId = SystemHelper::getServerId();
                $license = License::first();

                if (!$license) {
                    License::create([
                        'server_id' => $serverId,
                        'auasae_'   => Carbon::now(),
                    ]);
                }

                // create marker file so this block never runs again
                file_put_contents($path, 'done');
            }
        }
    }
}
