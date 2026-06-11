<?php

declare(strict_types=1);

namespace Booni3\Linnworks\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Booni3\Linnworks\LinnworksServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LinnworksServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up Linnworks config
        $app['config']->set('linnworks.applicationId', 'test-app-id');
        $app['config']->set('linnworks.applicationSecret', 'test-app-secret');
        $app['config']->set('linnworks.token', 'test-token');
    }
}