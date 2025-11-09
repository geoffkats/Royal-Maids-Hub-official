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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            
            // Company Information
            $table->string('company_name')->default('Royal Maids Hub');
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            
            // Branding
            $table->string('logo')->nullable();
            $table->string('logo_dark')->nullable(); // For dark mode
            $table->string('favicon')->nullable();
            
            // SEO Settings
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('meta_robots')->default('index, follow');
            
            // Open Graph / Social Media
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            
            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_site')->nullable();
            $table->string('twitter_creator')->nullable();
            
            // Analytics & Scripts
            $table->text('google_analytics_id')->nullable();
            $table->text('google_tag_manager_id')->nullable();
            $table->text('facebook_pixel_id')->nullable();
            $table->longText('head_scripts')->nullable(); // Custom scripts for <head>
            $table->longText('body_scripts')->nullable(); // Custom scripts for <body>
            $table->longText('footer_scripts')->nullable(); // Custom scripts before </body>
            
            // Google Services
            $table->text('google_site_verification')->nullable();
            $table->text('google_search_console')->nullable();
            
            // Social Media Links
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            
            // Contact Information
            $table->string('support_email')->nullable();
            $table->string('support_phone')->nullable();
            $table->text('business_hours')->nullable();
            
            // Additional Settings
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
