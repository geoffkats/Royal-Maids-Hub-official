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
        Schema::table('crm_pipelines', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_pipelines', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('crm_pipelines', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_default');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_pipelines', function (Blueprint $table) {
            if (Schema::hasColumn('crm_pipelines', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('crm_pipelines', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
