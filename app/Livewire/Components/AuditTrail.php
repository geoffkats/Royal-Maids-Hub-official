<?php

namespace App\Livewire\Components;

use App\Models\User;
use Livewire\Component;

class AuditTrail extends Component
{
    public ?string $created_by_name = null;
    public ?string $updated_by_name = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function mount(
        mixed $model = null,
        ?int $createdBy = null,
        ?int $updatedBy = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ): void
    {
        if ($model) {
            $createdBy = $createdBy ?? data_get($model, 'created_by');
            $updatedBy = $updatedBy ?? data_get($model, 'updated_by');
            $createdAt = $createdAt ?? data_get($model, 'created_at');
            $updatedAt = $updatedAt ?? data_get($model, 'updated_at');
        }

        if ($createdBy) {
            $user = User::find($createdBy);
            $this->created_by_name = $user?->full_name ?? $user?->name ?? 'System';
        }

        if ($updatedBy) {
            $user = User::find($updatedBy);
            $this->updated_by_name = $user?->full_name ?? $user?->name ?? 'System';
        }

        $this->created_at = $this->formatDate($createdAt);
        $this->updated_at = $this->formatDate($updatedAt);
    }

    protected function formatDate(mixed $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value)->format('M d, Y H:i');
        } catch (\Exception $e) {
            return is_string($value) ? $value : null;
        }
    }

    public function render()
    {
        return view('livewire.components.audit-trail');
    }
}
