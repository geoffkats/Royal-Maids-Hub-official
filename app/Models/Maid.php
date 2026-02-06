<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Soft deletes protect maid records from hard removal.
 */
class Maid extends Model
{
    use HasFactory, SoftDeletes;

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
        'family_members',
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
        'additional_documents',
        'id_scans',
        'additional_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_arrival' => 'date',
        'medical_status' => 'array',
        'additional_documents' => 'array',
        'id_scans' => 'array',
        'family_members' => 'array',
        'experience_years' => 'float',
        'english_proficiency' => 'integer',
        'number_of_children' => 'integer',
    ];

    /**
     * Hide sensitive identity data from default serialization.
     * Access is controlled via policies and gates.
     *
     * @var list<string>
     */
    protected $hidden = [
        'nin_number',
        'id_scans',
    ];

    protected static function booted(): void
    {
        static::created(function (self $maid) {
            if (empty($maid->maid_code)) {
                $maid->maid_code = 'RMH' . str_pad((string) $maid->id, 5, '0', STR_PAD_LEFT);
                $maid->save();
            }
        });

        static::saving(function (self $maid): void {
            $user = auth()->user();

            if (!$user) {
                return;
            }

            $identityChanged = $maid->isDirty('nin_number') || $maid->isDirty('id_scans');

            if ($identityChanged && Gate::denies('updateSensitiveIdentity', $maid)) {
                throw new AuthorizationException('Unauthorized to update identity fields.');
            }
        });
    }

    /**
     * Get the bookings for the maid.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the deployments for the maid.
     */
    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    /**
     * Get the contracts for the maid.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(MaidContract::class);
    }

    /**
     * Get the current active deployment.
     */
    public function currentDeployment()
    {
        return $this->hasOne(Deployment::class)->where('status', 'active')->latest();
    }

    /**
     * Get the training programs for the maid.
     */
    public function trainingPrograms(): HasMany
    {
        return $this->hasMany(TrainingProgram::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
