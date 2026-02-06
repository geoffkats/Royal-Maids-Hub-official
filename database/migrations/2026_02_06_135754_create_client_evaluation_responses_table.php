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
        Schema::create('client_evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_evaluation_link_id')->constrained('client_evaluation_links')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('maid_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('respondent_name');
            $table->string('respondent_email');
            $table->json('answers');
            $table->decimal('overall_rating', 3, 1)->nullable();
            $table->text('general_comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique('client_evaluation_link_id');
            $table->index(['booking_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_evaluation_responses');
    }
};
