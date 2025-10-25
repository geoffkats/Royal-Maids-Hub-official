<?php

namespace Tests\Feature\Livewire\Tickets;

use App\Livewire\Tickets\Create;
use App\Models\User;
use App\Models\Client;
use App\Models\Maid;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TicketSlaRulesSeeder']);
    }

    /** @test */
    public function it_can_render_create_ticket_component()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->assertStatus(200)
            ->assertSee('Create Support Ticket');
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->call('submit')
            ->assertHasErrors([
                'type' => 'required',
                'category' => 'required',
                'subject' => 'required',
                'description' => 'required',
            ]);
    }

    /** @test */
    public function it_creates_ticket_with_valid_data()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('type', 'client_issue')
            ->set('category', 'Service Quality')
            ->set('priority', 'medium')
            ->set('subject', 'Test ticket subject')
            ->set('description', 'Test ticket description')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test ticket subject',
            'type' => 'client_issue',
            'category' => 'Service Quality',
        ]);
    }

    /** @test */
    public function it_shows_tier_preview_for_platinum_client()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $package = Package::factory()->create(['tier' => 'platinum']);
        $client = Client::factory()->create();
        $booking = Booking::factory()->create([
            'client_id' => $client->id,
            'package_id' => $package->id,
            'status' => 'confirmed',
        ]);

        $this->actingAs($admin);

        Livewire::test(Create::class)
            ->set('onBehalfType', 'client')
            ->set('onBehalfOfClientId', $client->id)
            ->set('priority', 'medium')
            ->assertSee('high') // Should show boosted priority
            ->assertSee('Priority Auto-Boost');
    }

    /** @test */
    public function admin_can_create_ticket_on_behalf_of_client()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $this->actingAs($admin);

        Livewire::test(Create::class)
            ->set('type', 'client_issue')
            ->set('category', 'Service Quality')
            ->set('priority', 'medium')
            ->set('subject', 'Client called with issue')
            ->set('description', 'Phone call from client')
            ->set('onBehalfType', 'client')
            ->set('onBehalfOfClientId', $client->id)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Client called with issue',
            'requester_id' => $admin->id,
            'created_on_behalf_of' => $client->id,
            'created_on_behalf_type' => 'client',
        ]);
    }

    /** @test */
    public function admin_can_create_ticket_on_behalf_of_maid()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $maid = Maid::factory()->create();

        $this->actingAs($admin);

        Livewire::test(Create::class)
            ->set('type', 'maid_support')
            ->set('category', 'Payment Issue')
            ->set('priority', 'high')
            ->set('subject', 'Maid salary delayed')
            ->set('description', 'Maid called about late payment')
            ->set('onBehalfType', 'maid')
            ->set('onBehalfOfMaidId', $maid->id)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Maid salary delayed',
            'created_on_behalf_of' => $maid->id,
            'created_on_behalf_type' => 'maid',
        ]);
    }

    /** @test */
    public function trainer_can_see_on_behalf_section()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $this->actingAs($trainer);

        Livewire::test(Create::class)
            ->assertSee('Creating ticket on behalf of');
    }

    /** @test */
    public function client_cannot_see_on_behalf_section()
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($client);

        Livewire::test(Create::class)
            ->assertDontSee('Creating ticket on behalf of');
    }

    /** @test */
    public function it_links_related_entities_correctly()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $maid = Maid::factory()->create();
        $booking = Booking::factory()->create([
            'client_id' => $client->id,
        ]);

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('type', 'client_issue')
            ->set('category', 'Service Quality')
            ->set('priority', 'medium')
            ->set('subject', 'Test with related entities')
            ->set('description', 'Test description')
            ->set('relatedClientId', $client->id)
            ->set('relatedMaidId', $maid->id)
            ->set('relatedBookingId', $booking->id)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'client_id' => $client->id,
            'maid_id' => $maid->id,
            'booking_id' => $booking->id,
        ]);
    }

    /** @test */
    public function it_updates_categories_when_type_changes()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        $component = Livewire::test(Create::class)
            ->set('type', 'client_issue');

        $this->assertNotEmpty($component->get('categories'));
        $this->assertContains('Service Quality', $component->get('categories'));
    }
}
