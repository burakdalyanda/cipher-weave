<?php

namespace BurakDalyanda\CipherWeave;

use Illuminate\Support\ServiceProvider;

class CipherWeaveServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/cipher.php' => config_path('cipher.php'),
            ], 'cipherweave-config');
        }
    }

    public function register(): void
    {
//        $this->app->register(CipherWeaveServiceProvider::class);

        $this->app->singleton(CipherWeave::class, function () {
            return new CipherWeave();
        });

        $this->mergeConfigFrom(__DIR__ . '/config/cipher.php', 'cipherweave');
    }
}
