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
            $table->enum('identity_type', ['nin', 'passport'])->nullable()->after('parish');
            $table->string('identity_number', 50)->nullable()->after('identity_type');
            $table->index(['identity_type', 'identity_number'], 'bookings_identity_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_identity_index');
            $table->dropColumn(['identity_type', 'identity_number']);
        });
    }
};
