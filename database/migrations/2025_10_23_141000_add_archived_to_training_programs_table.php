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
        Schema::table('training_programs', function (Blueprint $table) {
            $table->boolean('archived')->default(false)->after('notes');
            $table->timestamp('archived_at')->nullable()->after('archived');
            $table->index('archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropIndex(['archived']);
            $table->dropColumn(['archived', 'archived_at']);
        });
    }
};
