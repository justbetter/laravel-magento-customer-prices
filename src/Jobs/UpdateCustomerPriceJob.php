<?php

namespace JustBetter\MagentoCustomerPrices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\MagentoCustomerPrices\Contracts\UpdatesPrices;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use Throwable;

class UpdateCustomerPriceJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public string $sku)
    {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(UpdatesPrices $action): void
    {
        $action->update($this->sku);
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

    /** @codeCoverageIgnore  */
    public function failed(Throwable $throwable): void
    {
        $model = MagentoCustomerPrice::findBySku($this->sku) ?? MagentoCustomerPrice::create(['sku' => $this->sku]);
        $model->registerFail(MagentoCustomerPrice::STATE_UPDATE);

        activity()
            ->on($model)
            ->withProperties([
                'exception' => $throwable->getMessage(),
                'metadata' => [
                    'level' => 'error',
                ],
            ])
            ->log('Failed to update customer price');
    }
}
