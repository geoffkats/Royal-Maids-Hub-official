<?php

use App\Livewire\Clients\Show as ClientShow;
use App\Mail\ClientEvaluationLinkMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientEvaluationLink;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

it('admin can send evaluation link from client page', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();
    $clientUser = User::factory()->client()->create(['email' => 'client@example.com']);
    $client = Client::factory()->create(['user_id' => $clientUser->id]);
    $booking = Booking::factory()->for($client)->create([
        'email' => 'client@example.com',
    ]);

    $this->actingAs($admin);

    Livewire::test(ClientShow::class, ['client' => $client])
        ->set('selectedBookingId', $booking->id)
        ->call('sendClientEvaluationLink');

    Mail::assertSent(ClientEvaluationLinkMail::class, function ($mail) use ($booking) {
        return $mail->hasTo($booking->email);
    });

    expect(ClientEvaluationLink::where('booking_id', $booking->id)->exists())->toBeTrue();
});
