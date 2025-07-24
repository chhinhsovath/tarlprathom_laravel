<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fix the migration repository configuration issue
        $this->app->singleton('migration.repository', function ($app) {
            $table = $app['config']['database.migrations.table'];

            return new \Illuminate\Database\Migrations\DatabaseMigrationRepository($app['db'], $table);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale from session or cookie BEFORE setting lang path
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } elseif (request()->hasCookie('locale')) {
            app()->setLocale(request()->cookie('locale'));
        }

        // Force HTTPS in production
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
