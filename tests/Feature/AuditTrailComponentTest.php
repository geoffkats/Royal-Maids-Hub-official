<?php

use App\Livewire\Components\AuditTrail;
use App\Models\MaidContract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('derives audit fields from the model', function () {
    $user = User::factory()->create(['name' => 'Audit User']);
    $contract = MaidContract::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    Livewire::test(AuditTrail::class, ['model' => $contract])
        ->assertSet('created_by_name', 'Audit User')
        ->assertSet('updated_by_name', 'Audit User');
});
