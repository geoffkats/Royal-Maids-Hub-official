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
        Schema::table('deployments', function (Blueprint $table) {
            $table->decimal('maid_salary', 12, 2)->nullable()->after('monthly_salary');
            $table->decimal('client_payment', 12, 2)->nullable()->after('maid_salary');
            $table->decimal('service_paid', 12, 2)->nullable()->after('client_payment');
            $table->date('salary_paid_date')->nullable()->after('service_paid');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->after('salary_paid_date');
            $table->char('currency', 3)->default('UGX')->after('payment_status');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('currency');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn([
                'currency',
                'payment_status',
                'salary_paid_date',
                'service_paid',
                'client_payment',
                'maid_salary',
            ]);
        });
    }
};
