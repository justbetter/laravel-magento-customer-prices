<?php

namespace JustBetter\MagentoCustomerPrices\Commands;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveAllCustomerPricesJob;

class RetrieveAllCustomerPricesCommand extends Command
{
    protected $signature = 'magento:customer-price:retrieve-all';

    protected $description = 'Dispatch job to retrieve customer all specific prices';

    public function handle(): int
    {
        $this->info('Dispatching...');

        RetrieveAllCustomerPricesJob::dispatch();

        $this->info('Done!');

        return static::SUCCESS;
    }
}
