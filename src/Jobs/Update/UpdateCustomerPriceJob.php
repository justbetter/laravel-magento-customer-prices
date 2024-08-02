<?php

namespace JustBetter\MagentoCustomerPrices\Jobs\Update;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

class UpdateCustomerPriceJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
       public CustomerPrice $customerPrice
    ) {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(UpdatesCustomerPrice $contract): void
    {
        $contract->update($this->customerPrice);
    }

    public function uniqueId(): int
    {
        return $this->customerPrice->id;
    }

    public function tags(): array
    {
        return [
            $this->customerPrice->sku,
        ];
    }
}
