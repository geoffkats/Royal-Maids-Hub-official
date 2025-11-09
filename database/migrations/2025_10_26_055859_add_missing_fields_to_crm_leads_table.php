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
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->timestamp('converted_at')->nullable()->after('client_id');
            $table->timestamp('disqualified_at')->nullable()->after('converted_at');
            $table->text('disqualified_reason')->nullable()->after('disqualified_at');
            $table->timestamp('last_contacted_at')->nullable()->after('disqualified_reason');
            
            $table->index('converted_at');
            $table->index('last_contacted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropIndex(['converted_at']);
            $table->dropIndex(['last_contacted_at']);
            $table->dropColumn([
                'converted_at',
                'disqualified_at',
                'disqualified_reason',
                'last_contacted_at'
            ]);
        });
    }
};
