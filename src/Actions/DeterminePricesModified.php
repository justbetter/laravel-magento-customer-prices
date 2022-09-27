<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Contracts\DeterminesPricesModified;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;

class DeterminePricesModified implements DeterminesPricesModified
{
    public function check(Enumerable $a, Enumerable $b): bool
    {
        /** @var CustomerPriceData $price */
        foreach ($a as $price) {
            $matchingPrice = $b
                ->where('customerId', $price->getCustomerId())
                ->where('quantity', $price->getQuantity())
                ->where('storeId', $price->getStoreId())
                ->first();

            if ($matchingPrice === null || ! $price->equals($matchingPrice)) {
                return true;
            }
        }

        return false;
    }

    public static function bind(): void
    {
        app()->singleton(DeterminesPricesModified::class, static::class);
    }
}
