<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Retrieval;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Carbon;
use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesAllCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Repository\BaseRepository;

class RetrieveAllCustomerPrices implements RetrievesAllCustomerPrices
{
    public function retrieve(?Carbon $from): void
    {
        $repository = BaseRepository::resolve();

        $repository->skus($from)->each(fn (string $sku): PendingDispatch => RetrieveCustomerPriceJob::dispatch($sku));
    }

    public static function bind(): void
    {
        app()->singleton(RetrievesAllCustomerPrices::class, static::class);
    }
}
