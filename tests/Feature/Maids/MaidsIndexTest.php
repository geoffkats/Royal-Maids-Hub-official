<?php

use App\Models\Maid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to view maids index', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    Maid::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('maids.index'))
        ->assertOk()
        ->assertSee('Maids');
});

it('forbids client from accessing maids index', function () {
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

    $this->actingAs($client)
        ->get(route('maids.index'))
        ->assertStatus(403);
});

it('filters maids by status and search', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $target = Maid::factory()->create([
        'first_name' => 'Test',
        'last_name' => 'AvailablePerson',
        'status' => 'available',
        'role' => 'nanny',
    ]);

    // A non-matching record
    Maid::factory()->create([
        'first_name' => 'Other',
        'last_name' => 'DeployedPerson',
        'status' => 'deployed',
        'role' => 'housekeeper',
    ]);

    $this->actingAs($admin)
        ->get(route('maids.index', ['status' => 'available', 'search' => 'Test']))
        ->assertOk()
        ->assertSee('Test AvailablePerson')
        ->assertDontSee('DeployedPerson');
});
