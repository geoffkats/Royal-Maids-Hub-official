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
        Schema::create('evaluation_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maid_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('deployment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('evaluation_id')->nullable()->constrained('evaluations')->nullOnDelete();
            $table->date('due_date');
            $table->unsignedSmallInteger('interval_months');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['maid_id', 'due_date']);
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_tasks');
    }
};
