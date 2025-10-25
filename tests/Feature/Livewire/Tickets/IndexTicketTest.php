<?php

namespace Tests\Feature\Livewire\Tickets;

use App\Livewire\Tickets\Index;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TicketSlaRulesSeeder']);
    }

    /** @test */
    public function it_can_render_index_component()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertStatus(200)
            ->assertSee('Support Tickets');
    }

    /** @test */
    public function it_displays_tickets_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Test Ticket Subject',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->assertSee('Test Ticket Subject');
    }

    /** @test */
    public function it_filters_by_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $openTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Open Ticket',
            'status' => 'open',
        ]);
        
        $resolvedTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Resolved Ticket',
            'status' => 'resolved',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('statusFilter', 'open')
            ->assertSee('Open Ticket')
            ->assertDontSee('Resolved Ticket');
    }

    /** @test */
    public function it_filters_by_priority()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $urgentTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Urgent Ticket',
            'priority' => 'urgent',
        ]);
        
        $lowTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Low Priority Ticket',
            'priority' => 'low',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('priorityFilter', 'urgent')
            ->assertSee('Urgent Ticket')
            ->assertDontSee('Low Priority Ticket');
    }

    /** @test */
    public function it_filters_by_type()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $clientIssue = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Client Issue',
            'type' => 'client_issue',
        ]);
        
        $billing = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Billing Issue',
            'type' => 'billing',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('typeFilter', 'billing')
            ->assertSee('Billing Issue')
            ->assertDontSee('Client Issue');
    }

    /** @test */
    public function it_filters_by_tier()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $platinumTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Platinum Client',
            'tier_based_priority' => 'platinum',
        ]);
        
        $silverTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Silver Client',
            'tier_based_priority' => 'silver',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('tierFilter', 'platinum')
            ->assertSee('Platinum Client')
            ->assertDontSee('Silver Client');
    }

    /** @test */
    public function it_searches_by_ticket_number()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Searchable Ticket',
        ]);

        $ticketNumber = $ticket->ticket_number;

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('search', substr($ticketNumber, 0, 10))
            ->assertSee('Searchable Ticket');
    }

    /** @test */
    public function it_searches_by_subject()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Unique Search Term',
        ]);
        
        Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Different Subject',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('search', 'Unique Search')
            ->assertSee('Unique Search Term')
            ->assertDontSee('Different Subject');
    }

    /** @test */
    public function it_filters_assigned_to_me()
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);
        
        $myTicket = Ticket::factory()->create([
            'requester_id' => $admin1->id,
            'assigned_to' => $admin1->id,
            'subject' => 'My Ticket',
        ]);
        
        $otherTicket = Ticket::factory()->create([
            'requester_id' => $admin1->id,
            'assigned_to' => $admin2->id,
            'subject' => 'Other Ticket',
        ]);

        $this->actingAs($admin1);

        Livewire::test(Index::class)
            ->set('assignedFilter', 'me')
            ->assertSee('My Ticket')
            ->assertDontSee('Other Ticket');
    }

    /** @test */
    public function it_filters_unassigned_tickets()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $unassigned = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'assigned_to' => null,
            'subject' => 'Unassigned Ticket',
        ]);
        
        $assigned = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'assigned_to' => $admin->id,
            'subject' => 'Assigned Ticket',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('assignedFilter', 'unassigned')
            ->assertSee('Unassigned Ticket')
            ->assertDontSee('Assigned Ticket');
    }

    /** @test */
    public function it_paginates_tickets()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Ticket::factory()->count(25)->create([
            'requester_id' => $admin->id,
        ]);

        $this->actingAs($admin);

        $component = Livewire::test(Index::class);
        
        $this->assertEquals(20, $component->get('tickets')->count());
    }

    /** @test */
    public function it_displays_priority_badges()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Critical Ticket',
            'priority' => 'critical',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->assertSee('Critical')
            ->assertSee('Critical Ticket');
    }

    /** @test */
    public function it_displays_sla_breach_warnings()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $breachedTicket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Breached SLA',
            'status' => 'open',
            'sla_response_due' => now()->subMinutes(30),
            'first_response_at' => null,
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->assertSee('Breached SLA');
    }

    /** @test */
    public function it_displays_tier_badges()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Platinum Ticket',
            'tier_based_priority' => 'platinum',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->assertSee('Platinum');
    }

    /** @test */
    public function it_displays_auto_boost_indicator()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Auto Boosted',
            'auto_priority' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->assertSee('Auto Boosted')
            ->assertSee('⚡');
    }
}
