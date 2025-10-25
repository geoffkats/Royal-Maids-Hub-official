<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Silver',
            'tier' => 'Standard',
            'description' => 'Intermediate level domestic work with comprehensive training',
            'base_price' => 300000,
            'base_family_size' => 3,
            'additional_member_cost' => 35000,
            'training_weeks' => 4,
            'training_includes' => ['Comprehensive Training'],
            'backup_days_per_year' => 21,
            'free_replacements' => 2,
            'evaluations_per_year' => 3,
            'features' => [
                'Intermediate level domestic work',
                '4 Weeks comprehensive training',
                'Free backup worker for up to 21 days in emergencies',
                '2 free replacements at full deployment cost',
                'Home deployment (partly shared cost)',
                '3 service evaluations in first year',
                'Continued performance monitoring',
            ],
            'is_active' => true,
            'sort_order' => 1,
        ];
    }

    /**
     * Silver package state.
     */
    public function silver(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Silver',
            'tier' => 'Standard',
            'description' => 'Intermediate level domestic work with comprehensive training',
            'base_price' => 300000,
            'base_family_size' => 3,
            'additional_member_cost' => 35000,
            'training_weeks' => 4,
            'training_includes' => ['Comprehensive Training'],
            'backup_days_per_year' => 21,
            'free_replacements' => 2,
            'evaluations_per_year' => 3,
            'features' => [
                'Intermediate level domestic work',
                '4 Weeks comprehensive training',
                'Free backup worker for up to 21 days in emergencies',
                '2 free replacements at full deployment cost',
                'Home deployment (partly shared cost)',
                '3 service evaluations in first year',
                'Continued performance monitoring',
            ],
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * Gold package state.
     */
    public function gold(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Gold',
            'tier' => 'Premium',
            'description' => 'Premium domestic services with advanced training',
            'base_price' => 500000,
            'base_family_size' => 3,
            'additional_member_cost' => 35000,
            'training_weeks' => 6,
            'training_includes' => [
                'Hospitality and Customer Service',
                'Basic Training (Driving and Swimming)',
                'Special Dishes (Cuisines)',
                'Advanced Child Care & Nanny Services',
            ],
            'backup_days_per_year' => 30,
            'free_replacements' => 2,
            'evaluations_per_year' => 3,
            'features' => [
                '6 Weeks Training Package',
                'Hospitality and Customer Service',
                'Basic Training (Driving and Swimming)',
                'Special Dishes (Cuisines)',
                'Advanced Child Care & Nanny Services',
                'Free backup workers for 30 days per year',
                '2 free replacements within 12 months',
                '3 service evaluations per year',
            ],
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }

    /**
     * Platinum package state.
     */
    public function platinum(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Platinum',
            'tier' => 'Elite',
            'description' => 'Elite services with specialized training for special needs',
            'base_price' => 1000000,
            'base_family_size' => 3,
            'additional_member_cost' => 35000,
            'training_weeks' => 8,
            'training_includes' => [
                'Children and Adults Care',
                'Nursing Basics',
                'Driving Fundamentals',
                'Self Defense',
                'Advanced Customer Service',
            ],
            'backup_days_per_year' => 45,
            'free_replacements' => 3,
            'evaluations_per_year' => 3,
            'features' => [
                'Special Needs Training',
                'Children and Adults Care',
                'Nursing Basics',
                'Driving Fundamentals',
                'Self Defense',
                'Advanced Customer Service',
                '3 service evaluations per year',
                'Premium priority support',
            ],
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}
