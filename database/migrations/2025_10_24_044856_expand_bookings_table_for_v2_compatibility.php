<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration expands the bookings table to match the comprehensive
     * V2.0 booking system with 40+ fields across 4 sections.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // ============================================================
            // SECTION 1: CONTACT INFORMATION (8 fields)
            // ============================================================
            $table->string('full_name', 100)->nullable()->after('id');
            $table->string('phone', 20)->nullable()->after('full_name');
            $table->string('email', 100)->nullable()->after('phone');
            $table->string('country', 50)->nullable()->after('email');
            $table->string('city', 50)->nullable()->after('country');
            $table->string('division', 50)->nullable()->after('city');
            $table->string('parish', 50)->nullable()->after('division');
            $table->string('national_id_path')->nullable()->after('parish'); // File storage path

            // ============================================================
            // SECTION 2: HOME & ENVIRONMENT (5 field groups)
            // ============================================================
            $table->string('home_type', 50)->nullable()->after('national_id_path'); // Apartment, Bungalow, Maisonette, Other
            $table->integer('bedrooms')->default(0)->after('home_type');
            $table->integer('bathrooms')->default(0)->after('bedrooms');
            $table->json('outdoor_responsibilities')->nullable()->after('bathrooms'); // Array: Sweeping, Gardening, None
            $table->json('appliances')->nullable()->after('outdoor_responsibilities'); // Array: Washing Machine, Microwave, etc.

            // ============================================================
            // SECTION 3: HOUSEHOLD COMPOSITION (7 fields)
            // ============================================================
            $table->integer('adults')->default(1)->after('appliances');
            $table->enum('has_children', ['Yes', 'No'])->nullable()->after('adults');
            $table->text('children_ages')->nullable()->after('has_children'); // Conditional field
            $table->enum('has_elderly', ['Yes', 'No'])->nullable()->after('children_ages');
            $table->string('pets', 100)->nullable()->after('has_elderly'); // Yes with duties, Yes no duties, No
            $table->string('pet_kind', 100)->nullable()->after('pets'); // Conditional field
            $table->string('language', 50)->nullable()->after('pet_kind'); // English, Luganda, Mix, Other
            $table->string('language_other', 100)->nullable()->after('language'); // Conditional field

            // ============================================================
            // SECTION 4: JOB ROLE & EXPECTATIONS (11 field groups)
            // Note: service_type already exists in original migration, we'll keep it
            // ============================================================
            $table->enum('service_tier', ['Silver', 'Gold', 'Platinum'])->nullable()->after('language_other'); // Replaces amount
            $table->enum('service_mode', ['Live-in', 'Live-out'])->nullable()->after('service_tier');
            $table->json('work_days')->nullable()->after('service_mode'); // Array: Monday-Sunday
            $table->string('working_hours', 100)->nullable()->after('work_days'); // e.g., "8 AM - 5 PM"
            $table->json('responsibilities')->nullable()->after('working_hours'); // Array: Cleaning, Laundry, Cooking, etc.
            $table->string('cuisine_type', 50)->nullable()->after('responsibilities'); // Local, Mixed, Other
            $table->string('atmosphere', 50)->nullable()->after('cuisine_type'); // Quiet/Calm, Busy/Fast-paced
            $table->string('manage_tasks', 100)->nullable()->after('atmosphere'); // Verbal, Written list, Worker initiative
            $table->text('unspoken_rules')->nullable()->after('manage_tasks');
            $table->text('anything_else')->nullable()->after('unspoken_rules'); // Additional notes/requirements

            // ============================================================
            // INDEXES FOR PERFORMANCE
            // ============================================================
            $table->index('full_name');
            $table->index('email');
            $table->index('phone');
            $table->index('service_tier');
            $table->index('service_mode');
            $table->index(['city', 'division']); // Composite for location searches
        });

        // Drop the amount column since pricing is now based on service_tier
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add amount column
            $table->decimal('amount', 10, 2)->nullable()->after('status');
            
            // Drop Section 1: Contact Information
            $table->dropColumn([
                'full_name',
                'phone',
                'email',
                'country',
                'city',
                'division',
                'parish',
                'national_id_path',
            ]);

            // Drop Section 2: Home & Environment
            $table->dropColumn([
                'home_type',
                'bedrooms',
                'bathrooms',
                'outdoor_responsibilities',
                'appliances',
            ]);

            // Drop Section 3: Household Composition
            $table->dropColumn([
                'adults',
                'has_children',
                'children_ages',
                'has_elderly',
                'pets',
                'pet_kind',
                'language',
                'language_other',
            ]);

            // Drop Section 4: Job Role & Expectations
            $table->dropColumn([
                'service_tier',
                'service_mode',
                'work_days',
                'working_hours',
                'responsibilities',
                'cuisine_type',
                'atmosphere',
                'manage_tasks',
                'unspoken_rules',
                'anything_else',
            ]);
        });
    }
};
