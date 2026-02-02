<?php

namespace Database\Factories;

use App\Models\{TrainingProgram, Trainer, Maid};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TrainingProgram>
 */
class TrainingProgramFactory extends Factory
{
    protected $model = TrainingProgram::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-3 months', '+1 month');
        $endDate = $this->faker->optional(0.7)->dateTimeBetween($startDate, '+2 months');

        return [
            'trainer_id' => Trainer::factory(),
            'maid_id' => Maid::factory(),
            'program_type' => $this->faker->randomElement([
                'Orientation',
                'Housekeeping Training',
                'Childcare Training',
                'Cooking Training',
                'Elderly Care Training',
                'Language Training',
                'Safety & First Aid',
                'Professional Development',
                'Customer Service',
            ]),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['scheduled', 'in-progress', 'completed', 'cancelled']),
            'notes' => $this->faker->optional(0.6)->paragraph(),
            'hours_completed' => $this->faker->numberBetween(0, 40),
            'hours_required' => $this->faker->randomElement([20, 40, 60, 80]),
        ];
    }

    public function scheduled(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'hours_completed' => 0,
        ]);
    }

    public function inProgress(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in-progress',
            'hours_completed' => $this->faker->numberBetween(5, 30),
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'hours_completed' => $attributes['hours_required'] ?? 40,
        ]);
    }
}
