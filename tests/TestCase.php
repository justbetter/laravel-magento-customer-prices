<?php

namespace JustBetter\MagentoCustomerPrices\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use JustBetter\MagentoClient\Client\Magento;
use JustBetter\MagentoCustomerPrices\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Activitylog\ActivitylogServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function defineEnvironment($app): void
    {
        config()->set('database.default', 'testbench');
        config()->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        activity()->disableLogging();
        Magento::fake();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            \JustBetter\MagentoClient\ServiceProvider::class,
            \JustBetter\MagentoProducts\ServiceProvider::class,
            ActivitylogServiceProvider::class,
        ];
    }
}
