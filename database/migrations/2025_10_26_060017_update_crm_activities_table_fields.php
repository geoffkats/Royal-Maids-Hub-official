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
        Schema::table('crm_activities', function (Blueprint $table) {
            $table->text('outcome')->nullable()->after('completed_at')->comment('Result or outcome of the activity');
            $table->foreignId('owner_id')->nullable()->after('assigned_to')->constrained('users')->onDelete('set null')->comment('Activity owner (can differ from assignee)');
            
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_activities', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropIndex(['owner_id']);
            $table->dropColumn(['outcome', 'owner_id']);
        });
    }
};
