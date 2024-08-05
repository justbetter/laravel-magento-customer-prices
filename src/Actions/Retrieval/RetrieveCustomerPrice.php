<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Retrieval;

use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\SaveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Repository\BaseRepository;

class RetrieveCustomerPrice implements RetrievesCustomerPrice
{
    public function retrieve(string $sku, bool $forceUpdate): void
    {
        $repository = BaseRepository::resolve();

        $priceData = $repository->retrieve($sku);

        if ($priceData === null) {
            CustomerPrice::query()
                ->where('sku', '=', $sku)
                ->update(['retrieve' => false]);

            return;
        }

        SaveCustomerPriceJob::dispatch($priceData, $forceUpdate);
    }

    public static function bind(): void
    {
        app()->singleton(RetrievesCustomerPrice::class, static::class);
    }
}
