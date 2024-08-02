<?php

namespace JustBetter\MagentoCustomerPrices\Commands\Update;

use Illuminate\Console\Command;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

class UpdateCustomerPriceCommand extends Command
{
    protected $signature = 'magento-customer-prices:update {sku}';

    protected $description = 'Dispatch job to update a customer price in Magento';

    public function handle(): int
    {
        /** @var string $sku */
        $sku = $this->argument('sku');

        /** @var CustomerPrice $price */
        $price = CustomerPrice::query()
            ->where('sku', '=', $sku)
            ->firstOrFail();

        UpdateCustomerPriceJob::dispatch($price);

        return static::SUCCESS;
    }
}
