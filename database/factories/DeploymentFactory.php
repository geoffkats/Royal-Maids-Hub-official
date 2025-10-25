<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Deployment;
use App\Models\Maid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deployment>
 */
class DeploymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-6 months', 'now');
        $hasEndDate = fake()->boolean(30);
        
        return [
            'maid_id' => Maid::factory(),
            'client_id' => fake()->boolean(70) ? Client::factory() : null,
            'deployment_date' => $start,
            'deployment_location' => fake()->randomElement([
                'Kampala - Kololo', 'Kampala - Nakasero', 'Kampala - Bugolobi', 
                'Entebbe', 'Kira', 'Wakiso', 'Mukono'
            ]),
            'client_name' => fake()->name(),
            'client_phone' => '07' . fake()->numberBetween(10000000, 99999999),
            'deployment_address' => fake()->address(),
            'monthly_salary' => fake()->numberBetween(300000, 1500000),
            'contract_type' => fake()->randomElement(['full-time', 'part-time', 'live-in', 'live-out']),
            'contract_start_date' => $start,
            'contract_end_date' => $hasEndDate ? fake()->dateTimeBetween($start, '+1 year') : null,
            'special_instructions' => fake()->optional()->sentence(),
            'notes' => fake()->optional()->paragraph(),
            'status' => $hasEndDate ? fake()->randomElement(['active', 'completed', 'terminated']) : 'active',
            'end_date' => $hasEndDate ? fake()->dateTimeBetween($start, 'now') : null,
            'end_reason' => $hasEndDate ? fake()->optional()->sentence() : null,
        ];
    }

    /**
     * Indicate that the deployment is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'end_date' => null,
            'end_reason' => null,
        ]);
    }

    /**
     * Indicate that the deployment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'end_date' => fake()->dateTimeBetween($attributes['deployment_date'], 'now'),
            'end_reason' => 'Contract completed successfully',
        ]);
    }

    /**
     * Indicate that the deployment was terminated.
     */
    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'terminated',
            'end_date' => fake()->dateTimeBetween($attributes['deployment_date'], 'now'),
            'end_reason' => fake()->randomElement([
                'Client dissatisfaction',
                'Maid requested termination',
                'Contract breach',
                'Relocation',
            ]),
        ]);
    }
}
