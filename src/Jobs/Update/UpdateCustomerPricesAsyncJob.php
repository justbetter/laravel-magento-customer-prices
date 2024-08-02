<?php

namespace JustBetter\MagentoCustomerPrices\Jobs\Update;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPricesAsync;

class UpdateCustomerPricesAsyncJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Collection $prices
    ) {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(UpdatesCustomerPricesAsync $contract): void
    {
        $contract->update($this->prices);
    }

    public function tags(): array
    {
        return $this->prices->pluck('sku')->toArray();
    }
}
