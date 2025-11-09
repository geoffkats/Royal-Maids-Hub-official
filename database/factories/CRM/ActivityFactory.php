<?php

namespace Database\Factories\CRM;

use App\Models\CRM\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['call','email','meeting','note']),
            'subject' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'due_date' => now()->addDays(rand(-5, 5)),
            'status' => 'pending',
            'priority' => $this->faker->randomElement(['low','medium','high']),
            'related_type' => \App\Models\CRM\Lead::class,
            'related_id' => function(){ return \App\Models\CRM\Lead::factory()->create()->id; },
            'assigned_to' => null,
            'owner_id' => null,
            'created_by' => null,
        ];
    }
}
