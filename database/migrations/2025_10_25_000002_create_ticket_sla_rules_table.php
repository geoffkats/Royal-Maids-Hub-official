<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_sla_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name');
            $table->enum('package_tier', ['silver', 'gold', 'platinum']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent', 'critical']);
            $table->integer('response_time_minutes');
            $table->integer('resolution_time_minutes');
            $table->boolean('auto_boost_priority')->default(false);
            $table->enum('boosted_priority', ['low', 'medium', 'high', 'urgent', 'critical'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_sla_rules');
    }
};