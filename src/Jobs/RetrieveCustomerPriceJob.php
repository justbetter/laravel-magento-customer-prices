<?php

namespace JustBetter\MagentoCustomerPrices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\ErrorLogger\Models\Error;
use JustBetter\MagentoCustomerPrices\Contracts\ProcessesRetrievedPrice;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use Throwable;

class RetrieveCustomerPriceJob implements ShouldQueue, ShouldBeUnique
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

    public function failed(Throwable $throwable): void
    {
        $model = MagentoCustomerPrice::findBySku($this->sku) ?? MagentoCustomerPrice::create(['sku' => $this->sku]);
        $model->registerFail(MagentoCustomerPrice::STATE_RETRIEVE);

        Error::log()
            ->withGroup('Customer Prices')
            ->withMessage("Failed to retrieve customer price for sku $this->sku")
            ->withModel($model)
            ->fromThrowable($throwable)
            ->save();
    }
}
