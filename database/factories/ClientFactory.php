<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $tiers = ['basic', 'premium', 'enterprise'];
        $statuses = ['active', 'expired', 'pending', 'cancelled'];
        $maidTypes = ['housekeeper', 'house_manager', 'nanny', 'chef', 'elderly_caretaker'];

        $hasSubscription = fake()->boolean(70);
        $subscriptionStatus = $hasSubscription ? fake()->randomElement(['active', 'expired']) : 'pending';

        return [
            'user_id' => User::factory()->create(['role' => 'client']),
            'company_name' => fake()->boolean(40) ? fake()->company() : null,
            'contact_person' => fake()->name(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'secondary_phone' => fake()->boolean(30) ? fake()->e164PhoneNumber() : null,
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Kampala', 'Entebbe', 'Mukono', 'Wakiso', 'Jinja']),
            'district' => fake()->randomElement(['Kampala', 'Wakiso', 'Mukono', 'Jinja', 'Mbarara']),
            'subscription_tier' => fake()->randomElement($tiers),
            'subscription_status' => $subscriptionStatus,
            'subscription_start_date' => $hasSubscription ? fake()->dateTimeBetween('-1 year', 'now') : null,
            'subscription_end_date' => $hasSubscription 
                ? ($subscriptionStatus === 'active' 
                    ? fake()->dateTimeBetween('now', '+1 year')
                    : fake()->dateTimeBetween('-6 months', 'now'))
                : null,
            'preferred_maid_types' => fake()->randomElements($maidTypes, fake()->numberBetween(1, 3)),
            'special_requirements' => fake()->boolean(40) ? fake()->sentence() : null,
            'total_bookings' => fake()->numberBetween(0, 20),
            'active_bookings' => fake()->numberBetween(0, 3),
            'notes' => fake()->boolean(30) ? fake()->paragraph() : null,
        ];
    }

    /**
     * Indicate that the client has an active subscription.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_status' => 'active',
            'subscription_start_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'subscription_end_date' => fake()->dateTimeBetween('now', '+6 months'),
        ]);
    }

    /**
     * Indicate that the client has no subscription.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_status' => 'pending',
            'subscription_start_date' => null,
            'subscription_end_date' => null,
            'total_bookings' => 0,
            'active_bookings' => 0,
        ]);
    }
}
