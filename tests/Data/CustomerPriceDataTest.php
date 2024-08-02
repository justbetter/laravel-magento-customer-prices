<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Data;

use Illuminate\Validation\ValidationException;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CustomerPriceDataTest extends TestCase
{
    #[Test]
    public function it_passes_simple_rules(): void
    {
        CustomerPriceData::of([
            'sku' => '::sku::',
        ]);

        $this->assertTrue(true, 'No exception thrown');
    }

    #[Test]
    public function it_fails_rules(): void
    {
        $this->expectException(ValidationException::class);

        CustomerPriceData::of([]);
    }

    #[Test]
    public function it_calculates_checksum(): void
    {
        $data = CustomerPriceData::of([
            'sku' => '::sku::',
        ]);

        $this->assertEquals('b5a9aed3556af7b01952f7fdcd28fdd8', $data->checksum());
    }

    #[Test]
    public function it_handles_array_operations(): void
    {
        $data = CustomerPriceData::of([
            'sku' => '::sku::',
        ]);

        $data['prices'] = [];

        $this->assertEquals([], $data['prices']);
        $this->assertTrue(isset($data['prices']));
        unset($data['prices']);

        $this->assertNull($data['prices']);
    }
}
