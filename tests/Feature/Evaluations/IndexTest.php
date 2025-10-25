<?php

use App\Models\User;
use App\Models\Trainer;
use App\Models\Maid;
use App\Models\Evaluation;
use function Pest\Laravel\{actingAs, get};

test('admin can access evaluations index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    actingAs($admin)
        ->get(route('evaluations.index'))
        ->assertOk();
});

test('trainer can access evaluations index', function () {
    $trainer = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainer->id]);
    
    actingAs($trainer)
        ->get(route('evaluations.index'))
        ->assertOk();
});

test('client cannot access evaluations index', function () {
    $client = User::factory()->create(['role' => 'client']);
    
    actingAs($client)
        ->get(route('evaluations.index'))
        ->assertForbidden();
});

test('admin can see all evaluations', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();
    
    $eval1 = Evaluation::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    $eval2 = Evaluation::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($admin)
        ->get(route('evaluations.index'))
        ->assertSee($maid->first_name);
});

test('trainer can see only their own evaluations', function () {
    $trainerUser1 = User::factory()->create(['role' => 'trainer']);
    $trainer1 = Trainer::factory()->create(['user_id' => $trainerUser1->id]);
    
    $trainerUser2 = User::factory()->create(['role' => 'trainer']);
    $trainer2 = Trainer::factory()->create(['user_id' => $trainerUser2->id]);
    
    $maid1 = Maid::factory()->create(['first_name' => 'OwnEval']);
    $maid2 = Maid::factory()->create(['first_name' => 'OtherEval']);
    
    $ownEval = Evaluation::factory()->create([
        'trainer_id' => $trainer1->id,
        'maid_id' => $maid1->id,
    ]);
    
    $otherEval = Evaluation::factory()->create([
        'trainer_id' => $trainer2->id,
        'maid_id' => $maid2->id,
    ]);
    
    actingAs($trainerUser1)
        ->get(route('evaluations.index'))
        ->assertSee('OwnEval')
        ->assertDontSee('OtherEval');
});

test('admin can access create evaluation page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    actingAs($admin)
        ->get(route('evaluations.create'))
        ->assertOk();
});

test('trainer can access create evaluation page', function () {
    $trainer = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainer->id]);
    
    actingAs($trainer)
        ->get(route('evaluations.create'))
        ->assertOk();
});

test('client cannot access create evaluation page', function () {
    $client = User::factory()->create(['role' => 'client']);
    
    actingAs($client)
        ->get(route('evaluations.create'))
        ->assertForbidden();
});

test('admin can view any evaluation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();
    
    $evaluation = Evaluation::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($admin)
        ->get(route('evaluations.show', $evaluation))
        ->assertOk();
});

test('trainer can view their own evaluation', function () {
    $trainerUser = User::factory()->create(['role' => 'trainer']);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);
    $maid = Maid::factory()->create();
    
    $evaluation = Evaluation::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser)
        ->get(route('evaluations.show', $evaluation))
        ->assertOk();
});

test('trainer cannot view other trainer evaluation', function () {
    $trainerUser1 = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainerUser1->id]);
    
    $trainerUser2 = User::factory()->create(['role' => 'trainer']);
    $trainer2 = Trainer::factory()->create(['user_id' => $trainerUser2->id]);
    
    $maid = Maid::factory()->create();
    
    $evaluation = Evaluation::factory()->create([
        'trainer_id' => $trainer2->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser1)
        ->get(route('evaluations.show', $evaluation))
        ->assertForbidden();
});

test('admin can access edit page for any evaluation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();
    
    $evaluation = Evaluation::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($admin)
        ->get(route('evaluations.edit', $evaluation))
        ->assertOk();
});

test('trainer can access edit page for their own evaluation', function () {
    $trainerUser = User::factory()->create(['role' => 'trainer']);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);
    $maid = Maid::factory()->create();
    
    $evaluation = Evaluation::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser)
        ->get(route('evaluations.edit', $evaluation))
        ->assertOk();
});

test('trainer cannot access edit page for other trainer evaluation', function () {
    $trainerUser1 = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainerUser1->id]);
    
    $trainerUser2 = User::factory()->create(['role' => 'trainer']);
    $trainer2 = Trainer::factory()->create(['user_id' => $trainerUser2->id]);
    
    $maid = Maid::factory()->create();
    
    $evaluation = Evaluation::factory()->create([
        'trainer_id' => $trainer2->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser1)
        ->get(route('evaluations.edit', $evaluation))
        ->assertForbidden();
});
