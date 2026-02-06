<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketAutoAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketAutoAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected TicketAutoAssignmentService $assignmentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assignmentService = app(TicketAutoAssignmentService::class);
        // Ensure crm_settings table exists and is seeded for tests
        $this->initializeCrmSettings();
    }

    protected function initializeCrmSettings(): void
    {
        // Initially disable to allow test data creation without triggering auto-assignment
        $this->disableAutoAssignment();
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_auto_assigns_ticket_to_least_busy_user_when_workload_strategy_enabled(): void
    {
        // Create two staff members
        $busyUser = User::factory()->admin()->create();
        $lessUsedUser = User::factory()->trainer()->create();

        // Create 5 tickets assigned to busy user
        Ticket::factory(5)->create(['assigned_to' => $busyUser->id, 'status' => 'open']);
        
        // Create unassigned ticket
        $unassignedTicket = Ticket::factory()->create(['status' => 'open']);
        
        // Enable auto-assignment and test
        $this->enableAutoAssignmentWithStrategy('workload');
        $ticket = $unassignedTicket;

        // Auto-assign
        $assignedUser = $this->assignmentService->assign($ticket);

        // Should be assigned to a user with the lowest open ticket count
        $this->assertNotNull($assignedUser);

        $eligibleUsers = User::whereIn('role', ['admin', 'trainer'])->get();
        $openCounts = $eligibleUsers->mapWithKeys(function (User $user) {
            return [
                $user->id => Ticket::where('assigned_to', $user->id)
                    ->whereIn('status', ['open', 'in_progress', 'pending'])
                    ->count(),
            ];
        });

        $minOpen = $openCounts->min();
        $this->assertEquals($minOpen, $openCounts[$assignedUser->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_skips_auto_assignment_when_disabled(): void
    {
        // Disable auto-assignment
        $this->disableAutoAssignment();

        $user = User::factory()->admin()->create();
        $ticket = Ticket::factory()->create(['assigned_to' => null]);

        $assignedUser = $this->assignmentService->assign($ticket);

        $this->assertNull($assignedUser);
        $this->assertNull($ticket->fresh()->assigned_to);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_skips_assignment_if_ticket_already_assigned(): void
    {
        $this->enableAutoAssignmentWithStrategy('workload');

        $originalUser = User::factory()->admin()->create();
        $otherUser = User::factory()->trainer()->create();

        $ticket = Ticket::factory()->create(['assigned_to' => $originalUser->id]);

        $assignedUser = $this->assignmentService->assign($ticket);

        $this->assertNull($assignedUser);
        $this->assertEquals($originalUser->id, $ticket->fresh()->assigned_to);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_bulk_assign_multiple_tickets(): void
    {
        $user = User::factory()->admin()->create();

        // Create unassigned tickets (auto-assignment disabled in setup)
        $tickets = Ticket::factory(3)->create(['status' => 'open']);
        
        // Enable for bulk assignment test
        $this->enableAutoAssignmentWithStrategy('workload');
        $ticketIds = $tickets->pluck('id')->toArray();

        $results = $this->assignmentService->assignBulk($ticketIds);

        $this->assertCount(3, $results);
        $this->assertTrue($results[$ticketIds[0]]['success']);

        $eligibleUserIds = User::whereIn('role', ['admin', 'trainer'])->pluck('id')->toArray();
        $this->assertContains($results[$ticketIds[0]]['assigned_to_id'], $eligibleUserIds);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_prioritizes_platinum_tier_tickets_to_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $trainer = User::factory()->trainer()->create();

        // Create unassigned platinum ticket
        $platinumTicket = Ticket::factory()->create([
            'tier_based_priority' => 'platinum',
            'status' => 'open',
        ]);
        
        // Enable for test
        $this->enableAutoAssignmentWithStrategy('workload');

        $assignedUser = $this->assignmentService->assign($platinumTicket);

        $this->assertNotNull($assignedUser);
        $this->assertEquals('admin', $assignedUser->role);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_assignment_statistics(): void
    {
        $user1 = User::factory()->admin()->create();
        $user2 = User::factory()->trainer()->create();

        // Create test tickets (auto-assignment disabled in setup)
        Ticket::factory(5)->create(['assigned_to' => $user1->id, 'status' => 'open']);
        Ticket::factory(3)->create(['assigned_to' => $user2->id, 'status' => 'open']);
        Ticket::factory(2)->create(['status' => 'open']);
        
        // Enable for testing
        $this->enableAutoAssignmentWithStrategy('workload');

        $stats = $this->assignmentService->getAssignmentStats();

        $this->assertEquals(Ticket::count(), $stats['total_tickets']);
        $this->assertEquals(Ticket::whereNull('assigned_to')->count(), $stats['unassigned_tickets']);
        $this->assertTrue($stats['auto_assignment_enabled']);
        $this->assertEquals('workload', $stats['assignment_strategy']);
    }

    // Helper methods
    protected function enableAutoAssignmentWithStrategy(string $strategy): void
    {
        \Illuminate\Support\Facades\DB::table('crm_settings')->updateOrInsert(
            ['key' => 'ticket_auto_assign_enabled'],
            ['value' => '1']
        );

        \Illuminate\Support\Facades\DB::table('crm_settings')->updateOrInsert(
            ['key' => 'ticket_assignment_strategy'],
            ['value' => $strategy]
        );
    }

    protected function disableAutoAssignment(): void
    {
        \Illuminate\Support\Facades\DB::table('crm_settings')->updateOrInsert(
            ['key' => 'ticket_auto_assign_enabled'],
            ['value' => '0']
        );
    }
}
