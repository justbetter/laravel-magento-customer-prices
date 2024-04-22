<?php

namespace JustBetter\MagentoCustomerPrices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\MagentoCustomerPrices\Contracts\ProcessesRetrievedPrice;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use Throwable;

class RetrieveCustomerPriceJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $backoff = 60;

    public function __construct(
        public string $sku,
        public bool $forceUpdate = false
    ) {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(
        ProcessesRetrievedPrice $processesRetrievedPrice,
        RetrievesCustomerPrice $retrieveCustomerPrice,
    ): void {
        $processesRetrievedPrice->process(
            $this->sku,
            $retrieveCustomerPrice->retrieve($this->sku),
            $this->forceUpdate
        );
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
    public function failed(Throwable $throwable): void
    {
        $model = MagentoCustomerPrice::findBySku($this->sku) ?? MagentoCustomerPrice::create(['sku' => $this->sku]);
        $model->registerFail(MagentoCustomerPrice::STATE_RETRIEVE);

        activity()
            ->on($model)
            ->useLog('error')
            ->withProperties([
                'exception' => $throwable->getMessage(),
            ])
            ->log('Failed to retrieve customer price');
    }
}
