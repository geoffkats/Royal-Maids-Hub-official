<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    // RefreshDatabase handles migrations lifecycle; no manual migrate needed.

    public function test_admin_can_view_any_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clientUser = User::factory()->create(['role' => 'client']);
        $client = Client::factory()->create(['user_id' => $clientUser->id]);
        $booking = Booking::factory()->create(['client_id' => $client->id]);

        $this->actingAs($admin)
            ->get(route('bookings.show', $booking))
            ->assertStatus(200)
            ->assertSee('Booking #'.$booking->id);
    }

    public function test_client_can_view_own_booking(): void
    {
        $clientUser = User::factory()->create(['role' => 'client']);
        $client = Client::factory()->create(['user_id' => $clientUser->id]);
        $booking = Booking::factory()->create(['client_id' => $client->id]);

        $this->actingAs($clientUser)
            ->get(route('bookings.show', $booking))
            ->assertStatus(200)
            ->assertSee('Booking #'.$booking->id);
    }

    public function test_client_cannot_view_others_booking(): void
    {
        $clientUser = User::factory()->create(['role' => 'client']);
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $otherClientUser = User::factory()->create(['role' => 'client']);
        $otherClient = Client::factory()->create(['user_id' => $otherClientUser->id]);
        $booking = Booking::factory()->create(['client_id' => $otherClient->id]);

        $this->actingAs($clientUser)
            ->get(route('bookings.show', $booking))
            ->assertStatus(403);
    }
}
