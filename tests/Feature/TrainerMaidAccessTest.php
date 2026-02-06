<?php

declare(strict_types=1);

use App\Models\Maid;
use App\Models\Trainer;
use App\Models\User;

test('trainer with maids permission can access maid show page', function () {
    $user = User::factory()->create(['role' => 'trainer']);
    $trainer = Trainer::factory()->create(['user_id' => $user->id]);
    
    // Give trainer the 'maids' permission
    $trainer->sidebarPermissions()->create([
        'sidebar_item' => 'maids',
    ]);

    $maid = Maid::factory()->create([
        'status' => 'available',
    ]);

    $response = $this->actingAs($user)
        ->get("/trainer/maids/{$maid->id}");

    $response->assertSuccessful()
        ->assertSee($maid->full_name);
});

test('trainer without maids permission cannot access maid show page', function () {
    $user = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $user->id]);
    
    $maid = Maid::factory()->create([
        'status' => 'available',
    ]);

    $response = $this->actingAs($user)
        ->get("/trainer/maids/{$maid->id}");

    $response->assertForbidden();
});

test('trainer can view maids with any status', function () {
    $user = User::factory()->create(['role' => 'trainer']);
    $trainer = Trainer::factory()->create(['user_id' => $user->id]);
    
    // Give trainer the 'maids' permission
    $trainer->sidebarPermissions()->create([
        'sidebar_item' => 'maids',
    ]);

    // Test different statuses
    $statuses = ['available', 'deployed', 'in-training', 'on-leave'];

    foreach ($statuses as $status) {
        $maid = Maid::factory()->create([
            'status' => $status,
        ]);

        $response = $this->actingAs($user)
            ->get("/trainer/maids/{$maid->id}");

        $response->assertSuccessful()
            ->assertSee($maid->full_name);
    }
});
