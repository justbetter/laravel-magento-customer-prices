<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use JustBetter\MagentoCustomerPrices\Contracts\RunsCustomerPriceSync;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;

class RunCustomerPriceSync implements RunsCustomerPriceSync
{
    public function sync(): void
    {
        MagentoCustomerPrice::where('state', MagentoCustomerPrice::STATE_RETRIEVE)
            ->select(['sku'])
            ->each(fn (MagentoCustomerPrice $price) => RetrieveCustomerPriceJob::dispatch($price->sku));

        MagentoCustomerPrice::where('state', MagentoCustomerPrice::STATE_UPDATE)
            ->select(['sku'])
            ->each(fn (MagentoCustomerPrice $price) => UpdateCustomerPriceJob::dispatch($price->sku));
    }

    public static function bind(): void
    {
        app()->singleton(RunsCustomerPriceSync::class, static::class);
    }
}
