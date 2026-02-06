<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEvaluationQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\ClientEvaluationQuestionFactory> */
    use HasFactory;

    protected $fillable = [
        'question',
        'type',
        'sort_order',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
