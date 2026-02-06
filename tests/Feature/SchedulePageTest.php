<?php

use App\Models\Trainer;
use App\Models\User;

test('admin can view schedule page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/schedule')
        ->assertSuccessful();
});

test('trainer can view schedule page', function () {
    $trainer = Trainer::factory()->create();

    $this->actingAs($trainer->user)
        ->get('/schedule')
        ->assertSuccessful();
});
