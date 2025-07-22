<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ProgressRepository;
use App\Repositories\SimuladoRepository;
use App\Repositories\QuizRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        $this->app->singleton(ModuleRepository::class, function ($app) {
            return new ModuleRepository();
        });

        $this->app->singleton(NotificationRepository::class, function ($app) {
            return new NotificationRepository();
        });

        $this->app->singleton(ProgressRepository::class, function ($app) {
            return new ProgressRepository();
        });

        $this->app->singleton(SimuladoRepository::class, function ($app) {
            return new SimuladoRepository();
        });

        $this->app->singleton(QuizRepository::class, function ($app) {
            return new QuizRepository();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
