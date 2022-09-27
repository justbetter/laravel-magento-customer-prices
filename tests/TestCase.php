<?php

namespace JustBetter\MagentoCustomerPrices\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use JustBetter\MagentoCustomerPrices\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Activitylog\ActivitylogServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function defineEnvironment($app): void
    {
        config()->set('magento.base_url', '');
        config()->set('magento.access_token', '::token::');
        config()->set('magento.timeout', 30);
        config()->set('magento.connect_timeout', 30);

        config()->set('database.default', 'testbench');
        config()->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        activity()->disableLogging();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            \JustBetter\MagentoClient\ServiceProvider::class,
            ActivitylogServiceProvider::class,
            \JustBetter\ErrorLogger\ServiceProvider::class,
        ];
    }
}
