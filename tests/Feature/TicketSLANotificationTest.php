<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketSLABreachedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TicketSLANotificationTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sends_sla_breached_notification_when_response_sla_breached(): void
    {
        Notification::fake();

        $user = User::factory()->admin()->create();
        $ticket = Ticket::factory()->create([
            'assigned_to' => $user->id,
            'status' => 'open',
        ]);

        // Override SLA deadlines to past times (boot() calculates future times)
        $ticket->update([
            'sla_response_due' => now()->subHours(2),
            'sla_resolution_due' => now()->addHours(10),
            'first_response_at' => null,
            'sla_breached' => false,
        ]);

        $ticket->checkSLABreach();

        Notification::assertSentTo(
            [$user],
            TicketSLABreachedNotification::class
        );

        $this->assertTrue($ticket->fresh()->sla_breached);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sends_sla_breached_notification_when_resolution_sla_breached(): void
    {
        Notification::fake();

        $user = User::factory()->trainer()->create();
        $ticket = Ticket::factory()->create([
            'assigned_to' => $user->id,
            'status' => 'open',
        ]);

        // Override SLA deadlines to past times
        $ticket->update([
            'sla_response_due' => now()->subHours(20),
            'sla_resolution_due' => now()->subHours(2),
            'first_response_at' => now()->subHours(15),
            'sla_breached' => false,
        ]);

        $ticket->checkSLABreach();

        Notification::assertSentTo(
            [$user],
            TicketSLABreachedNotification::class
        );

        $this->assertTrue($ticket->fresh()->sla_breached);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_notifies_admins_for_critical_ticket_sla_breach(): void
    {
        Notification::fake();

        $staffUser = User::factory()->trainer()->create();
        $admin = User::factory()->admin()->create();

        $ticket = Ticket::factory()->create([
            'assigned_to' => $staffUser->id,
            'status' => 'open',
            'priority' => 'critical',
        ]);

        // Override SLA deadlines to past times
        $ticket->update([
            'sla_response_due' => now()->subHours(2),
            'first_response_at' => null,
            'sla_breached' => false,
        ]);

        $ticket->checkSLABreach();

        // Both assigned staff and admin should be notified
        Notification::assertSentTo(
            [$staffUser],
            TicketSLABreachedNotification::class
        );

        Notification::assertSentTo(
            [$admin],
            TicketSLABreachedNotification::class
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_does_not_send_notification_if_resolved(): void
    {
        Notification::fake();

        $user = User::factory()->trainer()->create();
        $ticket = Ticket::factory()->create([
            'assigned_to' => $user->id,
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        // Override SLA deadlines to past times
        $ticket->update([
            'sla_response_due' => now()->subHours(2),
            'sla_breached' => false,
        ]);

        $ticket->checkSLABreach();

        Notification::assertNotSentTo(
            [$user],
            TicketSLABreachedNotification::class
        );

        $this->assertFalse($ticket->fresh()->sla_breached);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_does_not_send_duplicate_notifications(): void
    {
        Notification::fake();

        $user = User::factory()->trainer()->create();
        $ticket = Ticket::factory()->create([
            'assigned_to' => $user->id,
            'status' => 'open',
            'sla_breached' => true, // Already breached
        ]);

        // Override SLA deadlines to past times
        $ticket->update([
            'sla_response_due' => now()->subHours(2),
            'first_response_at' => null,
        ]);

        $ticket->checkSLABreach();

        // Should not send notification again
        Notification::assertNotSentTo(
            [$user],
            TicketSLABreachedNotification::class
        );
    }
}
