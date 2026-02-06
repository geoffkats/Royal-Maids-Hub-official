<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientEvaluationQuestion>
 */
class ClientEvaluationQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => fake()->sentence(),
            'type' => 'rating',
            'sort_order' => fake()->numberBetween(0, 20),
            'is_required' => true,
            'is_active' => true,
        ];
    }
}
