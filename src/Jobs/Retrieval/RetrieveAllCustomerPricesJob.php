<?php

namespace JustBetter\MagentoCustomerPrices\Jobs\Retrieval;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesAllCustomerPrices;

class RetrieveAllCustomerPricesJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public ?Carbon $from = null)
    {
        $this->onQueue(config('magento-customer-prices.queue'));
    }

    public function handle(RetrievesAllCustomerPrices $prices): void
    {
        $prices->retrieve($this->from);
    }
}
