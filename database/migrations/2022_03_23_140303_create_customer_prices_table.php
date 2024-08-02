<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magento_customer_prices', function (Blueprint $table): void {
            $table->id();
            $table->string('sku');

            $table->boolean('sync')->default(true);

            $table->json('prices')->nullable();

            $table->string('state')->default('idle');

            $table->dateTime('last_retrieved')->nullable();
            $table->dateTime('last_updated')->nullable();

            $table->integer('fail_count')->default(0);
            $table->dateTime('last_failed')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magento_customer_prices');
    }
};
