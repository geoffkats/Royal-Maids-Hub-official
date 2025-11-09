<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Client;
use App\Models\CRM\Lead;
use App\Models\CRM\Source;
use App\Models\Package;
use App\Models\User;
use App\Services\CRM\BookingToLeadService;
use App\Services\CRM\ConvertLeadToClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadClientConversionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary data
        Source::factory()->create(['name' => 'Website']);
        Package::factory()->create(['name' => 'Silver']);
    }

    /** @test */
    public function new_customer_booking_creates_lead()
    {
        $bookingData = [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'city' => 'Kampala',
            'division' => 'Central',
        ];

        $service = app(BookingToLeadService::class);
        $lead = $service->findOrCreateLeadFromBooking($bookingData);

        $this->assertInstanceOf(Lead::class, $lead);
        $this->assertEquals('John Doe', $lead->full_name);
        $this->assertEquals('john@example.com', $lead->email);
        $this->assertEquals('new', $lead->status);
        $this->assertNull($lead->client_id);
    }

    /** @test */
    public function existing_lead_booking_links_to_existing_lead()
    {
        // Create existing lead
        $existingLead = Lead::factory()->create([
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);

        $bookingData = [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'city' => 'Kampala',
        ];

        $service = app(BookingToLeadService::class);
        $lead = $service->findOrCreateLeadFromBooking($bookingData);

        $this->assertEquals($existingLead->id, $lead->id);
        $this->assertDatabaseCount('crm_leads', 1);
    }

    /** @test */
    public function existing_client_booking_creates_lead_linked_to_client()
    {
        // Create existing client
        $user = User::factory()->create(['email' => 'john@example.com', 'role' => 'client']);
        $client = Client::factory()->create(['user_id' => $user->id]);

        $bookingData = [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'city' => 'Kampala',
        ];

        $service = app(BookingToLeadService::class);
        $lead = $service->findOrCreateLeadFromBooking($bookingData);

        $this->assertInstanceOf(Lead::class, $lead);
        $this->assertEquals($client->id, $lead->client_id);
        $this->assertEquals('qualified', $lead->status);
    }

    /** @test */
    public function booking_is_linked_to_lead()
    {
        $bookingData = [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'city' => 'Kampala',
        ];

        $service = app(BookingToLeadService::class);
        $lead = $service->findOrCreateLeadFromBooking($bookingData);

        $booking = Booking::factory()->create([
            'lead_id' => $lead->id,
            'client_id' => null,
        ]);

        $this->assertEquals($lead->id, $booking->lead_id);
        $this->assertNull($booking->client_id);
        $this->assertTrue($booking->isLinkedToLead());
    }

    /** @test */
    public function lead_conversion_creates_client()
    {
        $lead = Lead::factory()->create([
            'status' => 'qualified',
            'email' => 'john@example.com',
        ]);

        $service = new ConvertLeadToClientService();
        $client = $service->convert($lead);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('converted', $lead->fresh()->status);
        $this->assertEquals($client->id, $lead->fresh()->client_id);
        $this->assertNotNull($lead->fresh()->converted_at);
    }

    /** @test */
    public function lead_conversion_transfers_bookings_to_client()
    {
        $lead = Lead::factory()->create(['status' => 'qualified']);
        
        $booking1 = Booking::factory()->create(['lead_id' => $lead->id, 'client_id' => null]);
        $booking2 = Booking::factory()->create(['lead_id' => $lead->id, 'client_id' => null]);

        $service = new ConvertLeadToClientService();
        $client = $service->convert($lead);

        $this->assertEquals($client->id, $booking1->fresh()->client_id);
        $this->assertEquals($client->id, $booking2->fresh()->client_id);
        $this->assertEquals($lead->id, $booking1->fresh()->lead_id); // Lead ID preserved
        $this->assertEquals($lead->id, $booking2->fresh()->lead_id);
    }

    /** @test */
    public function lead_conversion_with_existing_client_links_to_existing()
    {
        $user = User::factory()->create(['email' => 'john@example.com', 'role' => 'client']);
        $existingClient = Client::factory()->create(['user_id' => $user->id]);

        $lead = Lead::factory()->create([
            'status' => 'qualified',
            'email' => 'john@example.com',
        ]);

        $service = new ConvertLeadToClientService();
        $client = $service->convert($lead);

        $this->assertEquals($existingClient->id, $client->id);
        $this->assertDatabaseCount('clients', 1); // No duplicate client created
    }

    /** @test */
    public function duplicate_detection_prevents_duplicate_leads()
    {
        $existingLead = Lead::factory()->create([
            'email' => 'john@example.com',
        ]);

        $bookingData = [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'city' => 'Kampala',
        ];

        $service = app(BookingToLeadService::class);
        $lead = $service->findOrCreateLeadFromBooking($bookingData);

        $this->assertEquals($existingLead->id, $lead->id);
        $this->assertDatabaseCount('crm_leads', 1);
    }

    /** @test */
    public function duplicate_detection_by_phone_number()
    {
        $existingLead = Lead::factory()->create([
            'phone' => '123-456-7890',
        ]);

        $bookingData = [
            'full_name' => 'John Doe',
            'email' => 'different@example.com',
            'phone' => '1234567890', // Same phone, different format
            'city' => 'Kampala',
        ];

        $service = app(BookingToLeadService::class);
        $lead = $service->findOrCreateLeadFromBooking($bookingData);

        $this->assertEquals($existingLead->id, $lead->id);
        $this->assertDatabaseCount('crm_leads', 1);
    }

    /** @test */
    public function lead_shows_booking_count()
    {
        $lead = Lead::factory()->create();
        
        Booking::factory()->count(3)->create(['lead_id' => $lead->id]);

        $this->assertTrue($lead->hasBookings());
        $this->assertEquals(3, $lead->getBookingCount());
    }

    /** @test */
    public function client_has_leads_relationship()
    {
        $user = User::factory()->create(['role' => 'client']);
        $client = Client::factory()->create(['user_id' => $user->id]);
        
        $lead1 = Lead::factory()->create(['client_id' => $client->id, 'status' => 'converted']);
        $lead2 = Lead::factory()->create(['client_id' => $client->id, 'status' => 'converted']);

        $this->assertCount(2, $client->leads);
        $this->assertTrue($client->leads->contains($lead1));
        $this->assertTrue($client->leads->contains($lead2));
    }
}
