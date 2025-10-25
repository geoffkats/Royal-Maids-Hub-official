<?php

use App\Models\{Trainer, User};

test('admin can access trainers index', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->get(route('trainers.index'))
        ->assertStatus(200)
        ->assertSee(__('Trainers'));
});

test('non-admin cannot access trainers index', function () {
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

    $this->actingAs($client)
        ->get(route('trainers.index'))
        ->assertStatus(403);
});

test('admin can create trainer', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->get(route('trainers.create'))
        ->assertStatus(200);
});

test('admin can view trainer', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $trainer = Trainer::factory()->create();

    $this->actingAs($admin)
        ->get(route('trainers.show', $trainer))
        ->assertStatus(200)
        ->assertSee($trainer->user->name);
});

test('admin can edit trainer', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $trainer = Trainer::factory()->create();

    $this->actingAs($admin)
        ->get(route('trainers.edit', $trainer))
        ->assertStatus(200);
});
