<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maid extends Model
{
    use HasFactory;

    protected $fillable = [
        'maid_code',
        'first_name',
        'last_name',
        'phone',
        'mobile_number_2',
        'date_of_birth',
        'date_of_arrival',
        'nationality',
        'status',
        'secondary_status',
        'work_status',
        'nin_number',
        'lc1_chairperson',
        'mother_name_phone',
        'father_name_phone',
        'marital_status',
        'number_of_children',
        'tribe',
        'village',
        'district',
        'education_level',
        'experience_years',
        'mother_tongue',
        'english_proficiency',
        'role',
        'previous_work',
        'medical_status',
        'profile_image',
        'additional_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_arrival' => 'date',
        'medical_status' => 'array',
        'experience_years' => 'integer',
        'english_proficiency' => 'integer',
        'number_of_children' => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(function (self $maid) {
            if (empty($maid->maid_code)) {
                $maid->maid_code = 'RMH' . str_pad((string) $maid->id, 5, '0', STR_PAD_LEFT);
                $maid->save();
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
