<?php

use App\Livewire\Maids\Edit;
use App\Models\Maid;
use App\Models\MaidContract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('creates a contract when saving a deployment', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $maid = Maid::factory()->create(['status' => 'available']);

    Livewire::actingAs($admin)
        ->test(Edit::class, ['maid' => $maid])
        ->set('status', 'deployed')
        ->set('deployment_date', '2026-02-04')
        ->set('deployment_location', 'Kampala - Kololo')
        ->set('deployment_client_name', 'Jane Client')
        ->set('deployment_client_phone', '0700000000')
        ->set('deployment_address', 'Kololo')
        ->set('contract_type', 'full-time')
        ->set('contract_start_date', '2026-02-04')
        ->set('contract_end_date', '2027-02-04')
        ->set('payment_status', 'pending')
        ->set('currency', 'UGX')
        ->call('saveDeployment');

    $this->assertDatabaseHas('maid_contracts', [
        'maid_id' => $maid->id,
        'contract_status' => 'active',
        'contract_type' => 'full-time',
        'contract_start_date' => '2026-02-04',
        'contract_end_date' => '2027-02-04',
    ]);

    expect(MaidContract::where('maid_id', $maid->id)->count())->toBe(1);
});
