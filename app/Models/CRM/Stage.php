<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stage extends Model
{
    protected $table = 'crm_stages';

    protected $fillable = [
        'pipeline_id',
        'name',
        'position',
        'sla_first_response_hours',
        'sla_follow_up_hours',
        'is_closed',
        'probability_default',
    ];

    protected $casts = [
        'position' => 'integer',
        'sla_first_response_hours' => 'integer',
        'sla_follow_up_hours' => 'integer',
        'is_closed' => 'boolean',
        'probability_default' => 'integer',
    ];

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }
}
