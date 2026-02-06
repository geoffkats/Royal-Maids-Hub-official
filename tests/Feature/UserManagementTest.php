<?php

use App\Livewire\Users\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('allows super admins to access user management', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->get('/users')
        ->assertSuccessful();
});

it('blocks non super admins from user management', function () {
    $trainer = User::factory()->create(['role' => User::ROLE_TRAINER]);

    $this->actingAs($trainer)
        ->get('/users')
        ->assertForbidden();
});

it('creates users with assigned roles', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('name', 'Support User')
        ->set('email', 'support@royalmaids.test')
        ->set('role', User::ROLE_CUSTOMER_SUPPORT)
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('emailVerified', true)
        ->call('createUser');

    $this->assertDatabaseHas('users', [
        'email' => 'support@royalmaids.test',
        'role' => User::ROLE_CUSTOMER_SUPPORT,
    ]);
});

it('deactivates a user account', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $target = User::factory()->create(['is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('openDeactivateModal', $target->id)
        ->call('toggleActive');

    $this->assertDatabaseHas('users', [
        'id' => $target->id,
        'is_active' => false,
    ]);
});

it('resets a user password', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $target = User::factory()->create(['password' => Hash::make('old-password')]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('openResetModal', $target->id)
        ->set('resetPassword', 'new-password123')
        ->set('resetPassword_confirmation', 'new-password123')
        ->call('resetUserPassword');

    $target->refresh();

    expect(Hash::check('new-password123', $target->password))->toBeTrue();
});

it('soft deletes a user account', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $target = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('openDeleteModal', $target->id)
        ->call('deleteUser');

    $this->assertSoftDeleted('users', ['id' => $target->id]);
});
