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
        Schema::create('crm_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->onDelete('cascade');
            $table->string('name');
            $table->smallInteger('position');
            $table->integer('sla_first_response_hours')->nullable();
            $table->integer('sla_follow_up_hours')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->integer('probability_default')->nullable();
            $table->timestamps();
            
            $table->index(['pipeline_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_stages');
    }
};