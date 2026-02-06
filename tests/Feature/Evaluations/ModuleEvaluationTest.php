<?php

use App\Livewire\Evaluations\Create;
use App\Models\Maid;
use App\Models\Trainer;
use App\Models\User;
use Livewire\Livewire;

test('admin can save module evaluation scores', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $trainer = Trainer::factory()->create();
    $maid = Maid::factory()->create();

    $this->actingAs($admin);

    Livewire::test(Create::class)
        ->set('trainer_id', $trainer->id)
        ->set('maid_id', $maid->id)
        ->set('module', 'meal_preparation')
        ->set('module_scores', [
            'meal_preparation' => [
                'follows_recipe' => 4,
                'food_safety' => 5,
                'equipment_use' => 4,
                'balanced_meals' => 5,
                'kitchen_time_management' => 4,
            ],
        ])
        ->call('save')
        ->assertRedirect(route('evaluations.index'));

    $this->assertDatabaseHas('evaluations', [
        'trainer_id' => $trainer->id,
        'maid_id' => $maid->id,
        'scores->module_evaluation->module' => 'meal_preparation',
        'scores->module_evaluation->ratings->follows_recipe' => 4,
    ]);
});
