<?php

namespace JustBetter\MagentoCustomerPrices\Helpers;

use Brick\Money\Context\CustomContext;
use Brick\Money\Money;

class MoneyHelper
{
    public function getMoney(mixed $amount, string $method = 'of'): Money
    {
        return Money::$method(
            $amount,
            config('magento-customer-prices.currency'),
            new CustomContext(config('magento-customer-prices.precision')),
            config('magento-customer-prices.rounding_mode')
        );
    }
}
