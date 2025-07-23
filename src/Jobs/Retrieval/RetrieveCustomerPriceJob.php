<?php

namespace JustBetter\MagentoCustomerPrices\Jobs\Retrieval;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use Spatie\Activitylog\ActivityLogger;
use Throwable;

class RetrieveCustomerPriceJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        public string $sku,
        public bool $forceUpdate = false
    ) {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(RetrievesCustomerPrice $price): void
    {
        $price->retrieve($this->sku, $this->forceUpdate);
    }

    public function uniqueId(): string
    {
        return $this->sku;
    }

    public function tags(): array
    {
        return [
            $this->sku,
        ];
    }

    /** @codeCoverageIgnore */
    public function failed(Throwable $exception): void
    {
        /** @var ?CustomerPrice $model */
        $model = CustomerPrice::query()->firstWhere('sku', '=', $this->sku);

        activity()
            ->when($model, function (ActivityLogger $logger, CustomerPrice $price): ActivityLogger {
                return $logger->on($price);
            })
            ->useLog('error')
            ->log('Failed to retrieve customer price: '.$exception->getMessage());
    }
}
