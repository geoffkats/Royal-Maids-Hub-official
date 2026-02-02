<?php

use App\Models\{Trainer, TrainerSidebarPermission, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('admin can view trainer permissions management page', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $trainer = Trainer::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.trainer-permissions'))
        ->assertStatus(200)
        ->assertSee('Manage Trainer Permissions');
});

test('trainer cannot access trainer permissions management page', function () {
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    $this->actingAs($trainerUser)
        ->get(route('admin.trainer-permissions'))
        ->assertStatus(403);
});

test('client cannot access trainer permissions management page', function () {
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

    $this->actingAs($client)
        ->get(route('admin.trainer-permissions'))
        ->assertStatus(403);
});

test('admin can grant permission to trainer', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    // Ensure no permission exists initially
    TrainerSidebarPermission::where('trainer_id', $trainer->id)
        ->where('sidebar_item', 'my_programs')
        ->delete();

    $this->actingAs($admin);

    Livewire::test(\App\Livewire\Admin\Trainers\ManagePermissions::class)
        ->set('permissions.' . $trainer->id . '.my_programs', true)
        ->call('savePermissions')
        ->assertHasNoErrors();

    $this->assertTrue(
        TrainerSidebarPermission::where('trainer_id', $trainer->id)
            ->where('sidebar_item', 'my_programs')
            ->exists()
    );
});

test('admin can revoke permission from trainer', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    // Create initial permission
    TrainerSidebarPermission::create([
        'trainer_id' => $trainer->id,
        'sidebar_item' => 'my_evaluations',
    ]);

    $this->actingAs($admin);

    Livewire::test(\App\Livewire\Admin\Trainers\ManagePermissions::class)
        ->set('permissions.' . $trainer->id . '.my_evaluations', false)
        ->call('savePermissions')
        ->assertHasNoErrors();

    $this->assertFalse(
        TrainerSidebarPermission::where('trainer_id', $trainer->id)
            ->where('sidebar_item', 'my_evaluations')
            ->exists()
    );
});

test('trainer with permission can access sidebar item route', function () {
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    // Grant permission
    TrainerSidebarPermission::create([
        'trainer_id' => $trainer->id,
        'sidebar_item' => 'my_programs',
    ]);

    $this->actingAs($trainerUser)
        ->get(route('programs.index'))
        ->assertStatus(200);
});

test('trainer without permission cannot access sidebar item route', function () {
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    // Ensure permission does not exist
    TrainerSidebarPermission::where('trainer_id', $trainer->id)
        ->where('sidebar_item', 'my_evaluations')
        ->delete();

    $this->actingAs($trainerUser)
        ->get(route('evaluations.index'))
        ->assertStatus(403);
});

test('trainer has access to method returns correct boolean', function () {
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    // Grant one permission
    TrainerSidebarPermission::create([
        'trainer_id' => $trainer->id,
        'sidebar_item' => 'my_programs',
    ]);

    $this->assertTrue($trainer->hasAccessTo('my_programs'));
    $this->assertFalse($trainer->hasAccessTo('reports'));
});

test('admin can access all sidebar items regardless of permissions', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    // Admin should be able to access all routes without needing trainer role
    $this->actingAs($admin)
        ->get(route('programs.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('evaluations.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('deployments.index'))
        ->assertStatus(200);
});

test('admin can search and filter trainers', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $user1 = User::factory()->create(['name' => 'John Trainer', 'role' => User::ROLE_TRAINER]);
    $user2 = User::factory()->create(['name' => 'Jane Smith', 'role' => User::ROLE_TRAINER]);
    $trainer1 = Trainer::factory()->create(['user_id' => $user1->id]);
    $trainer2 = Trainer::factory()->create(['user_id' => $user2->id]);

    $this->actingAs($admin);

    Livewire::test(\App\Livewire\Admin\Trainers\ManagePermissions::class)
        ->set('search', 'John')
        ->assertSee('John Trainer');
});

test('sidebar items are displayed correctly for trainers', function () {
    $trainerUser = User::factory()->create(['role' => User::ROLE_TRAINER]);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);

    // Grant specific permissions
    TrainerSidebarPermission::create([
        'trainer_id' => $trainer->id,
        'sidebar_item' => 'my_programs',
    ]);
    TrainerSidebarPermission::create([
        'trainer_id' => $trainer->id,
        'sidebar_item' => 'weekly_board',
    ]);

    // Test by viewing dashboard and checking sidebar items
    $this->actingAs($trainerUser)
        ->get(route('dashboard.trainer'))
        ->assertStatus(200);

    // Should have access to programs and weekly board
    $this->assertTrue($trainer->hasAccessTo('my_programs'));
    $this->assertTrue($trainer->hasAccessTo('weekly_board'));
    $this->assertFalse($trainer->hasAccessTo('my_evaluations'));
});

