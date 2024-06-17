<?php

namespace JustBetter\MagentoCustomerPrices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesUpdatedPriceSkus;
use Throwable;

class RetrieveUpdatedCustomerPricesJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(
        RetrievesUpdatedPriceSkus $retrieveUpdatedPriceSkus
    ): void {
        $retrieveUpdatedPriceSkus->retrieve()
            ->each(fn (string $sku) => RetrieveCustomerPriceJob::dispatch($sku));
    }

    /** @codeCoverageIgnore  */
    public function failed(Throwable $throwable): void
    {
        activity()
            ->useLog('error')
            ->withProperties([
                'exception' => $throwable->getMessage(),
            ])
            ->log('Failed to retrieve updated customer prices');
    }
}
