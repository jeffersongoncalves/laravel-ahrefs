<?php

namespace JeffersonGoncalves\Ahrefs\Tests;

use JeffersonGoncalves\Ahrefs\AhrefsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            AhrefsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('ahrefs.token', 'fake-token');
    }
}
