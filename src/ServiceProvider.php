<?php

namespace JustBetter\MagentoCustomerPrices;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\MagentoCustomerPrices\Actions\ProcessCustomerPrices;
use JustBetter\MagentoCustomerPrices\Actions\Retrieval\RetrieveAllCustomerPrices;
use JustBetter\MagentoCustomerPrices\Actions\Retrieval\RetrieveCustomerPrice;
use JustBetter\MagentoCustomerPrices\Actions\Retrieval\SaveCustomerPrice;
use JustBetter\MagentoCustomerPrices\Actions\Update\UpdateCustomerPrice;
use JustBetter\MagentoCustomerPrices\Commands\ProcessCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\Retrieval\RetrieveAllCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\Retrieval\RetrieveCustomerPriceCommand;
use JustBetter\MagentoCustomerPrices\Commands\Update\UpdateAllCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\Update\UpdateCustomerPriceCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this
            ->registerConfig()
            ->registerActions();
    }

    public function boot(): void
    {
        $this
            ->bootMigrations()
            ->bootConfig()
            ->bootCommands();
    }

    public function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__.'/../config/magento-customer-prices.php',
            'magento-customer-prices');

        return $this;
    }

    public function registerActions(): static
    {
        RetrieveAllCustomerPrices::bind();
        RetrieveCustomerPrice::bind();
        SaveCustomerPrice::bind();

        UpdateCustomerPrice::bind();

        ProcessCustomerPrices::bind();

        return $this;
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__.'/../config/magento-customer-prices.php' => config_path('magento-customer-prices.php'),
        ], 'config');

        return $this;
    }

    protected function bootCommands(): static
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RetrieveAllCustomerPricesCommand::class,
                RetrieveCustomerPriceCommand::class,

                UpdateAllCustomerPricesCommand::class,
                UpdateCustomerPriceCommand::class,

                ProcessCustomerPricesCommand::class,
            ]);
        }

        return $this;
    }

    protected function bootMigrations(): static
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        return $this;
    }
}
