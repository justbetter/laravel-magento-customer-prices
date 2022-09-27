<?php

namespace JustBetter\MagentoCustomerPrices\Commands;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;

class RetrieveCustomerPriceCommand extends Command
{
    protected $signature = 'magento:customer-price:retrieve {sku}';

    protected $description = 'Dispatch job to retrieve customer specific price';

    public function handle(): int
    {
        $this->info('Dispatching...');

        /** @var string $sku */
        $sku = $this->argument('sku');

        RetrieveCustomerPriceJob::dispatch($sku);

        $this->info('Done!');

        return static::SUCCESS;
    }
}
