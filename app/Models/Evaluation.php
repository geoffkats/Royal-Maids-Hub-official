<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Soft deletes protect evaluations from hard removal.
 */
class Evaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trainer_id',
        'maid_id',
        'program_id',
        'evaluation_date',
        'status',
        'archived',
        'archived_at',
        'scores',
        'overall_rating',
        'general_comments',
        'strengths',
        'areas_for_improvement',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'scores' => 'array',
        'overall_rating' => 'decimal:1',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function maid()
    {
        return $this->belongsTo(Maid::class);
    }

    public function program()
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    /**
     * Get the average score from all evaluation sections
     */
    public function getAverageScoreAttribute(): float
    {
        if (empty($this->scores)) {
            return 0.0;
        }

        $allScores = [];
        
        // Collect all numeric scores from personality section
        if (isset($this->scores['personality'])) {
            foreach ($this->scores['personality'] as $key => $value) {
                if ($key !== 'comments' && is_numeric($value)) {
                    $allScores[] = $value;
                }
            }
        }
        
        // Collect all numeric scores from behavior section
        if (isset($this->scores['behavior'])) {
            foreach ($this->scores['behavior'] as $key => $value) {
                if ($key !== 'comments' && is_numeric($value)) {
                    $allScores[] = $value;
                }
            }
        }
        
        // Collect all numeric scores from performance section
        if (isset($this->scores['performance'])) {
            foreach ($this->scores['performance'] as $key => $value) {
                if ($key !== 'comments' && is_numeric($value)) {
                    $allScores[] = $value;
                }
            }
        }

        if (empty($allScores)) {
            return 0.0;
        }

        return round(array_sum($allScores) / count($allScores), 1);
    }

    /**
     * Get personality evaluation scores
     */
    public function getPersonalityScoresAttribute(): ?array
    {
        return $this->scores['personality'] ?? null;
    }

    /**
     * Get behavior evaluation scores
     */
    public function getBehaviorScoresAttribute(): ?array
    {
        return $this->scores['behavior'] ?? null;
    }

    /**
     * Get performance evaluation scores
     */
    public function getPerformanceScoresAttribute(): ?array
    {
        return $this->scores['performance'] ?? null;
    }

    /**
     * Get score badge color based on value
     */
    public function getScoreBadgeColor(float $score): string
    {
        return match (true) {
            $score >= 4.5 => 'green',
            $score >= 3.5 => 'blue',
            $score >= 2.5 => 'yellow',
            default => 'red',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'approved' => 'green',
            'rejected' => 'red',
            default => 'yellow', // pending
        };
    }
    
    /**
     * Get status label for display
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Scope to get only active (non-archived) evaluations
     */
    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }

    /**
     * Scope to get only archived evaluations
     */
    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    /**
     * Archive this evaluation
     */
    public function archive(): bool
    {
        return $this->update([
            'archived' => true,
            'archived_at' => now(),
        ]);
    }

    /**
     * Unarchive this evaluation
     */
    public function unarchive(): bool
    {
        return $this->update([
            'archived' => false,
            'archived_at' => null,
        ]);
    }
}
