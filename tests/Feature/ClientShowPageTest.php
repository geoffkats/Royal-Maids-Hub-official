declare(strict_types=1);

use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;

test('client show page displays correctly with account age formatting', function () {
    $user = User::factory()->create(['role' => 'trainer']);
    $client = Client::factory()->create([
        'contact_person' => 'John Doe',
        'phone' => '+1234567890',
        'company_name' => 'Old Company Name',
        'identity_number' => 'NIN123456789',
        'identity_type' => 'nin',
        'created_at' => now()->subMonths(2),
    ]);

    $response = $this->actingAs($user)
        ->get("/trainer/clients/{$client->id}");

    $response->assertSuccessful()
        ->assertSee('John Doe')
        ->assertSee('Account Age')
        ->assertSee('month') // Should display "2 months" not decimal
        ->assertDontSee('1.43')
        ->assertDontSee('1.4374646967443');
});

test('client show page displays subscription information', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create([
        'subscription_status' => 'active',
        'subscription_tier' => 'premium',
    ]);

    $response = $this->get("/clients/{$client->id}");

    $response->assertSuccessful()
        ->assertSee('Subscription')
        ->assertSee('Current Plan')
        ->assertSee('Status');
});

test('client show page displays tickets with SLA status', function () {
    $user = User::factory()->create(['role' => 'trainer']);
    $client = Client::factory()->create();
    
    // Create a ticket with SLA info
    $ticket = Ticket::factory()->create([
        'client_id' => $client->id,
        'subject' => 'Test Ticket',
        'priority' => 'high',
        'status' => 'open',
        'sla_breached' => false,
        'sla_response_due' => now()->addHours(2),
        'sla_resolution_due' => now()->addDays(1),
    ]);

    $response = $this->actingAs($user)
        ->get("/trainer/clients/{$client->id}");

    $response->assertSuccessful()
        ->assertSee('Support Tickets')
        ->assertSee('Test Ticket')
        ->assertSee('SLA Status')
        ->assertSee('On Track');
});

test('client show page ticket creation button uses correct route', function () {
    $user = User::factory()->create(['role' => 'trainer']);
    $client = Client::factory()->create();

    $response = $this->actingAs($user)
        ->get("/trainer/clients/{$client->id}");

    $response->assertSuccessful()
        ->assertSee("trainer/tickets/create?client_id={$client->id}");
});

test('ticket getSLAStatus returns correct status', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
        'sla_breached' => false,
        'sla_response_due' => now()->addHours(2),
    ]);

    expect($ticket->getSLAStatus())->toBe('on-track');
});

test('ticket getSLAStatus returns at-risk when within 1 hour', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
        'sla_breached' => false,
        'sla_response_due' => now()->addMinutes(30),
    ]);

    expect($ticket->getSLAStatus())->toBe('at-risk');
});

test('ticket getSLAStatus returns breached when overdue', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
        'sla_breached' => false,
        'sla_response_due' => now()->subMinutes(30),
    ]);

    expect($ticket->getSLAStatus())->toBe('breached');
});
