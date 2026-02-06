<?php

use App\Livewire\Contracts\Create;
use App\Livewire\Contracts\Index;
use App\Models\Client;
use App\Models\Deployment;
use App\Models\Maid;
use App\Models\MaidContract;
use Carbon\Carbon;
use Livewire\Livewire;

afterEach(function () {
    Carbon::setTestNow();
});

it('prefills contract fields from template', function () {
    Carbon::setTestNow('2024-01-01');

    $expectedEndDate = Carbon::parse('2024-01-01')
        ->addDays(365)
        ->format('Y-m-d');

    Livewire::test(Create::class)
        ->set('template', 'full-time')
        ->assertSet('contract_type', 'full-time')
        ->assertSet('contract_end_date', $expectedEndDate);
});

it('recalculates end date when start date changes', function () {
    Livewire::test(Create::class)
        ->set('template', 'seasonal')
        ->set('contract_start_date', '2024-06-01')
        ->assertSet('contract_end_date', Carbon::parse('2024-06-01')->addDays(90)->format('Y-m-d'));
});

it('shows client name on contracts index', function () {
    $maid = Maid::factory()->create();
    $client = Client::factory()->create(['contact_person' => 'Jane Client']);

    Deployment::factory()->active()->create([
        'maid_id' => $maid->id,
        'client_id' => $client->id,
        'status' => 'active',
    ]);

    MaidContract::factory()->create([
        'maid_id' => $maid->id,
    ]);

    Livewire::test(Index::class)
        ->assertSee('Jane Client');
});
