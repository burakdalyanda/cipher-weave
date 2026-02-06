<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave;

use BurakDalyanda\CipherWeave\Console\KeyGenerateCommand;
use BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface;
use Illuminate\Support\ServiceProvider;

class CipherWeaveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/cipher.php' => config_path('cipher.php'),
            ], 'cipherweave-config');

            $this->commands([
                KeyGenerateCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/cipher.php', 'cipherweave');

        $this->app->singleton(CipherWeaveInterface::class, function ($app) {
            return new CipherWeave();
        });

        $this->app->alias(CipherWeaveInterface::class, CipherWeave::class);
        $this->app->alias(CipherWeaveInterface::class, 'cipherweave');
    }
}
