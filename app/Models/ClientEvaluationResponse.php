<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientEvaluationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_evaluation_link_id',
        'booking_id',
        'client_id',
        'maid_id',
        'package_id',
        'respondent_name',
        'respondent_email',
        'answers',
        'overall_rating',
        'general_comments',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'overall_rating' => 'decimal:1',
        'submitted_at' => 'datetime',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(ClientEvaluationLink::class, 'client_evaluation_link_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
