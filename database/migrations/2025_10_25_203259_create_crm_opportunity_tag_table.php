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
        Schema::create('crm_opportunity_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opportunity_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
            
            $table->foreign('opportunity_id')->references('id')->on('crm_opportunities')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('crm_tags')->onDelete('cascade');
            $table->unique(['opportunity_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_opportunity_tag');
    }
};
