<?php

declare(strict_types=1);

namespace Asseco\Gelf;

use Illuminate\Support\ServiceProvider;

class GelfServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/logging.php', 'logging.channels.gelf');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        //
    }
}
