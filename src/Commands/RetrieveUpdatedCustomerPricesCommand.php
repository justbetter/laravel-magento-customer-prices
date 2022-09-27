<?php

namespace JustBetter\MagentoCustomerPrices\Commands;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveUpdatedCustomerPricesJob;

class RetrieveUpdatedCustomerPricesCommand extends Command
{
    protected $signature = 'magento:customer-price:retrieve-updated';

    protected $description = 'Dispatch job to retrieve customer all specific prices';

    public function handle(): int
    {
        $this->info('Dispatching...');

        RetrieveUpdatedCustomerPricesJob::dispatch();

        $this->info('Done!');

        return static::SUCCESS;
    }
}
