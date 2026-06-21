<?php

namespace Gopay\GopayUi;

use Exception;
use Gopay\GopayUi\Console\InstallCommand;
use Gopay\GopayUi\Console\ProcessPendingPaymentsCommand;
use Gopay\GopayUi\Console\UninstallCommand;
use Illuminate\Support\ServiceProvider;

class GopayUiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/gopay.php', 'gopay');

        $this->app->singleton('gopayui', function ($app) {
            return new GopayUI();
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                UninstallCommand::class,
                ProcessPendingPaymentsCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gopay');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        // $this->publishes([
        //     __DIR__ . '/../config/gopay.php' => config_path('gopay.php'),
        // ], 'gopay-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/gopay'),
        ], 'gopay-views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'gopay-migrations');

        $this->app->singleton('gopay-ui-path', function () {
            return dirname(__DIR__);
        });

        $env = config('gopay.environment');
        if (!in_array($env, ['sandbox', 'production'], true)) {
            throw new Exception(
                "Invalid GOPAY_ENV value '{$env}'. Allowed values: sandbox, production"
            );
        }
    }
}
