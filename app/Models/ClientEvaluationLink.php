<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientEvaluationLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'token',
        'status',
        'sent_at',
        'expires_at',
        'sent_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function response(): HasOne
    {
        return $this->hasOne(ClientEvaluationResponse::class, 'client_evaluation_link_id');
    }
}
