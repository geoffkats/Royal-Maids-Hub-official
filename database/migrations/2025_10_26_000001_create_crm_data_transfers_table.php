<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_data_transfers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['import', 'export']);
            $table->string('entity', 50); // leads, opportunities, activities
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('format', 16)->nullable(); // xlsx, csv
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status', 32)->default('pending'); // pending, running, completed, failed
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('success_count')->default(0);
            $table->unsignedInteger('failure_count')->default(0);
            $table->json('errors')->nullable();
            $table->timestamps();

            $table->index(['type', 'entity', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_data_transfers');
    }
};
