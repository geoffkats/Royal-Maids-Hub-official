<?php

test('public booking routes render the public booking form', function (): void {
    $this->get('/booking')
        ->assertSuccessful()
        ->assertSeeLivewire(\App\Livewire\PublicBooking::class);

    $this->get('/bookings/create')
        ->assertSuccessful()
        ->assertSeeLivewire(\App\Livewire\PublicBooking::class);
});
