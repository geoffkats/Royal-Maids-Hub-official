<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'experience_years',
        'bio',
        'photo_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->photo_path);
        }
        // Fallback avatar using UI avatars or a placeholder
        $name = urlencode($this->user?->name ?? 'Trainer');
        return "https://ui-avatars.com/api/?name={$name}&background=10b981&color=ffffff&format=svg";
    }

    /**
     * Get the trainer's name from the related user
     */
    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }
}
