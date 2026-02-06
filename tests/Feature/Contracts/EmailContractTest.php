<?php

use App\Livewire\Contracts\Show;
use App\Mail\ContractSummaryMail;
use App\Models\Client;
use App\Models\Deployment;
use App\Models\Maid;
use App\Models\MaidContract;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('emails contract summary to client', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);

    $clientUser = User::factory()->create([
        'role' => 'client',
        'email' => 'client@example.com',
    ]);
    $client = Client::factory()->for($clientUser)->create();
    $maid = Maid::factory()->create();

    Deployment::factory()
        ->active()
        ->for($maid)
        ->for($client)
        ->create([
            'deployment_date' => '2025-01-01',
        ]);

    $contract = MaidContract::factory()->create([
        'maid_id' => $maid->id,
        'contract_start_date' => '2025-01-01',
        'contract_end_date' => '2025-12-31',
        'contract_status' => 'active',
    ]);

    expect($contract->getClient())->not()->toBeNull();
    expect($contract->getClient()?->user?->email)->toBe($clientUser->email);

    Livewire::test(Show::class, ['contract' => $contract])
        ->call('emailContract');

    Mail::assertSent(ContractSummaryMail::class, function ($mail) use ($clientUser) {
        return $mail->hasTo($clientUser->email);
    });
});
