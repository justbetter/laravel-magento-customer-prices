<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\PendingDispatch;
use JustBetter\MagentoCustomerPrices\Contracts\ProcessesCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPricesAsyncJob;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Repository\BaseRepository;
use JustBetter\MagentoPrices\Models\Price;

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

        if (config('magento-prices.async')) {
            $prices = CustomerPrice::query()
                ->where('sync', '=', true)
                ->where('update', '=', true)
                ->whereHas('product', function (Builder $query): void {
                    $query->where('exists_in_magento', '=', true);
                })
                ->select(['id', 'sku'])
                ->take($repository->updateLimit())
                ->get();

            UpdateCustomerPricesAsyncJob::dispatch($prices);
        } else {
            CustomerPrice::query()
                ->where('sync', '=', true)
                ->where('update', '=', true)
                ->select(['id', 'sku'])
                ->take($repository->updateLimit())
                ->get()
                ->each(fn (Price $price): PendingDispatch => UpdateCustomerPriceJob::dispatch($price));
        }
    }

    public static function bind(): void
    {
        app()->singleton(ProcessesCustomerPrices::class, static::class);
    }
}
