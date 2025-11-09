<?php

namespace Database\Factories\CRM;

use App\Models\CRM\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company' => $this->faker->company(),
            'status' => 'new',
            'score' => 0,
            'last_contacted_at' => null,
            'owner_id' => \App\Models\User::factory(),
        ];
    }
}
