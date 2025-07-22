<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\GamificationServiceInterface;
use App\Contracts\CertificateServiceInterface;
use App\Contracts\NotificationServiceInterface;
use App\Contracts\PDFGenerationServiceInterface;
use App\Contracts\ActivityTrackingServiceInterface;
use App\Contracts\EventDispatcherInterface;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\PDFGenerationService;
use App\Services\ActivityTrackingService;
use App\Services\PointsService;
use App\Services\LevelService;
use App\Services\StreakService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar EventDispatcherAdapter
        $this->app->singleton(EventDispatcherInterface::class, function ($app) {
            return new \App\Services\EventDispatcherAdapter(
                $app->make(Dispatcher::class)
            );
        });

        // Registrar NotificationService primeiro (dependência de outros serviços)
        $this->app->singleton(NotificationServiceInterface::class, function ($app) {
            return new NotificationService(
                $app->make(Dispatcher::class),
                $app->make(Mailer::class)
            );
        });

        // Registrar services especializados
        $this->app->singleton(PointsService::class, function ($app) {
            return new PointsService(
                $app->make(EventDispatcherInterface::class)
            );
        });

        $this->app->singleton(LevelService::class, function ($app) {
            return new LevelService(
                $app->make(EventDispatcherInterface::class),
                $app->make(NotificationServiceInterface::class)
            );
        });

        $this->app->singleton(StreakService::class, function ($app) {
            return new StreakService(
                $app->make(EventDispatcherInterface::class)
            );
        });

        // Registrar services principais
        $this->app->singleton(GamificationServiceInterface::class, function ($app) {
            return new GamificationService(
                $app->make(PointsService::class),
                $app->make(LevelService::class),
                $app->make(StreakService::class),
                $app->make(NotificationServiceInterface::class),
                $app->make(EventDispatcherInterface::class)
            );
        });

        $this->app->singleton(CertificateServiceInterface::class, function ($app) {
            return new CertificateService(
                $app->make(NotificationServiceInterface::class),
                $app->make(PDFGenerationServiceInterface::class)
            );
        });

        $this->app->singleton(PDFGenerationServiceInterface::class, function ($app) {
            return new PDFGenerationService();
        });

        $this->app->singleton(ActivityTrackingServiceInterface::class, function ($app) {
            return new ActivityTrackingService();
        });

        // Bind concretas para interfaces (para compatibilidade)
        $this->app->bind(GamificationService::class, GamificationServiceInterface::class);
        $this->app->bind(CertificateService::class, CertificateServiceInterface::class);
        $this->app->bind(NotificationService::class, NotificationServiceInterface::class);
        $this->app->bind(PDFGenerationService::class, PDFGenerationServiceInterface::class);
        $this->app->bind(ActivityTrackingService::class, ActivityTrackingServiceInterface::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 