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
        Schema::table('crm_opportunities', function (Blueprint $table) {
            // client_id already exists, only add missing fields
            $table->foreignId('package_id')->nullable()->after('client_id')->constrained('packages')->onDelete('set null');
            $table->string('currency', 3)->default('USD')->after('amount');
            $table->date('expected_close_date')->nullable()->after('close_date');
            $table->string('loss_reason')->nullable()->after('lost_at');
            $table->text('loss_notes')->nullable()->after('loss_reason');
            
            $table->index('package_id');
            $table->index('expected_close_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropIndex(['package_id']);
            $table->dropIndex(['expected_close_date']);
            $table->dropColumn([
                'package_id',
                'currency',
                'expected_close_date',
                'loss_reason',
                'loss_notes'
            ]);
        });
    }
};
