<?php

namespace App\Livewire\Contracts;

use App\Models\MaidContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Mail\ContractSummaryMail;
use Illuminate\Support\Facades\Cache;
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
        $lockKey = 'contract-email:' . $this->contract->id;
        $lockTtl = 60;

        if (!Cache::add($lockKey, true, $lockTtl)) {
            session()->flash('error', __('Contract email was just sent. Please wait a minute before trying again.'));
            return;
        }

        $client = $this->contract->getClient();
        $recipient = $client?->user?->email;

        if (!$recipient) {
            Cache::forget($lockKey);
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
