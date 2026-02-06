<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maids', function (Blueprint $table) {
            $table->id();
            $table->string('maid_code', 20)->nullable()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('mobile_number_2')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_arrival')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('status', ['available','in-training','booked','deployed','absconded','terminated','on-leave'])->default('available')->index();
            $table->enum('secondary_status', ['booked','available','deployed','on-leave','absconded','terminated'])->nullable();
            $table->enum('work_status', ['brokerage','long-term','part-time','full-time'])->nullable()->index();
            $table->string('nin_number')->unique();
            $table->text('lc1_chairperson')->nullable();
            $table->string('mother_name_phone');
            $table->string('father_name_phone');
            $table->enum('marital_status', ['single','married'])->default('single');
            $table->unsignedInteger('number_of_children')->default(0);
            $table->string('tribe');
            $table->string('village');
            $table->string('district');
            $table->enum('education_level', ['P.7','S.4','Certificate','Diploma'])->default('P.7');
            $table->unsignedInteger('experience_years')->default(0);
            $table->string('mother_tongue');
            $table->unsignedTinyInteger('english_proficiency')->default(1);
            $table->enum('role', ['housekeeper','house_manager','nanny','chef','elderly_caretaker','nakawere_caretaker'])->default('housekeeper')->index();
            $table->text('previous_work')->nullable();
            $table->json('medical_status')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('additional_notes')->nullable();
            // Enable soft deletes for recoverability and audit safety.
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status','work_status','role']);
            $table->index('date_of_arrival');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maids');
    }
};
