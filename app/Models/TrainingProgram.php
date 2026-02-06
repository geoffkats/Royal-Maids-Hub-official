<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Soft deletes protect training programs from hard removal.
 */
class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trainer_id',
        'maid_id',
        'program_type',
        'start_date',
        'end_date',
        'status',
        'notes',
        'hours_completed',
        'hours_required',
        'archived',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'hours_completed' => 'integer',
            'hours_required' => 'integer',
            'archived' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function maid()
    {
        return $this->belongsTo(Maid::class);
    }

    // Archive scopes and methods
    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    public function archive(): void
    {
        $this->update([
            'archived' => true,
            'archived_at' => now(),
        ]);
    }

    public function unarchive(): void
    {
        $this->update([
            'archived' => false,
            'archived_at' => null,
        ]);
    }
}
