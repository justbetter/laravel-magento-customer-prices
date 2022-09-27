<?php

use Brick\Math\RoundingMode;
use JustBetter\MagentoCustomerPrices\Retriever\DummyCustomerPriceRetriever;

return [
    'retriever' => DummyCustomerPriceRetriever::class,

    'queue' => 'default',

    'fail_count' => 5,

    'currency' => 'EUR',
    'precision' => 4,
    'rounding_mode' => RoundingMode::HALF_UP,
];
