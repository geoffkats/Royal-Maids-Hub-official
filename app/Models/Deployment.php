<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'maid_id',
        'client_id',
        'deployment_date',
        'deployment_location',
        'client_name',
        'client_phone',
        'deployment_address',
        'monthly_salary',
        'contract_type',
        'contract_start_date',
        'contract_end_date',
        'special_instructions',
        'notes',
        'status',
        'end_date',
        'end_reason',
    ];

    protected function casts(): array
    {
        return [
            'deployment_date' => 'date',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'end_date' => 'date',
            'monthly_salary' => 'decimal:2',
        ];
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
