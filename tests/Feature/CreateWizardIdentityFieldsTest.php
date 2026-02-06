<?php

declare(strict_types=1);

use App\Livewire\Bookings\CreateWizard;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Maid;
use App\Models\User;
use Livewire\Livewire;

test('create wizard step 1 validates identity fields', function () {
    actingAs(User::factory()->create());

    Livewire::test(CreateWizard::class)
        ->set('current_step', 1)
        ->set('identity_type', 'nin')
        ->set('identity_number', '')
        ->call('validateCurrentStep')
        ->assertHasErrors('identity_number');
});

test('create wizard requires identity type with identity number', function () {
    actingAs(User::factory()->create());

    Livewire::test(CreateWizard::class)
        ->set('current_step', 1)
        ->set('identity_type', '')
        ->set('identity_number', 'CM1234567890')
        ->call('validateCurrentStep')
        ->assertHasErrors('identity_type');
});

test('create wizard accepts valid identity fields', function () {
    actingAs(User::factory()->create());

    Livewire::test(CreateWizard::class)
        ->set('current_step', 1)
        ->set('identity_type', 'passport')
        ->set('identity_number', 'PA123456789')
        ->call('validateCurrentStep')
        ->assertHasNoErrors();
});

test('create wizard allows empty identity fields', function () {
    actingAs(User::factory()->create());

    Livewire::test(CreateWizard::class)
        ->set('current_step', 1)
        ->set('identity_type', '')
        ->set('identity_number', '')
        ->call('validateCurrentStep')
        ->assertHasNoErrors();
});

test('create wizard populates identity from selected client', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create([
        'identity_type' => 'nin',
        'identity_number' => 'CM9876543210',
    ]);

    actingAs($user);

    Livewire::test(CreateWizard::class)
        ->set('client_id', $client->id)
        ->assertSet('identity_type', 'nin')
        ->assertSet('identity_number', 'CM9876543210');
});

test('create wizard saves identity fields to booking', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $maid = Maid::factory()->create();

    actingAs($user);

    Livewire::test(CreateWizard::class)
        ->set('client_id', $client->id)
        ->set('maid_id', $maid->id)
        ->set('booking_date', now()->format('Y-m-d'))
        ->set('identity_type', 'passport')
        ->set('identity_number', 'PA555666777')
        ->set('current_step', 4)
        ->call('createBooking')
        ->assertDispatched('booking:created');

    $booking = Booking::latest()->first();
    
    expect($booking)
        ->identity_type->toBe('passport')
        ->identity_number->toBe('PA555666777')
        ->created_by->toBe($user->id);
});

test('create wizard syncs identity fields to client on creation', function () {
    $user = User::factory()->create();
    $maid = Maid::factory()->create();

    actingAs($user);

    Livewire::test(CreateWizard::class)
        ->set('contact_person', 'New Client')
        ->set('phone', '256712345678')
        ->set('maid_id', $maid->id)
        ->set('booking_date', now()->format('Y-m-d'))
        ->set('identity_type', 'nin')
        ->set('identity_number', 'CM1111111111')
        ->set('current_step', 4)
        ->call('createBooking');

    $client = Client::latest()->first();
    
    expect($client)
        ->identity_type->toBe('nin')
        ->identity_number->toBe('CM1111111111')
        ->created_by->toBe($user->id);
});

test('create wizard tracks audit fields on booking creation', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $maid = Maid::factory()->create();

    actingAs($user);

    Livewire::test(CreateWizard::class)
        ->set('client_id', $client->id)
        ->set('maid_id', $maid->id)
        ->set('booking_date', now()->format('Y-m-d'))
        ->set('current_step', 4)
        ->call('createBooking');

    $booking = Booking::latest()->first();
    
    expect($booking->created_by)->toBe($user->id);
});
