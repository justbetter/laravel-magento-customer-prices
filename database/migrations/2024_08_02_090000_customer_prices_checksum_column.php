<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('magento_customer_prices', function (Blueprint $table): void {
            $table->string('checksum')->nullable()->after('last_failed');
        });
    }

    public function down(): void
    {
        Schema::dropColumns('magento_customer_prices', ['checksum']);
    }
};
