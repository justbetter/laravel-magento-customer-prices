<?php

namespace JustBetter\MagentoCustomerPrices\Repository;

use Illuminate\Support\Carbon;
use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Exceptions\NotImplementedException;
use JustBetter\MagentoProducts\Models\MagentoProduct;

class Repository extends BaseRepository
{
    public function retrieve(string $sku): ?CustomerPriceData
    {
        throw new NotImplementedException;
    }

    public function skus(?Carbon $from = null): Enumerable
    {
        /** @var Enumerable<int, string> $skus */
        $skus = MagentoProduct::query()
            ->where('exists_in_magento', '=', true)
            ->select(['sku'])
            ->distinct()
            ->pluck('sku');

        return $skus;
    }
}
