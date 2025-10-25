<?php

namespace Tests\Feature\Livewire\Tickets;

use App\Livewire\Tickets\Show;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TicketSlaRulesSeeder']);
    }

    /** @test */
    public function it_can_render_show_component()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertStatus(200)
            ->assertSee($ticket->ticket_number)
            ->assertSee($ticket->subject);
    }

    /** @test */
    public function it_displays_ticket_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'subject' => 'Test Ticket',
            'description' => 'Test Description',
            'priority' => 'urgent',
            'status' => 'open',
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('Test Ticket')
            ->assertSee('Test Description')
            ->assertSee('Urgent')
            ->assertSee('Open');
    }

    /** @test */
    public function it_can_add_comment_to_ticket()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('newComment', 'This is a test comment')
            ->call('addComment')
            ->assertHasNoErrors()
            ->assertSet('newComment', '');

        // Note: TicketComment model not yet implemented, 
        // so this will append to description temporarily
    }

    /** @test */
    public function it_validates_comment_is_required()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('newComment', '')
            ->call('addComment')
            ->assertHasErrors(['newComment' => 'required']);
    }

    /** @test */
    public function it_can_update_ticket_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'status' => 'open',
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('status', 'in_progress')
            ->call('updateTicket')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'in_progress',
        ]);
    }

    /** @test */
    public function it_can_update_ticket_priority()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'priority' => 'medium',
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('priority', 'urgent')
            ->call('updateTicket')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'priority' => 'urgent',
        ]);
    }

    /** @test */
    public function it_can_assign_ticket_to_user()
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);
        
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin1->id,
            'assigned_to' => null,
        ]);

        $this->actingAs($admin1);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('assigned_to', $admin2->id)
            ->call('updateTicket')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'assigned_to' => $admin2->id,
        ]);
    }

    /** @test */
    public function it_sets_resolved_at_when_status_changes_to_resolved()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'status' => 'open',
            'resolved_at' => null,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('status', 'resolved')
            ->call('updateTicket');

        $ticket->refresh();
        $this->assertNotNull($ticket->resolved_at);
    }

    /** @test */
    public function it_sets_closed_at_when_status_changes_to_closed()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'status' => 'resolved',
            'closed_at' => null,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('status', 'closed')
            ->call('updateTicket');

        $ticket->refresh();
        $this->assertNotNull($ticket->closed_at);
    }

    /** @test */
    public function it_sets_first_response_at_for_admin_comment()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'first_response_at' => null,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->set('newComment', 'First response from admin')
            ->call('addComment');

        $ticket->refresh();
        $this->assertNotNull($ticket->first_response_at);
    }

    /** @test */
    public function it_displays_related_client()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create(['contact_person' => 'John Doe']);
        
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'client_id' => $client->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('John Doe');
    }

    /** @test */
    public function it_displays_tier_badge_for_platinum_client()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'tier_based_priority' => 'platinum',
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('Platinum Client');
    }

    /** @test */
    public function it_displays_auto_boost_indicator()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'auto_priority' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('⚡')
            ->assertSee('Auto-Boosted');
    }

    /** @test */
    public function it_displays_sla_timeline_for_open_tickets()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'status' => 'open',
            'sla_response_due' => now()->addMinutes(30),
            'sla_resolution_due' => now()->addHours(2),
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('SLA Timeline')
            ->assertSee('Response Due')
            ->assertSee('Resolution Due');
    }

    /** @test */
    public function it_displays_sla_breach_warning()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'status' => 'open',
            'sla_response_due' => now()->subMinutes(30),
            'first_response_at' => null,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('SLA Breached');
    }

    /** @test */
    public function it_displays_created_on_behalf_text()
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin Sarah']);
        $client = Client::factory()->create(['contact_person' => 'Client John']);
        
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'created_on_behalf_of' => $client->id,
            'created_on_behalf_type' => 'client',
            'client_id' => $client->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('Admin Sarah')
            ->assertSee('on behalf of')
            ->assertSee('Client John');
    }

    /** @test */
    public function it_displays_timeline_timestamps()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = Ticket::factory()->create([
            'requester_id' => $admin->id,
            'first_response_at' => now()->subHours(2),
            'resolved_at' => now()->subHours(1),
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket])
            ->assertSee('Timeline')
            ->assertSee('Created')
            ->assertSee('First Response')
            ->assertSee('Resolved');
    }
}
