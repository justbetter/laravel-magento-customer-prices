<?php

namespace JustBetter\MagentoCustomerPrices\Commands\Update;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\PendingDispatch;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

class UpdateAllCustomerPricesCommand extends Command
{
    protected $signature = 'magento-customer-prices:update-all';

    protected $description = 'Update all customer prices to Magento';

    public function handle(): int
    {
        CustomerPrice::query()
            ->whereHas('product', function (Builder $query): void {
                $query->where('exists_in_magento', '=', true);
            })
            ->get()
            ->each(fn (CustomerPrice $customerPrice): PendingDispatch => UpdateCustomerPriceJob::dispatch($customerPrice));

        return static::SUCCESS;
    }
}
