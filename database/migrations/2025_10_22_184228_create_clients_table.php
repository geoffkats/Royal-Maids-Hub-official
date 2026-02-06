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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name')->nullable();
            $table->string('contact_person');
            $table->string('phone');
            $table->string('secondary_phone')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('district');
            $table->enum('subscription_tier', ['basic', 'premium', 'enterprise'])->default('basic');
            $table->enum('subscription_status', ['active', 'expired', 'pending', 'cancelled'])->default('pending')->index();
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->json('preferred_maid_types')->nullable();
            $table->text('special_requirements')->nullable();
            $table->unsignedInteger('total_bookings')->default(0);
            $table->unsignedInteger('active_bookings')->default(0);
            $table->text('notes')->nullable();
            // Enable soft deletes for recoverability and audit safety.
            $table->softDeletes();
            $table->timestamps();

            $table->index(['subscription_status', 'subscription_tier']);
            $table->index('subscription_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
