<?php

namespace Database\Factories;

use App\Models\Maid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maid>
 */
class MaidFactory extends Factory
{
    protected $model = Maid::class;

    public function definition(): array
    {
        $roles = ['housekeeper','house_manager','nanny','chef','elderly_caretaker','nakawere_caretaker'];
        $statuses = ['available','in-training','booked','deployed','absconded','terminated','on-leave'];
        $workStatuses = ['brokerage','long-term','part-time','full-time'];

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'mobile_number_2' => fake()->boolean(30) ? fake()->e164PhoneNumber() : null,
            'date_of_birth' => fake()->dateTimeBetween('-40 years','-18 years'),
            'date_of_arrival' => fake()->dateTimeBetween('-2 years','now'),
            'nationality' => 'Ugandan',
            'status' => fake()->randomElement($statuses),
            'secondary_status' => fake()->randomElement(['booked','available','deployed','on-leave',null]),
            'work_status' => fake()->randomElement($workStatuses),
            'nin_number' => strtoupper(fake()->unique()->bothify('CF###########')),
            'lc1_chairperson' => fake()->name().' - '.fake()->e164PhoneNumber(),
            'mother_name_phone' => fake()->name().' - '.fake()->e164PhoneNumber(),
            'father_name_phone' => fake()->name().' - '.fake()->e164PhoneNumber(),
            'marital_status' => fake()->randomElement(['single','married']),
            'number_of_children' => fake()->numberBetween(0,4),
            'tribe' => fake()->randomElement(['Muganda','Munyankole','Mukiga','Musoga','Munyoro','Mutooro','Mufumbira','Alur','Langi','Acholi','Iteso','Karamojong']),
            'village' => fake()->citySuffix(),
            'district' => fake()->randomElement(['Kampala','Wakiso','Mukono','Mbarara','Gulu','Arua']),
            'education_level' => fake()->randomElement(['P.7','S.4','Certificate','Diploma']),
            'experience_years' => fake()->numberBetween(0,10),
            'mother_tongue' => fake()->randomElement(['Luganda','Runyankole','Rukiga','Lusoga','Runyoro','Rutooro','Kiswahili']),
            'english_proficiency' => fake()->numberBetween(1,10),
            'role' => fake()->randomElement($roles),
            'previous_work' => fake()->boolean(60) ? fake()->paragraph() : null,
            'medical_status' => [
                'hepatitis_b' => ['result' => fake()->randomElement(['negative','positive']), 'date' => fake()->date()],
                'hiv' => ['result' => fake()->randomElement(['negative','positive']), 'date' => fake()->date()],
                'urine_hcg' => ['result' => fake()->randomElement(['negative','positive']), 'date' => fake()->date()],
            ],
            'profile_image' => null,
            'additional_notes' => fake()->boolean(40) ? fake()->sentence() : null,
        ];
    }
}
