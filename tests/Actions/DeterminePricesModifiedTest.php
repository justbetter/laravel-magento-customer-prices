<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Brick\Money\Money;
use JustBetter\MagentoCustomerPrices\Actions\DeterminePricesModified;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class DeterminePricesModifiedTest extends TestCase
{
    /** @dataProvider provider */
    public function test_modified(array $pricesA, array $pricesB, bool $modified): void
    {
        /** @var DeterminePricesModified $action */
        $action = app(DeterminePricesModified::class);

        $this->assertEquals($modified, $action->check(collect($pricesA), collect($pricesB)));
    }

    public static function provider(): array
    {
        return [
            'modified_price' => [
                'prices_a' => [
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                ],
                'prices_b' => [
                    new CustomerPriceData('::sku::', Money::of(11, 'EUR'), 1, 1),
                ],
                'modified' => true,
            ],
            'added_price' => [
                'prices_a' => [
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                ],
                'prices_b' => [
                    new CustomerPriceData('::sku::', Money::of(11, 'EUR'), 1, 1),
                    new CustomerPriceData('::sku::', Money::of(11, 'EUR'), 2, 1),
                ],
                'modified' => true,
            ],
            'removed_price' => [
                'prices_a' => [
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                    new CustomerPriceData('::sku::', Money::of(11, 'EUR'), 2, 1),
                ],
                'prices_b' => [
                    new CustomerPriceData('::sku::', Money::of(11, 'EUR'), 1, 1),
                ],
                'modified' => true,
            ],
            'equals' => [
                'prices_a' => [
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                ],
                'prices_b' => [
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                    new CustomerPriceData('::sku::', Money::of(10, 'EUR'), 1, 1),
                ],
                'modified' => false,
            ],
        ];
    }
}
