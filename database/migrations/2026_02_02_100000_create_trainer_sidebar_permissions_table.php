<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainer_sidebar_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->string('sidebar_item');
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamps();

            $table->unique(['trainer_id', 'sidebar_item']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_sidebar_permissions');
    }
};
