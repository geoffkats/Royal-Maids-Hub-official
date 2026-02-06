<?php

test('tickets inbox route resolves for admin users', function (): void {
    $admin = \App\Models\User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/tickets/inbox')
        ->assertSuccessful();
});
