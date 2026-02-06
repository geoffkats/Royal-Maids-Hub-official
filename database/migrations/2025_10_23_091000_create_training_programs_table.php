<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('maid_id')->constrained()->cascadeOnDelete();
            $table->string('program_type'); // e.g., orientation, housekeeping, childcare, cooking
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, in-progress, completed, cancelled
            $table->text('notes')->nullable();
            $table->unsignedInteger('hours_completed')->default(0);
            $table->unsignedInteger('hours_required')->default(40);
            // Enable soft deletes for recoverability and audit safety.
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};
