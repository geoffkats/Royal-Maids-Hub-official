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
        Schema::table('crm_stages', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('crm_stages', 'is_closed')) {
                $table->boolean('is_closed')->default(false)->after('sla_follow_up_hours');
            }
            if (!Schema::hasColumn('crm_stages', 'probability_default')) {
                $table->integer('probability_default')->nullable()->after('sla_follow_up_hours')->comment('Default probability % when entering this stage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_stages', function (Blueprint $table) {
            $table->dropColumn([
                'is_closed',
                'probability_default'
            ]);
        });
    }
};
