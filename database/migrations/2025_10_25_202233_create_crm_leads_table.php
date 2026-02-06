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
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->nullable(); // cached
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('industry')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('source_id')->nullable()->constrained('crm_sources')->onDelete('set null');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['new', 'working', 'qualified', 'disqualified', 'converted'])->default('new');
            $table->integer('score')->default(0);
            $table->foreignId('interested_package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null'); // audit link after conversion
            // Enable soft deletes for recoverability and audit safety.
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('email');
            $table->index('phone');
            $table->index('owner_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};