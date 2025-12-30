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
        Schema::table('interactions', function (Blueprint $table) {
            // Add metadata column for storing additional interaction data
            $table->json('metadata')->nullable()->after('session_id');
        });

        // Modify the enum to include 'wishlist' and 'search' types
        DB::statement("ALTER TABLE interactions MODIFY COLUMN type ENUM('view', 'click', 'cart', 'purchase', 'wishlist', 'search')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });

        // Revert to original enum
        DB::statement("UPDATE interactions SET type = 'view' WHERE type NOT IN ('view', 'click', 'cart', 'purchase')");
        DB::statement("ALTER TABLE interactions MODIFY COLUMN type ENUM('view', 'click', 'cart', 'purchase')");
    }
};
