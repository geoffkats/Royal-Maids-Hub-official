<?php

namespace Database\Factories;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Trainer>
 */
class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state([
                'role' => User::ROLE_TRAINER,
                'email_verified_at' => now(),
            ]),
            'specialization' => $this->faker->randomElement(['Housekeeping', 'Childcare', 'Elderly Care', 'Cooking']),
            'experience_years' => $this->faker->numberBetween(1, 15),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'bio' => $this->faker->sentences(3, true),
        ];
    }
}
