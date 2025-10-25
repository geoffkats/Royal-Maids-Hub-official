<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Client;
use App\Models\Maid;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['client_issue', 'maid_support', 'deployment_issue', 'billing', 'training', 'operations', 'general'];
        $categories = [
            'client_issue' => ['Service Quality', 'Maid Absence', 'Schedule Change', 'Replacement Request'],
            'maid_support' => ['Payment Issue', 'Safety Concern', 'Leave Request', 'Equipment Need'],
            'billing' => ['Incorrect Charge', 'Invoice Request', 'Payment Dispute'],
            'training' => ['Equipment Issue', 'Materials Shortage', 'Facility Issue'],
            'operations' => ['Administrative', 'Technical Support', 'General'],
        ];
        
        $type = $this->faker->randomElement($types);
        $category = $this->faker->randomElement($categories[$type] ?? ['General']);
        
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'pending', 'in_progress', 'on_hold', 'resolved', 'closed'];
        
        return [
            'type' => $type,
            'category' => $category,
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(3),
            'priority' => $this->faker->randomElement($priorities),
            'status' => $this->faker->randomElement($statuses),
            'requester_type' => 'admin',
            'auto_priority' => false,
            'sla_breached' => false,
        ];
    }

    /**
     * Indicate that the ticket is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    /**
     * Indicate that the ticket is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now()->subDays(rand(1, 7)),
        ]);
    }

    /**
     * Indicate that the ticket is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'resolved_at' => now()->subDays(rand(2, 14)),
            'closed_at' => now()->subDays(rand(1, 7)),
        ]);
    }

    /**
     * Indicate that the ticket has critical priority.
     */
    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'critical',
        ]);
    }

    /**
     * Indicate that the ticket has urgent priority.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    /**
     * Indicate that the ticket is for a platinum client.
     */
    public function platinum(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_based_priority' => 'platinum',
        ]);
    }

    /**
     * Indicate that the ticket is for a gold client.
     */
    public function gold(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_based_priority' => 'gold',
        ]);
    }

    /**
     * Indicate that the ticket is for a silver client.
     */
    public function silver(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_based_priority' => 'silver',
        ]);
    }

    /**
     * Indicate that the ticket was auto-boosted in priority.
     */
    public function autoBoosted(): static
    {
        return $this->state(fn (array $attributes) => [
            'auto_priority' => true,
        ]);
    }

    /**
     * Indicate that the ticket has breached SLA.
     */
    public function slaBreach(): static
    {
        return $this->state(fn (array $attributes) => [
            'sla_breached' => true,
            'sla_response_due' => now()->subMinutes(30),
            'sla_resolution_due' => now()->subHours(2),
        ]);
    }

    /**
     * Indicate that the ticket was created on behalf of someone.
     */
    public function onBehalf(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_on_behalf_type' => 'client',
            'created_on_behalf_of' => Client::factory(),
        ]);
    }
}
