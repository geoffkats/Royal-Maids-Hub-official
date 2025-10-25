<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->nullable()->unique();
            
            // Categorization
            $table->enum('type', ['client_issue', 'maid_support', 'deployment_issue', 'billing', 'training', 'operations', 'general'])->default('general');
            $table->string('category', 100)->nullable();
            $table->string('subcategory', 100)->nullable();
            
            // Subject and description
            $table->string('subject', 255);
            $table->text('description');
            
            // Priority
            $table->enum('priority', ['low', 'medium', 'high', 'urgent', 'critical'])->default('medium');
            $table->boolean('auto_priority')->default(false);
            $table->enum('tier_based_priority', ['silver', 'gold', 'platinum'])->nullable();
            
            // Requester info
            $table->unsignedBigInteger('requester_id');
            $table->enum('requester_type', ['client', 'maid', 'trainer', 'admin'])->default('client');
            $table->unsignedBigInteger('created_on_behalf_of')->nullable();
            $table->enum('created_on_behalf_type', ['client', 'maid'])->nullable();
            
            // Related entities
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('maid_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('deployment_id')->nullable();
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            
            // Status and assignment
            $table->enum('status', ['open', 'pending', 'in_progress', 'on_hold', 'resolved', 'closed', 'cancelled'])->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->enum('department', ['customer_service', 'operations', 'finance', 'hr', 'training', 'technical'])->nullable();
            
            // SLA and timing
            $table->timestamp('due_date')->nullable();
            $table->timestamp('sla_response_due')->nullable();
            $table->timestamp('sla_resolution_due')->nullable();
            $table->boolean('sla_breached')->default(false);
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            // Location
            $table->text('location_address')->nullable();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            
            // Resolution
            $table->text('resolution_notes')->nullable();
            $table->tinyInteger('satisfaction_rating')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('ticket_number');
            $table->index(['requester_id', 'requester_type']);
            $table->index('status');
            $table->index('priority');
            $table->index('assigned_to');
            $table->index('client_id');
            $table->index('maid_id');
            $table->index('booking_id');
            $table->index('package_id');
            $table->index('tier_based_priority');
            $table->index('sla_breached');
            $table->index('created_on_behalf_of');
            
            // Foreign keys
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('maid_id')->references('id')->on('maids')->onDelete('set null');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
            $table->foreign('deployment_id')->references('id')->on('deployments')->onDelete('set null');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};