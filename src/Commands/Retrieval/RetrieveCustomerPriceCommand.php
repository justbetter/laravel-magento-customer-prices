<?php

namespace JustBetter\MagentoCustomerPrices\Commands\Retrieval;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;

class RetrieveCustomerPriceCommand extends Command
{
    protected $signature = 'magento-customer-prices:retrieve {sku} {--force}';

    protected $description = 'Retrieve customer price for a specific SKU';

    public function handle(): int
    {
        /** @var string $sku */
        $sku = $this->argument('sku');

        /** @var bool $force */
        $force = $this->option('force');

        RetrieveCustomerPriceJob::dispatch($sku, $force);

        return static::SUCCESS;
    }
}
