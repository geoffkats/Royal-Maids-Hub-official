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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->after('maid_id')->constrained('packages')->nullOnDelete();
            $table->integer('family_size')->nullable()->after('adults'); // Track family size for pricing calculation
            $table->decimal('calculated_price', 10, 2)->nullable()->after('service_tier'); // Auto-calculated from package + family size
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'family_size', 'calculated_price']);
        });
    }
};
