<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->foreignId('maid_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('training_programs')->onDelete('set null');
            $table->date('evaluation_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Comprehensive scores JSON structure:
            // {
            //   "personality": {"confidence": 4, "self_awareness": 4, "emotional_stability": 4, "growth_mindset": 5, "comments": "..."},
            //   "behavior": {"punctuality": 4, "respect_for_instructions": 4, "work_ownership": 5, "interpersonal_conduct": 5, "comments": "..."},
            //   "performance": {"alertness": 4, "first_aid_ability": 3, "security_consciousness": 4, "comments": "..."}
            // }
            $table->json('scores')->nullable();
            $table->decimal('overall_rating', 3, 1)->nullable(); // 0.0 to 5.0
            $table->text('general_comments')->nullable();
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->timestamps();

            $table->index(['trainer_id', 'evaluation_date']);
            $table->index(['maid_id', 'evaluation_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
