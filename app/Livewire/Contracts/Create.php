<?php

namespace App\Livewire\Contracts;

use App\Models\Maid;
use App\Models\MaidContract;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Create Contract')]
class Create extends Component
{
    use AuthorizesRequests, WithFileUploads;

    #[Url]
    public string $template = '';

    public ?int $maid_id = null;
    public ?string $contract_start_date = null;
    public ?string $contract_end_date = null;
    public string $contract_status = 'pending';
    public string $contract_type = 'standard';
    public ?string $notes = null;
    public ?int $template_duration_days = null;
    public array $contract_documents = [];

    public function mount(): void
    {
        $this->contract_start_date = now()->format('Y-m-d');

        if ($this->template) {
            $this->applyTemplate($this->template);
        }
    }

    public function updatedTemplate(string $template): void
    {
        if ($template) {
            $this->applyTemplate($template);
        }
    }

    public function updatedContractStartDate(): void
    {
        if ($this->template_duration_days) {
            $this->contract_end_date = Carbon::parse($this->contract_start_date)
                ->addDays($this->template_duration_days)
                ->format('Y-m-d');
        }
    }

    protected function applyTemplate(string $templateKey): void
    {
        $template = $this->getTemplateConfig($templateKey);
        if (!$template) {
            return;
        }

        $this->contract_type = $template['type'];
        $this->template_duration_days = $template['duration_days'];

        $startDate = $this->contract_start_date
            ? Carbon::parse($this->contract_start_date)
            : Carbon::today();

        $this->contract_end_date = $startDate->copy()
            ->addDays($template['duration_days'])
            ->format('Y-m-d');
    }

    protected function getTemplateConfig(string $templateKey): ?array
    {
        $templates = [
            'full-time' => [
                'type' => 'full-time',
                'duration_days' => 365,
            ],
            'part-time' => [
                'type' => 'part-time',
                'duration_days' => 180,
            ],
            'live-in' => [
                'type' => 'live-in',
                'duration_days' => 365,
            ],
            'live-out' => [
                'type' => 'live-out',
                'duration_days' => 180,
            ],
            'seasonal' => [
                'type' => 'seasonal',
                'duration_days' => 90,
            ],
        ];

        return $templates[$templateKey] ?? null;
    }

    public function rules(): array
    {
        return [
            'maid_id' => 'required|exists:maids,id',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'contract_status' => 'required|in:pending,active,completed,terminated',
            'contract_type' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'contract_documents' => 'nullable|array',
            'contract_documents.*' => 'file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $contract = MaidContract::create([
            'maid_id' => $this->maid_id,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'contract_status' => $this->contract_status,
            'contract_type' => $this->contract_type,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        if (!empty($this->contract_documents)) {
            $paths = [];

            foreach ($this->contract_documents as $document) {
                $paths[] = $document->store('contracts/' . $contract->id, 'public');
            }

            $contract->update([
                'contract_documents' => $paths,
            ]);
        }

        // Calculate initial day counts
        $contract->recalculateDayCounts();

        session()->flash('success', 'Contract created successfully.');
        $this->redirect(route('contracts.show', $contract), navigate: true);
    }

    public function render()
    {
        $maids = Maid::orderBy('first_name')->orderBy('last_name')->get();
        
        return view('livewire.contracts.create', [
            'maids' => $maids,
        ]);
    }
}
