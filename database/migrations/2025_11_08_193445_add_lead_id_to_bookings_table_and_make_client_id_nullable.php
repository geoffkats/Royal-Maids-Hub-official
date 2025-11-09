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
            // Drop the existing foreign key constraint on client_id
            $table->dropForeign(['client_id']);
            
            // Make client_id nullable (bookings can exist without clients initially - linked to leads)
            $table->foreignId('client_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            
            // Add lead_id to link bookings to leads
            $table->foreignId('lead_id')->nullable()->after('client_id')
                ->constrained('crm_leads')->onDelete('set null');
            
            // Add index for lead_id
            $table->index('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop lead_id
            $table->dropForeign(['lead_id']);
            $table->dropIndex(['lead_id']);
            $table->dropColumn('lead_id');
            
            // Drop foreign key on client_id
            $table->dropForeign(['client_id']);
            
            // Make client_id required again (this might fail if there are null values)
            // Note: In production, ensure all bookings have client_id before rolling back
            $table->foreignId('client_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }
};
