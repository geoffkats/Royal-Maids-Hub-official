<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataTransfer extends Model
{
    use HasFactory;

    protected $table = 'crm_data_transfers';

    protected $fillable = [
        'type', 'entity', 'file_path', 'file_name', 'format', 'user_id', 'status',
        'total_rows', 'success_count', 'failure_count', 'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
