<?php

namespace App\Livewire\Deployments;

use App\Models\Deployment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Deployment')]
class Edit extends Component
{
    use AuthorizesRequests;

    public Deployment $deployment;

    public float|null $maid_salary = null;
    public float|null $client_payment = null;
    public float|null $service_paid = null;
    public ?string $salary_paid_date = null;
    public string $payment_status = 'pending';
    public string $currency = 'UGX';

    public function mount(Deployment $deployment)
    {
        $this->deployment = $deployment;
        $this->authorize('update', $deployment);

        $this->maid_salary = $deployment->maid_salary;
        $this->client_payment = $deployment->client_payment;
        $this->service_paid = $deployment->service_paid;
        $this->salary_paid_date = $deployment->salary_paid_date?->format('Y-m-d');
        $this->payment_status = $deployment->payment_status ?? 'pending';
        $this->currency = $deployment->currency ?? 'UGX';
    }

    public function updateFinancials(): void
    {
        $this->validate([
            'maid_salary' => 'nullable|numeric|min:0',
            'client_payment' => 'nullable|numeric|min:0',
            'service_paid' => 'nullable|numeric|min:0',
            'salary_paid_date' => 'nullable|date',
            'payment_status' => 'required|in:pending,partial,paid',
            'currency' => 'required|string|size:3',
        ]);

        $this->deployment->update([
            'maid_salary' => $this->maid_salary,
            'client_payment' => $this->client_payment,
            'service_paid' => $this->service_paid,
            'salary_paid_date' => $this->salary_paid_date,
            'payment_status' => $this->payment_status,
            'currency' => $this->currency,
            'updated_by' => auth()->id(),
        ]);

        $this->dispatch('deployment:updated');
        session()->flash('success', 'Deployment financial information updated successfully.');
    }

    public function render()
    {
        return view('livewire.deployments.edit');
    }
}
