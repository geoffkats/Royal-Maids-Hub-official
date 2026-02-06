<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Role constants
     */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATIONS_MANAGER = 'operations_manager';
    public const ROLE_TRAINER = 'trainer';
    public const ROLE_FINANCE_OFFICER = 'finance_officer';
    public const ROLE_CUSTOMER_SUPPORT = 'customer_support';
    public const ROLE_CLIENT = 'client';

    /**
     * Get the supported roles for RBAC enforcement.
     *
     * @return list<string>
     */
    public static function roles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_OPERATIONS_MANAGER,
            self::ROLE_TRAINER,
            self::ROLE_FINANCE_OFFICER,
            self::ROLE_CUSTOMER_SUPPORT,
            self::ROLE_CLIENT,
        ];
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAdminLike(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function isOperationsManager(): bool
    {
        return $this->role === self::ROLE_OPERATIONS_MANAGER;
    }

    public function isTrainer(): bool
    {
        return $this->role === self::ROLE_TRAINER;
    }

    public function isFinanceOfficer(): bool
    {
        return $this->role === self::ROLE_FINANCE_OFFICER;
    }

    public function isCustomerSupport(): bool
    {
        return $this->role === self::ROLE_CUSTOMER_SUPPORT;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the profile picture URL
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        if ($this->profile_picture) {
            return \Storage::url($this->profile_picture);
        }
        return null;
    }

    /**
     * Get tickets assigned to this user
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Get the trainer profile for this user
     */
    public function trainer()
    {
        return $this->hasOne(Trainer::class);
    }
}
