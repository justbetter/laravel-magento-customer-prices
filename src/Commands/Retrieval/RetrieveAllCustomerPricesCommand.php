<?php

namespace JustBetter\MagentoCustomerPrices\Commands\Retrieval;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveAllCustomerPricesJob;

class RetrieveAllCustomerPricesCommand extends Command
{
    protected $signature = 'magento-customer-prices:retrieve-all {from?}';

    protected $description = 'Retrieve all customer prices, optionally filtered by date';

    public function handle(): int
    {
        /** @var ?string $from */
        $from = $this->argument('from');

        $carbon = blank($from) ? null : Carbon::parse($from);

        RetrieveAllCustomerPricesJob::dispatch($carbon);

        return static::SUCCESS;
    }
}
