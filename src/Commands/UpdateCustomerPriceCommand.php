<?php

namespace JustBetter\MagentoCustomerPrices\Commands;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\UpdateCustomerPriceJob;

class UpdateCustomerPriceCommand extends Command
{
    protected $signature = 'magento:customer-price:update {sku}';

    protected $description = 'Dispatch job to update customer specific price';

    public function handle(): int
    {
        $this->info('Dispatching...');

        /** @var string $sku */
        $sku = $this->argument('sku');

        UpdateCustomerPriceJob::dispatch($sku);

        $this->info('Done!');

        return static::SUCCESS;
    }
}
