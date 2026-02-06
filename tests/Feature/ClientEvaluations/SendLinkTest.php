<?php

use App\Livewire\Bookings\Show as BookingShow;
use App\Mail\ClientEvaluationLinkMail;
use App\Models\Booking;
use App\Models\ClientEvaluationLink;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

it('sends a client evaluation link email for a booking', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();
    $booking = Booking::factory()->create([
        'email' => 'client@example.com',
    ]);

    $this->actingAs($admin);

    Livewire::test(BookingShow::class, ['booking' => $booking])
        ->call('sendClientEvaluationLink');

    Mail::assertSent(ClientEvaluationLinkMail::class, function ($mail) use ($booking) {
        return $mail->hasTo($booking->email);
    });

    expect(ClientEvaluationLink::where('booking_id', $booking->id)->exists())->toBeTrue();
});
