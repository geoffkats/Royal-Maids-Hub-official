<?php

declare(strict_types=1);

use App\Models\Booking;
use App\Models\Client;
use App\Models\Deployment;
use App\Models\Maid;
use App\Models\MaidContract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('client can be created with identity fields', function () {
    $user = User::factory()->create();
    
    $client = Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'address' => '123 Main St',
        'city' => 'Kampala',
        'identity_type' => 'nin',
        'identity_number' => 'CM1234567890',
        'created_by' => $user->id,
    ]);

    expect($client)
        ->identity_type->toBe('nin')
        ->identity_number->toBe('CM1234567890');
});

test('identity number must be unique per identity type', function () {
    $user = User::factory()->create();
    
    Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'address' => '123 Main St',
        'city' => 'Kampala',
        'identity_type' => 'nin',
        'identity_number' => 'CM1234567890',
        'created_by' => $user->id,
    ]);

    // Same NIN should fail
    $this->expectException(Exception::class);
    Client::create([
        'user_id' => $user->id,
        'contact_person' => 'Jane Doe',
        'phone' => '256701230000',
        'address' => '456 Main St',
        'city' => 'Entebbe',
        'identity_type' => 'nin',
        'identity_number' => 'CM1234567890',
        'created_by' => $user->id,
    ]);
})->skip('Database constraint check - test only if running with constraint checks');

test('client audit fields track creation user', function () {
    $user = User::factory()->create();
    
    $client = Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'address' => '123 Main St',
        'city' => 'Kampala',
        'created_by' => $user->id,
    ]);

    expect($client->created_by)->toBe($user->id);
});

test('client audit fields track update user', function () {
    $user = User::factory()->create();
    $updater = User::factory()->create();
    
    $client = Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'address' => '123 Main St',
        'city' => 'Kampala',
        'created_by' => $user->id,
    ]);

    $client->update([
        'contact_person' => 'Jane Doe',
        'updated_by' => $updater->id,
    ]);

    $client->refresh();
    expect($client->updated_by)->toBe($updater->id);
});

test('client can be soft deleted', function () {
    $user = User::factory()->create();
    
    $client = Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'address' => '123 Main St',
        'city' => 'Kampala',
        'created_by' => $user->id,
    ]);

    $clientId = $client->id;
    $client->delete();

    expect(Client::find($clientId))->toBeNull();
    expect(Client::withTrashed()->find($clientId))->not->toBeNull();
});

test('booking can store identity fields', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $maid = Maid::factory()->create();
    
    $booking = Booking::create([
        'client_id' => $client->id,
        'maid_id' => $maid->id,
        'booking_date' => now(),
        'start_date' => now()->addDay(),
        'identity_type' => 'passport',
        'identity_number' => 'PA123456789',
        'created_by' => $user->id,
    ]);

    expect($booking)
        ->identity_type->toBe('passport')
        ->identity_number->toBe('PA123456789')
        ->created_by->toBe($user->id);
});

test('booking audit fields track creation and update users', function () {
    $creator = User::factory()->create();
    $updater = User::factory()->create();
    $client = Client::factory()->for($creator)->create();
    $maid = Maid::factory()->create();
    
    $booking = Booking::create([
        'client_id' => $client->id,
        'maid_id' => $maid->id,
        'booking_date' => now(),
        'start_date' => now()->addDay(),
        'created_by' => $creator->id,
    ]);

    expect($booking->created_by)->toBe($creator->id);

    $booking->update(['updated_by' => $updater->id]);
    $booking->refresh();

    expect($booking->updated_by)->toBe($updater->id);
});

test('maid can be soft deleted', function () {
    $maid = Maid::factory()->create();
    $maidId = $maid->id;

    $maid->delete();

    expect(Maid::find($maidId))->toBeNull();
    expect(Maid::withTrashed()->find($maidId))->not->toBeNull();
});

test('maid contract calculates worked days', function () {
    $maid = Maid::factory()->create();
    
    $contract = MaidContract::create([
        'maid_id' => $maid->id,
        'contract_start_date' => now()->subDays(30),
        'contract_end_date' => now()->addDays(30),
        'contract_status' => 'active',
    ]);

    // Create a deployment that falls within contract period
    $deployment = Deployment::factory()
        ->for($maid)
        ->create([
            'contract_start_date' => now()->subDays(10),
            'contract_end_date' => now()->addDays(10),
        ]);

    // Recalculate and verify
    $contract->recalculateDayCounts();
    
    // Should have at least 1 day (current day)
    expect($contract->worked_days)->toBeGreaterThanOrEqual(1);
});

test('maid contract calculates pending days', function () {
    $maid = Maid::factory()->create();
    
    $contract = MaidContract::create([
        'maid_id' => $maid->id,
        'contract_start_date' => now()->subDays(30),
        'contract_end_date' => now()->addDays(15),
        'contract_status' => 'active',
    ]);

    $pendingDays = $contract->calculatePendingDays();
    
    // Should have approximately 15 days pending
    expect($pendingDays)->toBeBetween(14, 16); // Allow for day boundary variations
});

test('deployment tracks financial fields', function () {
    $maid = Maid::factory()->create();
    $client = Client::factory()->create();
    $creator = User::factory()->create();
    
    $deployment = Deployment::create([
        'maid_id' => $maid->id,
        'client_id' => $client->id,
        'deployment_date' => now(),
        'deployment_location' => 'Kampala',
        'maid_salary' => 500000,
        'client_payment' => 800000,
        'service_paid' => 0,
        'payment_status' => 'pending',
        'currency' => 'UGX',
        'created_by' => $creator->id,
    ]);

    expect($deployment)
        ->maid_salary->toBe(500000.00)
        ->client_payment->toBe(800000.00)
        ->service_paid->toBe(0.00)
        ->payment_status->toBe('pending')
        ->currency->toBe('UGX')
        ->created_by->toBe($creator->id);
});

test('deployment can be soft deleted', function () {
    $maid = Maid::factory()->create();
    $deployment = Deployment::factory()->for($maid)->create();
    $deploymentId = $deployment->id;

    $deployment->delete();

    expect(Deployment::find($deploymentId))->toBeNull();
    expect(Deployment::withTrashed()->find($deploymentId))->not->toBeNull();
});

test('maid relationship to contracts works', function () {
    $maid = Maid::factory()->create();
    
    MaidContract::factory()
        ->count(3)
        ->for($maid)
        ->create();

    expect($maid->contracts)->toHaveCount(3);
});

test('identity type must be valid enum value', function () {
    $user = User::factory()->create();
    
    // This should fail validation if enum is properly enforced
    $this->expectException(Exception::class);
    
    Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'identity_type' => 'invalid_type',
        'identity_number' => 'SOME123',
        'created_by' => $user->id,
    ]);
})->skip('Enum validation - test only if database enforces check constraint');

test('client with soft delete can be restored', function () {
    $user = User::factory()->create();
    
    $client = Client::create([
        'user_id' => $user->id,
        'contact_person' => 'John Doe',
        'phone' => '256701234567',
        'address' => '123 Main St',
        'city' => 'Kampala',
        'created_by' => $user->id,
    ]);

    $clientId = $client->id;
    $client->delete();
    
    expect(Client::find($clientId))->toBeNull();
    
    Client::withTrashed()->find($clientId)->restore();
    
    expect(Client::find($clientId))->not->toBeNull();
});
