<?php

namespace LensaWicara\SnapBI;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LensaWicara\SnapBI\Contracts\Client;
use LensaWicara\SnapBI\Http\SnapClient as HttpSnapClient;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        // snap client
        $this->app->bind(Client::class, function ($app) {
            return new HttpSnapClient();
        });

        // publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/snap-bi.php' => config_path('snap-bi.php'),
            ], 'config');
        }
    }

    public function register()
    {
        // merge config
        $this->mergeConfigFrom(__DIR__.'/../config/snap-bi.php', 'snap-bi');
    }
}
