<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('dashboard.client'));
});

test('staff roles redirect to the admin dashboard', function (string $role) {
    $user = User::factory()->create(['role' => $role]);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('dashboard.admin'));
})->with([
    'super_admin' => User::ROLE_SUPER_ADMIN,
    'admin' => User::ROLE_ADMIN,
    'operations_manager' => User::ROLE_OPERATIONS_MANAGER,
    'finance_officer' => User::ROLE_FINANCE_OFFICER,
    'customer_support' => User::ROLE_CUSTOMER_SUPPORT,
]);

test('trainers redirect to the trainer dashboard', function () {
    $user = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('dashboard.trainer'));
});