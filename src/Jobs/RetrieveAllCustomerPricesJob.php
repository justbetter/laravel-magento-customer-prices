<?php

namespace JustBetter\MagentoCustomerPrices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\ErrorLogger\Models\Error;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesAllCustomerPriceSkus;
use Throwable;

class RetrieveAllCustomerPricesJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 1800;

    public function __construct(protected bool $forceUpdate = false)
    {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(RetrievesAllCustomerPriceSkus $retrievesAllCustomerPriceSkus): void
    {
        $retrievesAllCustomerPriceSkus->retrieve()
            ->each(fn (string $sku) => RetrieveCustomerPriceJob::dispatch($sku, $this->forceUpdate));
    }

    public function failed(Throwable $throwable): void
    {
        Error::log()
            ->withGroup('Customer Prices')
            ->withMessage('Failed to retrieve all customer prices')
            ->fromThrowable($throwable)
            ->save();
    }
}
