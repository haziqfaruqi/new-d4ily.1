<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('bill_code')->nullable()->after('payment_method');
            $table->string('payment_status')->nullable()->after('bill_code');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->decimal('subtotal', 10, 2)->nullable()->after('total_price');
            $table->decimal('tax', 10, 2)->nullable()->after('subtotal');
            $table->decimal('shipping', 10, 2)->nullable()->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['bill_code', 'payment_status', 'transaction_id', 'subtotal', 'tax', 'shipping']);
        });
    }
};