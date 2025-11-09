<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyTaskBoard extends Model
{
    protected $fillable = [
        'trainer_id',
        'start_of_week',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeeklyTask::class, 'task_board_id');
    }
}
