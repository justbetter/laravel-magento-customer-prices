<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Retrieval;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesAllCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Repository\BaseRepository;

class RetrieveAllCustomerPrices implements RetrievesAllCustomerPrices
{
    public function retrieve(?Carbon $from = null, bool $defer = true): void
    {
        $repository = BaseRepository::resolve();

        if (! $defer) {
            $repository->skus($from)->each(fn (string $sku): PendingDispatch => RetrieveCustomerPriceJob::dispatch($sku));

            return;
        }

        $date = now();

        $repository->skus($from)->chunk(250)->each(function (Enumerable $skus) use ($date): void {
            $existing = CustomerPrice::query()
                ->whereIn('sku', $skus)
                ->pluck('sku');

            CustomerPrice::query()
                ->whereIn('sku', $existing)
                ->where('sync', '=', true)
                ->update(['retrieve' => true]);

            CustomerPrice::query()->insert(
                $skus
                    ->diff($existing)
                    ->values()
                    ->map(fn (string $sku): array => [
                        'sku' => $sku,
                        'retrieve' => true,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ])->toArray()
            );
        });
    }

    public static function bind(): void
    {
        app()->singleton(RetrievesAllCustomerPrices::class, static::class);
    }
}
