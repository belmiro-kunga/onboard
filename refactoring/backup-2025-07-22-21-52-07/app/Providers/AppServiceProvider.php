<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Doctrine\DBAL\Types\Type;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\NotificationService::class);
        $this->app->singleton(\App\Services\GamificationService::class);
        $this->app->singleton(\App\Services\CertificateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register enum type for Doctrine DBAL only when needed
        try {
            if (!Type::hasType('enum')) {
                Type::addType('enum', 'Doctrine\DBAL\Types\StringType');
            }
            
            // Map enum database type to string type only if connection is available
            if (app()->environment() !== 'testing') {
                $platform = \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform();
                $platform->registerDoctrineTypeMapping('enum', 'string');
            }
        } catch (\Exception $e) {
            // Silently ignore database connection issues during bootstrap
        }
    }
}
