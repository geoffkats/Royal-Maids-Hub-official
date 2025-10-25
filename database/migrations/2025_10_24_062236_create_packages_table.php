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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Silver, Gold, Platinum
            $table->string('tier'); // Standard, Premium, Elite
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); // Base monthly price
            $table->integer('base_family_size')->default(3); // Base family size (3 members)
            $table->decimal('additional_member_cost', 10, 2)->default(35000); // Cost per additional family member
            $table->integer('training_weeks'); // Training duration in weeks
            $table->json('training_includes')->nullable(); // Training inclusions
            $table->integer('backup_days_per_year'); // Free backup worker days per year
            $table->integer('free_replacements'); // Number of free replacements
            $table->integer('evaluations_per_year'); // Service evaluations per year
            $table->json('features')->nullable(); // Additional features as JSON
            $table->boolean('is_active')->default(true); // Package availability
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
