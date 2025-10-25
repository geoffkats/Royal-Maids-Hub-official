<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->boolean('archived')->default(false)->after('status');
            $table->timestamp('archived_at')->nullable()->after('archived');
            
            $table->index('archived');
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropIndex(['archived']);
            $table->dropColumn(['archived', 'archived_at']);
        });
    }
};
