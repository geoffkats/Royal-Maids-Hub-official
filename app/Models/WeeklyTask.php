<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyTask extends Model
{
    protected $fillable = [
        'task_board_id',
        'day_of_week',
        'content',
        'is_complete',
        'sort_order',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(WeeklyTaskBoard::class, 'task_board_id');
    }
}
