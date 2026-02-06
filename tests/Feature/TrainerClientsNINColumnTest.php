<?php

test('trainer clients index displays nin column instead of company', function (): void {
    $admin = \App\Models\User::factory()->admin()->create();
    $client = \App\Models\Client::factory()->create([
        'identity_number' => 'NIN123456789',
        'identity_type' => 'nin',
        'company_name' => 'Old Company Name'
    ]);

    $this->actingAs($admin)
        ->get('/clients')
        ->assertSuccessful()
        ->assertSee('NIN/ID')
        ->assertSee('NIN123456789')
        ->assertDontSee('Old Company Name');
});
