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
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('identity_type', ['nin', 'passport'])->nullable()->after('district');
            $table->string('identity_number', 50)->nullable()->after('identity_type');
            $table->unique(['identity_type', 'identity_number'], 'clients_identity_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique('clients_identity_unique');
            $table->dropColumn(['identity_type', 'identity_number']);
        });
    }
};
