<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // Set PHP runtime timezone to match application timezone
        $tz = Config::get('app.timezone', 'UTC');
        if ($tz) {
            date_default_timezone_set($tz);
        }

        // If using MySQL, set the session time_zone to the app timezone offset
        try {
            $driver = Config::get('database.default');
            if (in_array($driver, ['mysql','mariadb'])) {
                $offset = Carbon::now($tz)->format('P'); // e.g. +07:00
                DB::statement("SET time_zone = '{$offset}'");
            }
        } catch (\Throwable $e) {
            // don't break app boot if DB not available yet
        }
    }
}
