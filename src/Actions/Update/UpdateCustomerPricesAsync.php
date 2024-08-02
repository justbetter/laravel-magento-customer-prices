<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Update;

use Illuminate\Support\Collection;
use JustBetter\MagentoAsync\Client\MagentoAsync;
use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPricesAsync;

class UpdateCustomerPricesAsync implements UpdatesCustomerPricesAsync
{
    public function __construct(protected MagentoAsync $magentoAsync)
    {
    }

    public function update(Collection $customerPrices): void
    {
        //
    }

    public static function bind(): void
    {
        app()->singleton(UpdatesCustomerPricesAsync::class, static::class);
    }
}
