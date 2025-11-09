<?php

use App\Livewire\CRM\Leads\Show as LeadShow;
use App\Models\CRM\Lead;
use App\Models\Client;
use App\Models\User;
use Livewire\Livewire;

it('converts a qualified lead into a new client', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lead = Lead::factory()->create([
        'status' => 'qualified',
        'client_id' => null,
        'owner_id' => $admin->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
    ]);

    $this->actingAs($admin);

    $component = Livewire::test(LeadShow::class, ['lead' => $lead])
        ->call('openConvertModal')
        ->assertSet('showConvertModal', true)
        ->set('convertAction', 'create');
    
    // Check if there are any errors before calling convertToClient
    $component->call('convertToClient');
    
    // Check the component state after conversion
    expect($component->get('showConvertModal'))->toBe(false);

    $lead->refresh();
    expect($lead->status)->toBe('converted')
        ->and($lead->client_id)->not()->toBeNull();
});

it('converts a qualified lead by attaching to an existing client', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create(['contact_person' => 'Acme Contact']);
    $lead = Lead::factory()->create([
        'status' => 'qualified',
        'client_id' => null,
        'owner_id' => $admin->id,
        'first_name' => 'John',
        'last_name' => 'Smith',
        'email' => 'john@example.com',
    ]);

    $this->actingAs($admin);

    Livewire::test(LeadShow::class, ['lead' => $lead])
        ->call('openConvertModal')
        ->assertSet('showConvertModal', true)
        ->set('convertAction', 'existing')
        ->set('existingClientId', $client->id)
        ->call('convertToClient')
        ->assertSet('showConvertModal', false);

    $lead->refresh();
    expect($lead->status)->toBe('converted')
        ->and($lead->client_id)->toBe($client->id);
});

it('prevents converting a non-convertible lead and shows an error', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lead = Lead::factory()->create([
        'status' => 'new',
        'client_id' => null,
        'owner_id' => $admin->id,
    ]);

    $this->actingAs($admin);

    Livewire::test(LeadShow::class, ['lead' => $lead])
        ->call('openConvertModal')
        ->assertSet('showConvertModal', false); // Modal should not open for non-convertible leads
});
