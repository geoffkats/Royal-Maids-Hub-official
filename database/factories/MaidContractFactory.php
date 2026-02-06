<?php

namespace Database\Factories;

use App\Models\Maid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaidContract>
 */
class MaidContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'maid_id' => Maid::factory(),
            'contract_start_date' => Carbon::today()->subDays(30),
            'contract_end_date' => Carbon::today()->addDays(60),
            'contract_status' => 'active',
            'contract_type' => fake()->randomElement(['full-time', 'part-time', 'live-in', 'live-out']),
            'worked_days' => fake()->numberBetween(0, 60),
            'pending_days' => fake()->numberBetween(0, 60),
        ];
    }
}
