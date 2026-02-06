<?php

use App\Livewire\ClientEvaluationResponses\Index as FeedbackIndex;
use App\Mail\ClientEvaluationLinkMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientEvaluationLink;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

it('admin can send evaluation link from feedback page', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();
    $clientUser = User::factory()->client()->create(['email' => 'client@example.com']);
    $client = Client::factory()->create(['user_id' => $clientUser->id]);
    $booking = Booking::factory()->for($client)->create([
        'email' => 'client@example.com',
    ]);

    $this->actingAs($admin);

    Livewire::test(FeedbackIndex::class)
        ->set('activeTab', 'send-links')
        ->set('selectedClientId', $client->id)
        ->call('sendClientEvaluationLink', $booking->id);

    Mail::assertSent(ClientEvaluationLinkMail::class, function ($mail) use ($booking) {
        return $mail->hasTo($booking->email);
    });

    expect(ClientEvaluationLink::where('booking_id', $booking->id)->exists())->toBeTrue();
});
