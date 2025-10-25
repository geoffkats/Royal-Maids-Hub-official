<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Trainer;
use App\Models\Maid;
use App\Models\TrainingProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        // Generate comprehensive scores for all three sections
        $personalityScores = [
            'confidence' => $this->faker->numberBetween(2, 5),
            'self_awareness' => $this->faker->numberBetween(2, 5),
            'emotional_stability' => $this->faker->numberBetween(2, 5),
            'growth_mindset' => $this->faker->numberBetween(2, 5),
            'comments' => $this->faker->sentence(10),
        ];

        $behaviorScores = [
            'punctuality' => $this->faker->numberBetween(2, 5),
            'respect_for_instructions' => $this->faker->numberBetween(2, 5),
            'work_ownership' => $this->faker->numberBetween(2, 5),
            'interpersonal_conduct' => $this->faker->numberBetween(2, 5),
            'comments' => $this->faker->sentence(10),
        ];

        $performanceScores = [
            'alertness' => $this->faker->numberBetween(2, 5),
            'first_aid_ability' => $this->faker->numberBetween(2, 5),
            'security_consciousness' => $this->faker->numberBetween(2, 5),
            'comments' => $this->faker->sentence(10),
        ];

        $scores = [
            'personality' => $personalityScores,
            'behavior' => $behaviorScores,
            'performance' => $performanceScores,
        ];

        // Calculate overall rating
        $allNumericScores = array_merge(
            array_filter($personalityScores, 'is_numeric'),
            array_filter($behaviorScores, 'is_numeric'),
            array_filter($performanceScores, 'is_numeric')
        );
        $overallRating = round(array_sum($allNumericScores) / count($allNumericScores), 1);

        return [
            'trainer_id' => Trainer::factory(),
            'maid_id' => Maid::factory(),
            'program_id' => TrainingProgram::factory(),
            'evaluation_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'scores' => $scores,
            'overall_rating' => $overallRating,
            'general_comments' => $this->faker->paragraph(),
            'strengths' => $this->faker->sentence(12),
            'areas_for_improvement' => $this->faker->sentence(12),
        ];
    }

    /**
     * Evaluation with excellent scores
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'scores' => [
                'personality' => [
                    'confidence' => 5,
                    'self_awareness' => 5,
                    'emotional_stability' => 5,
                    'growth_mindset' => 5,
                    'comments' => 'Confident, Clearly self aware, Stable, Grown',
                ],
                'behavior' => [
                    'punctuality' => 5,
                    'respect_for_instructions' => 5,
                    'work_ownership' => 5,
                    'interpersonal_conduct' => 5,
                    'comments' => 'Punctual, Good, Excellent, Good',
                ],
                'performance' => [
                    'alertness' => 5,
                    'first_aid_ability' => $this->faker->numberBetween(4, 5),
                    'security_consciousness' => 5,
                    'comments' => 'Very alert, Improved, Improved',
                ],
            ],
            'overall_rating' => 5.0,
            'general_comments' => 'Exceptional performance across all areas. Demonstrates strong work ethic and professional attitude.',
            'strengths' => 'Outstanding in all evaluation categories. Shows excellent potential for advanced responsibilities.',
            'areas_for_improvement' => 'Continue building on current strengths.',
        ]);
    }

    /**
     * Evaluation with average scores
     */
    public function average(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'scores' => [
                'personality' => [
                    'confidence' => $this->faker->numberBetween(3, 4),
                    'self_awareness' => $this->faker->numberBetween(3, 4),
                    'emotional_stability' => $this->faker->numberBetween(3, 4),
                    'growth_mindset' => $this->faker->numberBetween(3, 4),
                    'comments' => 'Developing confidence, Generally aware, Mostly stable, Showing growth',
                ],
                'behavior' => [
                    'punctuality' => $this->faker->numberBetween(3, 4),
                    'respect_for_instructions' => $this->faker->numberBetween(3, 4),
                    'work_ownership' => $this->faker->numberBetween(3, 4),
                    'interpersonal_conduct' => $this->faker->numberBetween(3, 4),
                    'comments' => 'Usually punctual, Follows most instructions, Takes some ownership, Good conduct',
                ],
                'performance' => [
                    'alertness' => $this->faker->numberBetween(3, 4),
                    'first_aid_ability' => $this->faker->numberBetween(2, 3),
                    'security_consciousness' => $this->faker->numberBetween(3, 4),
                    'comments' => 'Alert most times, Needs more training, Aware of security',
                ],
            ],
            'overall_rating' => $this->faker->randomFloat(1, 3.0, 4.0),
        ]);
    }

    /**
     * Evaluation with needs improvement scores
     */
    public function needsImprovement(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'scores' => [
                'personality' => [
                    'confidence' => $this->faker->numberBetween(2, 3),
                    'self_awareness' => $this->faker->numberBetween(2, 3),
                    'emotional_stability' => $this->faker->numberBetween(2, 3),
                    'growth_mindset' => $this->faker->numberBetween(2, 3),
                    'comments' => 'Lacks confidence, Limited awareness, Emotionally unstable at times, Slow growth',
                ],
                'behavior' => [
                    'punctuality' => $this->faker->numberBetween(2, 3),
                    'respect_for_instructions' => $this->faker->numberBetween(2, 3),
                    'work_ownership' => $this->faker->numberBetween(2, 3),
                    'interpersonal_conduct' => $this->faker->numberBetween(2, 3),
                    'comments' => 'Often late, Struggles with instructions, Poor ownership, Needs improvement in conduct',
                ],
                'performance' => [
                    'alertness' => $this->faker->numberBetween(2, 3),
                    'first_aid_ability' => $this->faker->numberBetween(1, 2),
                    'security_consciousness' => $this->faker->numberBetween(2, 3),
                    'comments' => 'Not very alert, No first aid knowledge, Low security awareness',
                ],
            ],
            'overall_rating' => $this->faker->randomFloat(1, 2.0, 3.0),
            'areas_for_improvement' => 'Needs to focus on improving all areas significantly. Additional intensive training recommended before deployment.',
        ]);
    }
}
