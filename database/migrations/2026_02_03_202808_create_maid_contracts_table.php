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
        Schema::create('maid_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maid_id')->constrained()->cascadeOnDelete();
            $table->date('contract_start_date');
            $table->date('contract_end_date')->nullable();
            $table->enum('contract_status', ['pending', 'active', 'completed', 'terminated'])->default('pending')->index();
            $table->string('contract_type', 100)->nullable();
            $table->unsignedInteger('worked_days')->default(0);
            $table->unsignedInteger('pending_days')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maid_contracts');
    }
};
