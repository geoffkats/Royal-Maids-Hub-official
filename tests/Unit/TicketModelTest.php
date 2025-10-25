<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Client;
use App\Models\Maid;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TicketSlaRulesSeeder']);
    }

    public function test_it_generates_unique_ticket_number_on_creation()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Test ticket',
            'description' => 'Test description',
            'priority' => 'medium',
            'requester_id' => $user->id,
            'requester_type' => 'admin',
            'status' => 'open',
        ]);

        $this->assertNotNull($ticket->ticket_number);
        $this->assertStringStartsWith('TKT-' . date('Y') . '-', $ticket->ticket_number);
    }

    /** @test */
    public function it_boosts_priority_for_platinum_clients()
    {
        $package = Package::factory()->create(['tier' => 'platinum']);
        $client = Client::factory()->create();
        $booking = Booking::factory()->create([
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'confirmed',
        ]);
        $user = User::factory()->create(['role' => 'client']);

        // Test: Medium → High for Platinum
        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Test ticket',
            'description' => 'Test description',
            'priority' => 'medium',
            'requester_id' => $user->id,
            'requester_type' => 'client',
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'open',
        ]);

        $this->assertEquals('high', $ticket->priority);
        $this->assertTrue($ticket->auto_priority);
        $this->assertEquals('platinum', $ticket->tier_based_priority);
    }

    /** @test */
    public function it_boosts_platinum_urgent_to_critical()
    {
        $package = Package::factory()->create(['tier' => 'platinum']);
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'client']);

        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Urgent issue',
            'description' => 'Very urgent',
            'priority' => 'urgent',
            'requester_id' => $user->id,
            'requester_type' => 'client',
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'open',
        ]);

        $this->assertEquals('critical', $ticket->priority);
        $this->assertTrue($ticket->auto_priority);
    }

    /** @test */
    public function it_maintains_gold_client_priority()
    {
        $package = Package::factory()->create(['tier' => 'gold']);
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'client']);

        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Test ticket',
            'description' => 'Test description',
            'priority' => 'high',
            'requester_id' => $user->id,
            'requester_type' => 'client',
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'open',
        ]);

        $this->assertEquals('high', $ticket->priority);
        $this->assertFalse($ticket->auto_priority);
        $this->assertEquals('gold', $ticket->tier_based_priority);
    }

    /** @test */
    public function it_caps_silver_urgent_at_high()
    {
        $package = Package::factory()->create(['tier' => 'silver']);
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'client']);

        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Urgent issue',
            'description' => 'Urgent',
            'priority' => 'urgent',
            'requester_id' => $user->id,
            'requester_type' => 'client',
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'open',
        ]);

        $this->assertEquals('high', $ticket->priority);
        $this->assertTrue($ticket->auto_priority);
        $this->assertEquals('silver', $ticket->tier_based_priority);
    }

    /** @test */
    public function it_detects_critical_safety_issues()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $ticket = Ticket::create([
            'type' => 'maid_support',
            'category' => 'Safety Concern',
            'subject' => 'Emergency: Maid injured at work',
            'description' => 'The maid had a serious injury and needs immediate help',
            'priority' => 'low', // Will be overridden
            'requester_id' => $user->id,
            'requester_type' => 'admin',
            'status' => 'open',
        ]);

        $this->assertEquals('critical', $ticket->priority);
        $this->assertTrue($ticket->auto_priority);
    }

    /** @test */
    public function it_detects_critical_keywords_in_description()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $criticalKeywords = ['danger', 'emergency', 'abuse', 'harassment', 'assault', 'medical'];

        foreach ($criticalKeywords as $keyword) {
            $ticket = Ticket::create([
                'type' => 'client_issue',
                'category' => 'General',
                'subject' => 'Issue',
                'description' => "There is a serious $keyword situation",
                'priority' => 'medium',
                'requester_id' => $user->id,
                'requester_type' => 'admin',
                'status' => 'open',
            ]);

            $this->assertEquals('critical', $ticket->priority, "Failed to detect critical keyword: $keyword");
            $ticket->delete();
        }
    }

    /** @test */
    public function it_calculates_sla_deadlines_for_platinum_critical()
    {
        $package = Package::factory()->create(['tier' => 'platinum']);
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'client']);

        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Urgent issue',
            'description' => 'Emergency situation',
            'priority' => 'urgent', // Will become critical
            'requester_id' => $user->id,
            'requester_type' => 'client',
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'open',
        ]);

        $this->assertNotNull($ticket->sla_response_due);
        $this->assertNotNull($ticket->sla_resolution_due);
        
        // Platinum critical: 5 min response, 30 min resolution
        $expectedResponse = now()->addMinutes(5);
        $expectedResolution = now()->addMinutes(30);
        
        $this->assertTrue($ticket->sla_response_due->diffInMinutes($expectedResponse) < 1);
        $this->assertTrue($ticket->sla_resolution_due->diffInMinutes($expectedResolution) < 1);
    }

    /** @test */
    public function it_tracks_on_behalf_creation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $ticket = Ticket::create([
            'type' => 'client_issue',
            'category' => 'Service Quality',
            'subject' => 'Client called with issue',
            'description' => 'Phone call from client',
            'priority' => 'medium',
            'requester_id' => $admin->id,
            'requester_type' => 'admin',
            'created_on_behalf_of' => $client->id,
            'created_on_behalf_type' => 'client',
            'client_id' => $client->id,
            'status' => 'open',
        ]);

        $this->assertTrue($ticket->wasCreatedOnBehalf());
        $this->assertEquals($client->id, $ticket->created_on_behalf_of);
        $this->assertEquals('client', $ticket->created_on_behalf_type);
    }

    /** @test */
    public function it_checks_if_ticket_is_open()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $openTicket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'status' => 'open',
        ]);
        $inProgressTicket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'status' => 'in_progress',
        ]);
        $resolvedTicket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'status' => 'resolved',
        ]);

        $this->assertTrue($openTicket->isOpen());
        $this->assertTrue($inProgressTicket->isOpen());
        $this->assertFalse($resolvedTicket->isOpen());
    }

    /** @test */
    public function it_detects_sla_breach_for_unresponded_ticket()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $ticket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'status' => 'open',
            'sla_response_due' => now()->subMinutes(10), // Overdue
            'first_response_at' => null,
        ]);

        $this->assertTrue($ticket->isSLABreached());
    }

    /** @test */
    public function it_does_not_breach_sla_if_responded()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $ticket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'status' => 'open',
            'sla_response_due' => now()->subMinutes(10),
            'first_response_at' => now()->subMinutes(15), // Responded before due
            'sla_resolution_due' => now()->addMinutes(30), // Not overdue yet
        ]);

        $this->assertFalse($ticket->isSLABreached());
    }

    /** @test */
    public function it_detects_sla_breach_for_unresolved_ticket()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $ticket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'status' => 'open',
            'first_response_at' => now()->subHours(2),
            'sla_resolution_due' => now()->subMinutes(30), // Overdue
            'resolved_at' => null,
        ]);

        $this->assertTrue($ticket->isSLABreached());
    }

    /** @test */
    public function it_returns_correct_status_colors()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $statuses = [
            'open' => 'blue',
            'pending' => 'yellow',
            'in_progress' => 'purple',
            'resolved' => 'green',
            'closed' => 'zinc',
        ];

        foreach ($statuses as $status => $expectedColor) {
            $ticket = Ticket::factory()->create([
                'requester_id' => $user->id,
                'status' => $status,
            ]);

            $this->assertEquals($expectedColor, $ticket->getStatusColor());
        }
    }

    /** @test */
    public function it_returns_correct_priority_colors()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $priorities = [
            'critical' => 'red',
            'urgent' => 'orange',
            'high' => 'amber',
            'medium' => 'yellow',
            'low' => 'zinc',
        ];

        foreach ($priorities as $priority => $expectedColor) {
            $ticket = Ticket::factory()->create([
                'requester_id' => $user->id,
                'priority' => $priority,
            ]);

            $this->assertEquals($expectedColor, $ticket->getPriorityColor());
        }
    }

    /** @test */
    public function it_returns_correct_tier_badge_colors()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $tiers = [
            'platinum' => 'purple',
            'gold' => 'yellow',
            'silver' => 'zinc',
        ];

        foreach ($tiers as $tier => $expectedColor) {
            $ticket = Ticket::factory()->create([
                'requester_id' => $user->id,
                'tier_based_priority' => $tier,
            ]);

            $this->assertEquals($expectedColor, $ticket->getTierBadgeColor());
        }
    }

    /** @test */
    public function it_scopes_tickets_by_open_status()
    {
        $user = User::factory()->create(['role' => 'admin']);

        Ticket::factory()->create(['requester_id' => $user->id, 'status' => 'open']);
        Ticket::factory()->create(['requester_id' => $user->id, 'status' => 'in_progress']);
        Ticket::factory()->create(['requester_id' => $user->id, 'status' => 'pending']);
        Ticket::factory()->create(['requester_id' => $user->id, 'status' => 'resolved']);
        Ticket::factory()->create(['requester_id' => $user->id, 'status' => 'closed']);

        $openTickets = Ticket::open()->get();

        $this->assertEquals(3, $openTickets->count());
    }

    /** @test */
    public function it_scopes_tickets_by_priority()
    {
        $user = User::factory()->create(['role' => 'admin']);

        Ticket::factory()->create(['requester_id' => $user->id, 'priority' => 'urgent']);
        Ticket::factory()->create(['requester_id' => $user->id, 'priority' => 'critical']);
        Ticket::factory()->create(['requester_id' => $user->id, 'priority' => 'medium']);

        $urgentTickets = Ticket::urgent()->get();
        $criticalTickets = Ticket::critical()->get();

        $this->assertEquals(1, $urgentTickets->count());
        $this->assertEquals(1, $criticalTickets->count());
    }

    /** @test */
    public function it_scopes_tickets_by_tier()
    {
        $user = User::factory()->create(['role' => 'admin']);

        Ticket::factory()->create(['requester_id' => $user->id, 'tier_based_priority' => 'platinum']);
        Ticket::factory()->create(['requester_id' => $user->id, 'tier_based_priority' => 'gold']);
        Ticket::factory()->create(['requester_id' => $user->id, 'tier_based_priority' => null]);

        $platinumTickets = Ticket::platinumTier()->get();

        $this->assertEquals(1, $platinumTickets->count());
    }

    /** @test */
    public function it_scopes_tickets_by_assignment()
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        Ticket::factory()->create(['requester_id' => $admin1->id, 'assigned_to' => $admin1->id]);
        Ticket::factory()->create(['requester_id' => $admin1->id, 'assigned_to' => $admin2->id]);
        Ticket::factory()->create(['requester_id' => $admin1->id, 'assigned_to' => null]);

        $assignedToAdmin1 = Ticket::assignedTo($admin1->id)->get();
        $unassigned = Ticket::unassigned()->get();

        $this->assertEquals(1, $assignedToAdmin1->count());
        $this->assertEquals(1, $unassigned->count());
    }

    /** @test */
    public function it_generates_created_by_text_for_self_created_ticket()
    {
        $user = User::factory()->create(['role' => 'client', 'name' => 'John Doe']);

        $ticket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'created_on_behalf_of' => null,
        ]);

        $this->assertEquals('Created by John Doe', $ticket->getCreatedByText());
    }

    /** @test */
    public function it_generates_created_by_text_for_on_behalf_ticket()
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin Sarah']);
        $client = Client::factory()->create(['contact_person' => 'Client John']);

        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'created_on_behalf_of' => $client->id,
            'created_on_behalf_type' => 'client',
            'client_id' => $client->id,
        ]);

        $this->assertStringContainsString('Admin Sarah', $ticket->getCreatedByText());
        $this->assertStringContainsString('Client John', $ticket->getCreatedByText());
    }

    /** @test */
    public function it_identifies_platinum_clients()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $platinumTicket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'tier_based_priority' => 'platinum',
        ]);
        $silverTicket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'tier_based_priority' => 'silver',
        ]);

        $this->assertTrue($platinumTicket->isPlatinumClient());
        $this->assertFalse($silverTicket->isPlatinumClient());
    }
}
