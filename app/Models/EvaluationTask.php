<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class EvaluationTask extends Model
{
    protected $fillable = [
        'maid_id',
        'client_id',
        'deployment_id',
        'evaluation_id',
        'due_date',
        'interval_months',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'interval_months' => 'integer',
    ];

    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class);
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public static function createForDeployment(Deployment $deployment): array
    {
        $intervals = [3, 6, 12];
        $created = [];

        foreach ($intervals as $months) {
            $task = static::updateOrCreate(
                [
                    'deployment_id' => $deployment->id,
                    'interval_months' => $months,
                ],
                [
                    'maid_id' => $deployment->maid_id,
                    'client_id' => $deployment->client_id,
                    'due_date' => Carbon::parse($deployment->deployment_date)->addMonths($months)->toDateString(),
                    'status' => 'pending',
                ]
            );

            $created[] = $task;
        }

        return $created;
    }
}
