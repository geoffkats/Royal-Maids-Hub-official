<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaidContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'maid_id',
        'contract_start_date',
        'contract_end_date',
        'contract_status',
        'contract_type',
        'worked_days',
        'pending_days',
        'notes',
        'contract_documents',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'worked_days' => 'integer',
        'pending_days' => 'integer',
        'contract_documents' => 'array',
    ];

    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    /**
     * Get the current active deployment for this contract
     */
    public function getActiveDeployment(): ?Deployment
    {
        if ($this->relationLoaded('maid') && $this->maid->relationLoaded('deployments')) {
            return $this->maid->deployments
                ->where('status', 'active')
                ->sortByDesc('deployment_date')
                ->first();
        }

        return $this->maid->deployments()
            ->where('status', 'active')
            ->latest('deployment_date')
            ->first();
    }

    /**
     * Get the client associated with this contract (through current deployment)
     */
    public function getClient()
    {
        $deployment = $this->getActiveDeployment();
        return $deployment?->client;
    }

    /**
     * Get the salary information from the current deployment
     */
    public function getSalaryInfo(): ?array
    {
        $deployment = $this->getActiveDeployment();
        if (!$deployment) {
            return null;
        }

        return [
            'maid_salary' => $deployment->maid_salary,
            'client_payment' => $deployment->client_payment,
            'service_paid' => $deployment->service_paid,
            'payment_status' => $deployment->payment_status,
            'currency' => $deployment->currency,
        ];
    }

    /**
     * Check if contract is expiring soon (within 30 days)
     */
    public function isExpiringSoon(): bool
    {
        if (!$this->contract_end_date || $this->contract_status === 'expired' || $this->contract_status === 'terminated') {
            return false;
        }

        $daysUntilExpiry = Carbon::today()->diffInDays($this->contract_end_date, false);
        return $daysUntilExpiry <= 30 && $daysUntilExpiry >= 0;
    }

    /**
     * Check if contract is expired
     */
    public function isExpired(): bool
    {
        return $this->contract_end_date && Carbon::today()->greaterThan($this->contract_end_date);
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->contract_end_date) {
            return null;
        }

        $diff = Carbon::today()->diffInDays($this->contract_end_date, false);
        return max(0, $diff);
    }

    /**
     * Get contract completion percentage
     */
    public function getCompletionPercentage(): int
    {
        if (!$this->contract_start_date || !$this->contract_end_date) {
            return 0;
        }

        $totalDays = $this->contract_start_date->diffInDays($this->contract_end_date);
        if ($totalDays === 0) {
            return 100;
        }

        $workedDays = $this->worked_days ?? 0;
        return min(100, (int) (($workedDays / $totalDays) * 100));
    }

    public function recalculateDayCounts(): void
    {
        $workedDays = $this->calculateWorkedDays();
        $pendingDays = $this->calculatePendingDays();

        $this->forceFill([
            'worked_days' => $workedDays,
            'pending_days' => $pendingDays,
        ])->save();
    }

    public function calculateWorkedDays(): int
    {
        $start = $this->contract_start_date;
        $end = $this->contract_end_date ?? Carbon::today();

        if (!$start) {
            return 0;
        }

        return $this->maid->deployments()
            ->whereDate('contract_start_date', '>=', $start)
            ->whereDate('contract_start_date', '<=', $end)
            ->get()
            ->sum(function (Deployment $deployment) use ($start, $end) {
                $deploymentStart = $deployment->contract_start_date ?? $deployment->deployment_date ?? $start;
                $deploymentEnd = $deployment->contract_end_date ?? $deployment->end_date ?? $end;

                if (!$deploymentStart) {
                    return 0;
                }

                $deploymentStart = Carbon::parse($deploymentStart);
                $deploymentEnd = Carbon::parse($deploymentEnd);

                if ($deploymentEnd->lessThan($deploymentStart)) {
                    return 0;
                }

                return $deploymentStart->diffInDays($deploymentEnd) + 1;
            });
    }

    public function calculatePendingDays(): int
    {
        if (!$this->contract_end_date) {
            return 0;
        }

        $diff = Carbon::today()->diffInDays($this->contract_end_date, false);

        return max(0, $diff);
    }
}
