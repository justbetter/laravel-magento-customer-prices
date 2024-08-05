<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Foundation\Bus\PendingDispatch;
use JustBetter\MagentoCustomerPrices\Contracts\ProcessesCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Repository\BaseRepository;

class ProcessCustomerPrices implements ProcessesCustomerPrices
{
    public function process(): void
    {
        $repository = BaseRepository::resolve();

        CustomerPrice::query()
            ->where('sync', '=', true)
            ->where('retrieve', '=', true)
            ->select(['sku'])
            ->take($repository->retrieveLimit())
            ->each(fn (CustomerPrice $price): PendingDispatch => RetrieveCustomerPriceJob::dispatch($price->sku));

        CustomerPrice::query()
            ->where('sync', '=', true)
            ->where('update', '=', true)
            ->select(['id', 'sku'])
            ->take($repository->updateLimit())
            ->get()
            ->each(fn (CustomerPrice $price): PendingDispatch => UpdateCustomerPriceJob::dispatch($price));
    }

    public static function bind(): void
    {
        app()->singleton(ProcessesCustomerPrices::class, static::class);
    }
}
