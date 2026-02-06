<?php

namespace App\Livewire\Contracts;

use App\Models\MaidContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Mail\ContractSummaryMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Contract Details')]
class Show extends Component
{
    use AuthorizesRequests;

    public MaidContract $contract;

    public function mount(MaidContract $contract)
    {
        $this->contract = $contract;
    }

    public function restore(): void
    {
        $this->contract->restore();
        $this->contract->refresh();
        
        session()->flash('success', 'Contract restored successfully.');
    }

    public function recalculate(): void
    {
        $this->contract->recalculateDayCounts();
        $this->contract->refresh();
        
        session()->flash('success', 'Contract days recalculated successfully.');
    }

    public function emailContract(): void
    {
        $client = $this->contract->getClient();
        $recipient = $client?->user?->email;

        if (!$recipient) {
            session()->flash('error', __('Client email not found for this contract.'));
            return;
        }

        Mail::to($recipient)->send(new ContractSummaryMail(
            $this->contract,
            $client,
            $this->contract->maid
        ));

        session()->flash('success', __('Contract email sent successfully.'));
    }

    public function render()
    {
        return view('livewire.contracts.show', [
            'contract' => $this->contract->load(['maid']),
        ]);
    }
}
