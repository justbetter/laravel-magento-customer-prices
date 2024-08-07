<?php

namespace JustBetter\MagentoCustomerPrices\Repository;

use Illuminate\Support\Carbon;
use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;

abstract class BaseRepository
{
    protected int $retrieveLimit = 250;

    protected int $updateLimit = 250;

    protected int $failLimit = 3;

    public function retrieveLimit(): int
    {
        return $this->retrieveLimit;
    }

    public function updateLimit(): int
    {
        return $this->updateLimit;
    }

    public function failLimit(): int
    {
        return $this->failLimit;
    }

    public static function resolve(): BaseRepository
    {
        /** @var ?class-string<BaseRepository> $repository */
        $repository = config('magento-customer-prices.repository');

        throw_if($repository === null, 'Repository has not been found.');

        /** @var BaseRepository $instance */
        $instance = app($repository);

        return $instance;
    }

    /** @return Enumerable<int, string> */
    abstract public function skus(?Carbon $from = null): Enumerable;

    abstract public function retrieve(string $sku): ?CustomerPriceData;
}
