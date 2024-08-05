<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magento_customer_prices', function (Blueprint $table): void {
            $table->dropColumn('state');
            $table->boolean('retrieve')->default(false)->after('sync');
            $table->boolean('update')->default(false)->after('retrieve');
        });
    }

    public function down(): void
    {
        Schema::table('magento_customer_prices', function (Blueprint $table): void {
            $table->string('state')->default('idle');
        });

    }
};
