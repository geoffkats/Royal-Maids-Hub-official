<?php

use App\Livewire\ClientEvaluations\Create;
use App\Models\Client;
use App\Models\ClientEvaluation;
use App\Models\User;
use Livewire\Livewire;

test('admin can create a client evaluation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create();

    $this->actingAs($admin);

    Livewire::test(Create::class)
        ->set('client_id', $client->id)
        ->set('evaluation_date', '2025-01-01')
        ->set('evaluation_type', '3_months')
        ->set('overall_rating', 4)
        ->set('strengths', 'Communication and responsiveness')
        ->set('areas_for_improvement', 'Faster issue resolution')
        ->set('comments', 'Client is satisfied with service')
        ->call('save')
        ->assertRedirect(route('client-evaluations.index'));

    $this->assertDatabaseHas('client_evaluations', [
        'client_id' => $client->id,
        'evaluation_type' => '3_months',
        'next_evaluation_date' => '2025-04-01',
    ]);

    expect(ClientEvaluation::count())->toBe(1);
});
