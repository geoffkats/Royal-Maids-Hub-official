<?php

namespace App\Livewire\Contracts;

use App\Models\MaidContract;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Contract Templates')]
class Templates extends Component
{
    use WithPagination;

    public string $templateType = '';

    public array $templates = [
        'full-time' => [
            'name' => 'Full-Time Contract',
            'description' => 'For full-time maids working 6 days per week',
            'type' => 'full-time',
            'duration_days' => 365,
            'working_days_per_week' => 6,
        ],
        'part-time' => [
            'name' => 'Part-Time Contract',
            'description' => 'For part-time maids working 3-4 days per week',
            'type' => 'part-time',
            'duration_days' => 180,
            'working_days_per_week' => 3,
        ],
        'live-in' => [
            'name' => 'Live-In Contract',
            'description' => 'For maids staying at client\'s residence',
            'type' => 'live-in',
            'duration_days' => 365,
            'working_days_per_week' => 6,
        ],
        'live-out' => [
            'name' => 'Live-Out Contract',
            'description' => 'For maids working day shifts and living elsewhere',
            'type' => 'live-out',
            'duration_days' => 180,
            'working_days_per_week' => 5,
        ],
        'seasonal' => [
            'name' => 'Seasonal Contract',
            'description' => 'For temporary or seasonal work',
            'type' => 'seasonal',
            'duration_days' => 90,
            'working_days_per_week' => 6,
        ],
    ];

    public function getContractStats(): array
    {
        return [
            'total_templates' => count($this->templates),
            'full_time_contracts' => MaidContract::where('contract_type', 'full-time')->count(),
            'part_time_contracts' => MaidContract::where('contract_type', 'part-time')->count(),
            'live_in_contracts' => MaidContract::where('contract_type', 'live-in')->count(),
            'live_out_contracts' => MaidContract::where('contract_type', 'live-out')->count(),
            'seasonal_contracts' => MaidContract::where('contract_type', 'seasonal')->count(),
        ];
    }

    public function getTemplateByType(string $type): ?array
    {
        return $this->templates[$type] ?? null;
    }

    public function render()
    {
        $stats = $this->getContractStats();

        return view('livewire.contracts.templates', [
            'stats' => $stats,
            'templates' => $this->templates,
        ]);
    }
}
