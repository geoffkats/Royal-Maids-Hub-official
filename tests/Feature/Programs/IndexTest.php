<?php

use App\Models\User;
use App\Models\Trainer;
use App\Models\Maid;
use App\Models\TrainingProgram;
use function Pest\Laravel\{actingAs, get};

test('admin can access training programs index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    actingAs($admin)
        ->get(route('programs.index'))
        ->assertOk();
});

test('trainer can access training programs index', function () {
    $trainer = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainer->id]);
    
    actingAs($trainer)
        ->get(route('programs.index'))
        ->assertOk();
});

test('client cannot access training programs index', function () {
    $client = User::factory()->create(['role' => 'client']);
    
    actingAs($client)
        ->get(route('programs.index'))
        ->assertForbidden();
});

test('admin can see all training programs', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();
    
    $program1 = TrainingProgram::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
        'program_type' => 'Housekeeping Training',
    ]);
    
    $program2 = TrainingProgram::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
        'program_type' => 'Childcare Training',
    ]);
    
    actingAs($admin)
        ->get(route('programs.index'))
        ->assertSee($program1->program_type)
        ->assertSee($program2->program_type);
});

test('trainer can see only their own training programs', function () {
    $trainerUser1 = User::factory()->create(['role' => 'trainer']);
    $trainer1 = Trainer::factory()->create(['user_id' => $trainerUser1->id]);
    
    $trainerUser2 = User::factory()->create(['role' => 'trainer']);
    $trainer2 = Trainer::factory()->create(['user_id' => $trainerUser2->id]);
    
    $maid = Maid::factory()->create();
    
    $ownProgram = TrainingProgram::factory()->create([
        'trainer_id' => $trainer1->id,
        'maid_id' => $maid->id,
        'program_type' => 'Own Program',
    ]);
    
    $otherProgram = TrainingProgram::factory()->create([
        'trainer_id' => $trainer2->id,
        'maid_id' => $maid->id,
        'program_type' => 'Other Trainer Program',
    ]);
    
    actingAs($trainerUser1)
        ->get(route('programs.index'))
        ->assertSee($ownProgram->program_type)
        ->assertDontSee($otherProgram->program_type);
});

test('admin can access create training program page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    actingAs($admin)
        ->get(route('programs.create'))
        ->assertOk();
});

test('trainer can access create training program page', function () {
    $trainer = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainer->id]);
    
    actingAs($trainer)
        ->get(route('programs.create'))
        ->assertOk();
});

test('client cannot access create training program page', function () {
    $client = User::factory()->create(['role' => 'client']);
    
    actingAs($client)
        ->get(route('programs.create'))
        ->assertForbidden();
});

test('admin can view any training program', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();
    
    $program = TrainingProgram::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
        'program_type' => 'Admin View Test',
    ]);
    
    actingAs($admin)
        ->get(route('programs.show', $program))
        ->assertOk()
        ->assertSee($program->program_type);
});

test('trainer can view their own training program', function () {
    $trainerUser = User::factory()->create(['role' => 'trainer']);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);
    $maid = Maid::factory()->create();
    
    $program = TrainingProgram::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
        'program_type' => 'Trainer Own Program',
    ]);
    
    actingAs($trainerUser)
        ->get(route('programs.show', $program))
        ->assertOk()
        ->assertSee($program->program_type);
});

test('trainer cannot view other trainer program', function () {
    $trainerUser1 = User::factory()->create(['role' => 'trainer']);
    $trainer1 = Trainer::factory()->create(['user_id' => $trainerUser1->id]);
    
    $trainerUser2 = User::factory()->create(['role' => 'trainer']);
    $trainer2 = Trainer::factory()->create(['user_id' => $trainerUser2->id]);
    
    $maid = Maid::factory()->create();
    
    $program = TrainingProgram::factory()->create([
        'trainer_id' => $trainer2->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser1)
        ->get(route('programs.show', $program))
        ->assertForbidden();
});

test('admin can access edit page for any training program', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();
    
    $program = TrainingProgram::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($admin)
        ->get(route('programs.edit', $program))
        ->assertOk();
});

test('trainer can access edit page for their own program', function () {
    $trainerUser = User::factory()->create(['role' => 'trainer']);
    $trainer = Trainer::factory()->create(['user_id' => $trainerUser->id]);
    $maid = Maid::factory()->create();
    
    $program = TrainingProgram::factory()->create([
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser)
        ->get(route('programs.edit', $program))
        ->assertOk();
});

test('trainer cannot access edit page for other trainer program', function () {
    $trainerUser1 = User::factory()->create(['role' => 'trainer']);
    Trainer::factory()->create(['user_id' => $trainerUser1->id]);
    
    $trainerUser2 = User::factory()->create(['role' => 'trainer']);
    $trainer2 = Trainer::factory()->create(['user_id' => $trainerUser2->id]);
    
    $maid = Maid::factory()->create();
    
    $program = TrainingProgram::factory()->create([
        'trainer_id' => $trainer2->id,
        'maid_id' => $maid->id,
    ]);
    
    actingAs($trainerUser1)
        ->get(route('programs.edit', $program))
        ->assertForbidden();
});
