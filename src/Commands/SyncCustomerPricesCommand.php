<?php

namespace JustBetter\MagentoCustomerPrices\Commands;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\SyncCustomerPricesJob;

class SyncCustomerPricesCommand extends Command
{
    protected $signature = 'magento:customer-price:sync';

    protected $description = 'Dispatch job to sync customer specific price';

    public function handle(): int
    {
        $this->info('Dispatching...');

        SyncCustomerPricesJob::dispatch();

        $this->info('Done!');

        return static::SUCCESS;
    }
}
