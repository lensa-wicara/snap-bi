<?php

namespace LensaWicara\SnapBI;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LensaWicara\SnapBI\Http\SnapClient as HttpSnapClient;
use LensaWicara\SnapBI\Support\SnapClient;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        // snap client
        $this->app->bind(SnapClient::class, function ($app) {
            return new HttpSnapClient();
        });
    }

    public function register()
    {
        // merge config
        $this->mergeConfigFrom(__DIR__.'/../config/snap-bi.php', 'snap-bi');
    }
}
