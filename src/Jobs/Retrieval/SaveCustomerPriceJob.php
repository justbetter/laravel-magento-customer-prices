<?php

namespace JustBetter\MagentoCustomerPrices\Jobs\Retrieval;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\SavesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use Spatie\Activitylog\ActivityLogger;
use Throwable;

class SaveCustomerPriceJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        public CustomerPriceData $data,
        public bool $forceUpdate
    ) {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(SavesCustomerPrice $price): void
    {
        $price->save($this->data, $this->forceUpdate);
    }

    public function uniqueId(): string
    {
        return $this->data['sku'];
    }

    public function tags(): array
    {
        return [
            $this->data['sku'],
        ];
    }

    /** @codeCoverageIgnore */
    public function failed(Throwable $exception): void
    {
        /** @var ?CustomerPrice $model */
        $model = CustomerPrice::query()->firstWhere('sku', '=', $this->data['sku']);

        activity()
            ->when($model, function (ActivityLogger $logger, CustomerPrice $price): ActivityLogger {
                return $logger->on($price);
            })
            ->useLog('error')
            ->log('Failed to customer save price: '.$exception->getMessage());
    }
}
