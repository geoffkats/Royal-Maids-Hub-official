<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientEvaluation extends Model
{
    protected $fillable = [
        'client_id',
        'booking_id',
        'trainer_id',
        'evaluation_date',
        'evaluation_type',
        'overall_rating',
        'strengths',
        'areas_for_improvement',
        'comments',
        'next_evaluation_date',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'next_evaluation_date' => 'date',
        'overall_rating' => 'decimal:1',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}
