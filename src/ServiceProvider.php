<?php

namespace JustBetter\MagentoCustomerPrices;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\MagentoCustomerPrices\Actions\DeterminePricesModified;
use JustBetter\MagentoCustomerPrices\Actions\ProcessRetrievedPrice;
use JustBetter\MagentoCustomerPrices\Actions\RetrieveAllCustomerPriceSkus;
use JustBetter\MagentoCustomerPrices\Actions\RetrieveCustomerPrice;
use JustBetter\MagentoCustomerPrices\Actions\RetrieveUpdatedPriceSkus;
use JustBetter\MagentoCustomerPrices\Actions\RunCustomerPriceSync;
use JustBetter\MagentoCustomerPrices\Actions\UpdateCustomerPrices;
use JustBetter\MagentoCustomerPrices\Actions\UpdatePrices;
use JustBetter\MagentoCustomerPrices\Commands\RetrieveAllCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\RetrieveCustomerPriceCommand;
use JustBetter\MagentoCustomerPrices\Commands\RetrieveUpdatedCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\SyncCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\UpdateCustomerPriceCommand;

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
        RetrieveAllCustomerPriceSkus::bind();
        RetrieveCustomerPrice::bind();
        RetrieveUpdatedPriceSkus::bind();

        ProcessRetrievedPrice::bind();
        DeterminePricesModified::bind();
        RunCustomerPriceSync::bind();

        UpdatePrices::bind();
        UpdateCustomerPrices::bind();

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
                RetrieveUpdatedCustomerPricesCommand::class,
                SyncCustomerPricesCommand::class,
                UpdateCustomerPriceCommand::class,
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
