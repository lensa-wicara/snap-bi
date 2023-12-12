<?php

namespace LensaWicara\Tests;

use Illuminate\Support\Facades\Cache;
use LensaWicara\SnapBI\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array|string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
