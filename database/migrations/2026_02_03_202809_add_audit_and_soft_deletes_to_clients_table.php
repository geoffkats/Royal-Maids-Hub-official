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
        // Guard against re-adding columns in environments with partial migrations.
        if (!Schema::hasColumn('clients', 'created_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
            });
        }

        if (!Schema::hasColumn('clients', 'updated_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
            });
        }

        if (!Schema::hasColumn('clients', 'deleted_at')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('clients', 'deleted_at')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('clients', 'updated_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropConstrainedForeignId('updated_by');
            });
        }

        if (Schema::hasColumn('clients', 'created_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropConstrainedForeignId('created_by');
            });
        }
    }
};
