<?php

namespace App\Livewire\Contracts;

use App\Models\Maid;
use App\Models\MaidContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit Contract')]
class Edit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public MaidContract $contract;
    
    public $maid_id;
    public $contract_start_date;
    public $contract_end_date;
    public $contract_status;
    public $contract_type;
    public $notes;
    public array $contract_documents = [];

    protected function rules()
    {
        return [
            'maid_id' => 'required|exists:maids,id',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'contract_status' => 'required|in:pending,active,completed,terminated',
            'contract_type' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'contract_documents' => 'nullable|array',
            'contract_documents.*' => 'file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }

    public function mount(MaidContract $contract)
    {
        $this->contract = $contract;
        $this->maid_id = $contract->maid_id;
        $this->contract_start_date = $contract->contract_start_date?->format('Y-m-d');
        $this->contract_end_date = $contract->contract_end_date?->format('Y-m-d');
        $this->contract_status = $contract->contract_status;
        $this->contract_type = $contract->contract_type;
        $this->notes = $contract->notes;
    }

    public function update()
    {
        $this->validate();

        $this->contract->update([
            'maid_id' => $this->maid_id,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'contract_status' => $this->contract_status,
            'contract_type' => $this->contract_type,
            'notes' => $this->notes,
            'updated_by' => auth()->id(),
        ]);

        if (!empty($this->contract_documents)) {
            $paths = $this->contract->contract_documents ?? [];

            foreach ($this->contract_documents as $document) {
                $paths[] = $document->store('contracts/' . $this->contract->id, 'public');
            }

            $this->contract->update([
                'contract_documents' => $paths,
            ]);
        }

        // Recalculate day counts
        $this->contract->recalculateDayCounts();

        session()->flash('success', 'Contract updated successfully.');

        return redirect()->route('contracts.show', $this->contract);
    }

    public function render()
    {
        return view('livewire.contracts.edit', [
            'maids' => Maid::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }
}
