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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maid_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->date('deployment_date');
            $table->string('deployment_location');
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('deployment_address')->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->string('contract_type')->default('full-time'); // full-time, part-time, live-in, live-out
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, completed, terminated
            $table->date('end_date')->nullable();
            $table->text('end_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
