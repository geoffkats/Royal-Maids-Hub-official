<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class Deployment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'maid_id',
        'client_id',
        'deployment_date',
        'deployment_location',
        'client_name',
        'client_phone',
        'deployment_address',
        'monthly_salary',
        'maid_salary',
        'client_payment',
        'service_paid',
        'salary_paid_date',
        'payment_status',
        'currency',
        'contract_type',
        'contract_start_date',
        'contract_end_date',
        'special_instructions',
        'notes',
        'status',
        'end_date',
        'end_reason',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'deployment_date' => 'date',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'end_date' => 'date',
            'monthly_salary' => 'decimal:2',
            'maid_salary' => 'decimal:2',
            'client_payment' => 'decimal:2',
            'service_paid' => 'decimal:2',
            'salary_paid_date' => 'date',
        ];
    }

    /**
     * Hide sensitive financial data from default serialization.
     * Access is controlled via policies and gates.
     *
     * @var list<string>
     */
    protected $hidden = [
        'monthly_salary',
        'maid_salary',
        'client_payment',
        'service_paid',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $deployment): void {
            $user = auth()->user();

            if (!$user) {
                return;
            }

            $financialFields = ['monthly_salary', 'maid_salary', 'client_payment', 'service_paid'];
            $financialChanged = collect($financialFields)->contains(fn (string $field) => $deployment->isDirty($field));

            $hasValues = collect($financialFields)->contains(fn (string $field) => $deployment->{$field} !== null);
            $hadValues = collect($financialFields)->contains(fn (string $field) => $deployment->getOriginal($field) !== null);

            if ($financialChanged && ($hasValues || $hadValues) && Gate::denies('updateSensitiveFinancials', $deployment)) {
                throw new AuthorizationException('Unauthorized to update financial fields.');
            }
        });
    }

    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
