<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows operations managers to access core operations pages', function () {
    $user = User::factory()->create(['role' => User::ROLE_OPERATIONS_MANAGER]);

    $this->actingAs($user)
        ->get(route('maids.index'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(route('programs.index'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(route('evaluations.index'))
        ->assertSuccessful();
});

it('allows finance officers to access finance context pages', function () {
    $user = User::factory()->create(['role' => User::ROLE_FINANCE_OFFICER]);

    $this->actingAs($user)
        ->get(route('contracts.index'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(route('reports'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(route('clients.index'))
        ->assertSuccessful();
});

it('allows customer support to access support pages', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER_SUPPORT]);

    $this->actingAs($user)
        ->get(route('clients.index'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(route('tickets.index'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(route('client-feedback.index'))
        ->assertSuccessful();
});
