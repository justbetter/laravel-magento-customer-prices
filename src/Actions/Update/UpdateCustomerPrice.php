<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Update;

use Illuminate\Http\Client\Response;
use JustBetter\MagentoClient\Client\Magento;
use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoProducts\Contracts\ChecksMagentoExistence;

class UpdateCustomerPrice implements UpdatesCustomerPrice
{
    public function __construct(
        protected Magento $magento,
        protected ChecksMagentoExistence $magentoExistence
    ) {}

    public function update(CustomerPrice $price): void
    {
        if (! $this->magentoExistence->exists($price->sku)) {
            $price->update([
                'update' => false,
            ]);

            return;
        }

        $response = $this->magento->post('customer-pricing/'.urlencode($price->sku), [
            'customerPrices' => $price->prices,
        ])->onError(function (Response $response) use ($price): void {
            activity()
                ->on($price)
                ->useLog('error')
                ->withProperties([
                    'response' => $response->body(),
                ])
                ->log('Failed to update customer price');
        });

        if ($response->failed()) {
            $price->registerFailure();

            return;
        }

        $price->update([
            'update' => false,
            'last_updated' => now(),
            'fail_count' => 0,
            'last_failed' => null,
        ]);
    }

    public static function bind(): void
    {
        app()->singleton(UpdatesCustomerPrice::class, static::class);
    }
}
