<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the enum to include 'processing' status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        // Note: Any existing 'processing' records will need to be updated first
        DB::statement("UPDATE orders SET status = 'pending' WHERE status = 'processing'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};
