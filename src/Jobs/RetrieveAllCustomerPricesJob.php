<?php

namespace JustBetter\MagentoCustomerPrices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesAllCustomerPriceSkus;
use Throwable;

class RetrieveAllCustomerPricesJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 1800;

    public int $tries = 1;

    public function __construct(protected bool $forceUpdate = false)
    {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(RetrievesAllCustomerPriceSkus $retrievesAllCustomerPriceSkus): void
    {
        $retrievesAllCustomerPriceSkus->retrieve()
            ->each(fn (string $sku): PendingDispatch => RetrieveCustomerPriceJob::dispatch($sku, $this->forceUpdate));
    }

    /** @codeCoverageIgnore  */
    public function failed(Throwable $throwable): void
    {
        activity()
            ->useLog('error')
            ->withProperties([
                'exception' => $throwable->getMessage(),
            ])
            ->log('Failed to retrieve all customer prices');
    }
}
