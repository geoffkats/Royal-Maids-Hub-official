<?php

use App\Models\Deployment;
use App\Models\Maid;
use App\Models\MaidContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

it('creates contracts from deployments', function () {
    $maid = Maid::factory()->create();

    Deployment::factory()->create([
        'maid_id' => $maid->id,
        'contract_start_date' => '2026-02-01',
        'contract_end_date' => '2026-05-01',
        'contract_type' => 'full-time',
        'status' => 'active',
    ]);

    Artisan::call('contracts:backfill-deployments');

    $this->assertDatabaseHas('maid_contracts', [
        'maid_id' => $maid->id,
        'contract_start_date' => '2026-02-01',
        'contract_end_date' => '2026-05-01',
        'contract_type' => 'full-time',
        'contract_status' => 'active',
    ]);
});

it('skips deployments that already have matching contracts', function () {
    $maid = Maid::factory()->create();

    Deployment::factory()->create([
        'maid_id' => $maid->id,
        'contract_start_date' => '2026-02-01',
        'contract_end_date' => '2026-05-01',
        'contract_type' => 'live-in',
        'status' => 'active',
    ]);

    MaidContract::factory()->create([
        'maid_id' => $maid->id,
        'contract_start_date' => '2026-02-01',
        'contract_end_date' => '2026-05-01',
        'contract_type' => 'live-in',
        'contract_status' => 'active',
    ]);

    Artisan::call('contracts:backfill-deployments');

    expect(MaidContract::where('maid_id', $maid->id)->count())->toBe(1);
});
