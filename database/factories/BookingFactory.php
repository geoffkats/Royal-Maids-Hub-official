<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookingType = $this->faker->randomElement(['brokerage', 'long-term', 'part-time', 'full-time']);
        $status = $this->faker->randomElement(['pending', 'confirmed', 'active', 'completed', 'cancelled']);
        
        $startDate = $this->faker->dateTimeBetween('-6 months', '+1 month');
        $endDate = null;
        
        // Set end_date based on booking type
        if (in_array($bookingType, ['long-term', 'full-time'])) {
            $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');
        } elseif ($bookingType === 'part-time') {
            $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');
        } elseif ($bookingType === 'brokerage') {
            $endDate = $this->faker->dateTimeBetween($startDate, '+1 month');
        }

        // Random household data
        $hasChildren = $this->faker->randomElement(['Yes', 'No']);
        $hasElderly = $this->faker->randomElement(['Yes', 'No']);
        $hasPets = $this->faker->randomElement(['Yes with duties', 'Yes no duties', 'No']);

        // Package and pricing
        $package = \App\Models\Package::inRandomOrder()->first() ?? \App\Models\Package::factory()->create();
        $adults = $this->faker->numberBetween(1, 4);
        $children = $hasChildren === 'Yes' ? $this->faker->numberBetween(1, 3) : 0;
        $familySize = $adults + $children;
        $calculatedPrice = $package->calculatePrice($familySize);

        return [
            // Original V3.0 fields
            'client_id' => \App\Models\Client::factory(),
            'maid_id' => \App\Models\Maid::factory(),
            'package_id' => $package->id,
            'booking_type' => $bookingType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'notes' => $this->faker->optional()->sentence(),

            // Section 1: Contact Information
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('+256-7##-###-###'),
            'email' => $this->faker->unique()->safeEmail(),
            'country' => 'Uganda',
            'city' => $this->faker->randomElement(['Kampala', 'Entebbe', 'Wakiso', 'Mukono', 'Jinja']),
            'division' => $this->faker->randomElement(['Central', 'Makindye', 'Rubaga', 'Kawempe', 'Nakawa']),
            'parish' => $this->faker->randomElement(['Bukoto', 'Ntinda', 'Muyenga', 'Kololo', 'Naguru']),
            'national_id_path' => null, // File uploads handled separately

            // Section 2: Home & Environment
            'home_type' => $this->faker->randomElement(['Apartment', 'Bungalow', 'Maisonette', 'Other']),
            'bedrooms' => $this->faker->numberBetween(1, 6),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'outdoor_responsibilities' => $this->faker->randomElements(
                ['Sweeping', 'Gardening', 'None'],
                $this->faker->numberBetween(1, 2)
            ),
            'appliances' => $this->faker->randomElements(
                ['Washing Machine', 'Microwave', 'Oven', 'Blender', 'Airfryer', 'Generator'],
                $this->faker->numberBetween(2, 4)
            ),

            // Section 3: Household Composition
            'adults' => $adults,
            'family_size' => $familySize,
            'has_children' => $hasChildren,
            'children_ages' => $hasChildren === 'Yes' ? $this->faker->randomElement([
                '2 and 5 years',
                '3, 7, and 10 years',
                '1 year',
                '4 and 8 years',
            ]) : null,
            'has_elderly' => $hasElderly,
            'pets' => $hasPets,
            'pet_kind' => $hasPets !== 'No' ? $this->faker->randomElement(['Dog', 'Cat', 'Dog and Cat', 'Birds']) : null,
            'language' => $this->faker->randomElement(['English', 'Luganda', 'A mix of English and Luganda', 'Other']),
            'language_other' => null,

            // Section 4: Job Role & Expectations
            'service_tier' => in_array($package->name, ['Silver', 'Gold', 'Platinum']) ? $package->name : 'Silver', // Ensure valid enum value
            'calculated_price' => $calculatedPrice,
            'service_mode' => $this->faker->randomElement(['Live-in', 'Live-out']),
            'work_days' => $this->faker->randomElements(
                ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                $this->faker->numberBetween(5, 7)
            ),
            'working_hours' => $this->faker->randomElement([
                '8 AM - 5 PM',
                '7 AM - 4 PM',
                '9 AM - 6 PM',
                '6 AM - 3 PM',
            ]),
            'responsibilities' => $this->faker->randomElements(
                ['Cleaning', 'Laundry', 'Cooking', 'Childcare', 'Shopping', 'Elderly Care'],
                $this->faker->numberBetween(3, 5)
            ),
            'cuisine_type' => $this->faker->randomElement(['Local', 'Mixed', 'Other']),
            'atmosphere' => $this->faker->randomElement(['Quiet and calm', 'Busy and fast-paced']),
            'manage_tasks' => $this->faker->randomElement([
                'Verbal instructions',
                'Written list',
                'Worker should take initiative',
            ]),
            'unspoken_rules' => $this->faker->optional()->sentence(),
            'anything_else' => $this->faker->optional()->paragraph(),
        ];
    }
}
