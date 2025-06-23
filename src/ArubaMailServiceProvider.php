<?php

namespace Regoldidealista\ArubaPecMailer;

use Illuminate\Support\ServiceProvider;
use Regoldidealista\ArubaPecMailer\Contracts\ArubaPecMailerInterface;
use Regoldidealista\ArubaPecMailer\Mail\ArubaPecMailer;

class ArubaMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/aruba-pec.php', 'aruba-pec');
        $this->app->singleton(ArubaPecMailerInterface::class, ArubaPecMailer::class);
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/config/aruba-pec.php' => config_path('aruba-pec.php'),], 'aruba-mail-config');
    }
}