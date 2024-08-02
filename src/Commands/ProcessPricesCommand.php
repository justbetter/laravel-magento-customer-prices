<?php

namespace JustBetter\MagentoCustomerPrices\Commands;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\ProcessCustomerPricesJob;

class ProcessPricesCommand extends Command
{
    protected $signature = 'magento-customer-prices:process';

    protected $description = 'Process customer prices that have the retrieve and update flags set';

    public function handle(): int
    {
        ProcessCustomerPricesJob::dispatch();

        return static::SUCCESS;
    }
}
