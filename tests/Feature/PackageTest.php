<?php

use App\Models\Package;
use App\Models\Booking;
use App\Models\User;

test('it can create a package', function () {
    $package = Package::factory()->create([
        'name' => 'Silver',
        'base_price' => 300000,
    ]);

    expect($package)->toBeInstanceOf(Package::class);
    $this->assertDatabaseHas('packages', [
        'name' => 'Silver',
        'base_price' => 300000,
    ]);
});

test('it calculates price correctly for base family size', function () {
    $package = Package::factory()->create([
        'base_price' => 300000,
        'base_family_size' => 3,
        'additional_member_cost' => 35000,
    ]);

    $price = $package->calculatePrice(3);
    
    expect($price)->toBe(300000.0);
});

test('it calculates price correctly for larger family', function () {
    $package = Package::factory()->create([
        'base_price' => 300000,
        'base_family_size' => 3,
        'additional_member_cost' => 35000,
    ]);

    // Family of 5: base (3) + 2 extra members
    $price = $package->calculatePrice(5);
    
    expect($price)->toBe(370000.0); // 300000 + (2 * 35000)
});

test('it calculates price correctly for family of 8', function () {
    $package = Package::factory()->create([
        'base_price' => 500000,
        'base_family_size' => 3,
        'additional_member_cost' => 35000,
    ]);

    // Family of 8: base (3) + 5 extra members
    $price = $package->calculatePrice(8);
    
    expect($price)->toBe(675000.0); // 500000 + (5 * 35000)
});

test('it has correct badge color for silver', function () {
    $package = Package::factory()->silver()->create();
    
    expect($package->badge_color)->toBe('zinc');
});

test('it has correct badge color for gold', function () {
    $package = Package::factory()->gold()->create();
    
    expect($package->badge_color)->toBe('yellow');
});

test('it has correct badge color for platinum', function () {
    $package = Package::factory()->platinum()->create();
    
    expect($package->badge_color)->toBe('purple');
});

test('it formats price correctly', function () {
    $package = Package::factory()->create([
        'base_price' => 500000,
    ]);

    expect($package->formatted_price)->toBe('500,000 UGX/month');
});

test('it returns correct icon for each tier', function () {
    $silver = Package::factory()->silver()->create();
    $gold = Package::factory()->gold()->create();
    $platinum = Package::factory()->platinum()->create();

    expect($silver->icon)->toBe('shield');
    expect($gold->icon)->toBe('star');
    expect($platinum->icon)->toBe('sparkles');
});

test('active scope returns only active packages', function () {
    Package::query()->delete(); // Clear all packages
    
    Package::factory()->create(['is_active' => true, 'name' => 'Active 1']);
    Package::factory()->create(['is_active' => true, 'name' => 'Active 2']);
    Package::factory()->create(['is_active' => false, 'name' => 'Inactive']);

    $activePackages = Package::active()->get();

    expect($activePackages)->toHaveCount(2);
    expect($activePackages->every(fn($p) => $p->is_active))->toBeTrue();
});

test('active scope orders by sort order', function () {
    Package::query()->delete(); // Clear all packages
    
    Package::factory()->create(['is_active' => true, 'sort_order' => 3, 'name' => 'Third']);
    Package::factory()->create(['is_active' => true, 'sort_order' => 1, 'name' => 'First']);
    Package::factory()->create(['is_active' => true, 'sort_order' => 2, 'name' => 'Second']);

    $packages = Package::active()->get();

    expect($packages[0]->name)->toBe('First');
    expect($packages[1]->name)->toBe('Second');
    expect($packages[2]->name)->toBe('Third');
});

test('it has bookings relationship', function () {
    $package = Package::factory()->create();
    $booking = Booking::factory()->create(['package_id' => $package->id]);

    expect($package->bookings->contains($booking))->toBeTrue();
});

test('booking has package relationship', function () {
    $package = Package::factory()->create(['name' => 'Gold']);
    $booking = Booking::factory()->create(['package_id' => $package->id]);

    expect($booking->package->name)->toBe('Gold');
});

test('booking calculates price from package', function () {
    $package = Package::factory()->create([
        'base_price' => 500000,
        'base_family_size' => 3,
        'additional_member_cost' => 35000,
    ]);

    $booking = Booking::factory()->create([
        'package_id' => $package->id,
        'family_size' => 6,
    ]);

    $calculatedPrice = $booking->calculateBookingPrice();

    // Family of 6: 500000 + (3 * 35000) = 605000
    expect($calculatedPrice)->toBe(605000.0);
});

test('package factory creates silver with correct data', function () {
    $package = Package::factory()->silver()->create();

    expect($package->name)->toBe('Silver');
    expect($package->tier)->toBe('Standard');
    expect((float)$package->base_price)->toBe(300000.0);
    expect($package->training_weeks)->toBe(4);
    expect($package->backup_days_per_year)->toBe(21);
    expect($package->free_replacements)->toBe(2);
    expect($package->evaluations_per_year)->toBe(3);
});

test('package factory creates gold with correct data', function () {
    $package = Package::factory()->gold()->create();

    expect($package->name)->toBe('Gold');
    expect($package->tier)->toBe('Premium');
    expect((float)$package->base_price)->toBe(500000.0);
    expect($package->training_weeks)->toBe(6);
    expect($package->backup_days_per_year)->toBe(30);
    expect($package->training_includes)->toContain('Hospitality and Customer Service');
});

test('package factory creates platinum with correct data', function () {
    $package = Package::factory()->platinum()->create();

    expect($package->name)->toBe('Platinum');
    expect($package->tier)->toBe('Elite');
    expect((float)$package->base_price)->toBe(1000000.0);
    expect($package->training_weeks)->toBe(8);
    expect($package->backup_days_per_year)->toBe(45);
    expect($package->free_replacements)->toBe(3);
    expect($package->training_includes)->toContain('Self Defense');
});

test('json fields are properly cast', function () {
    $package = Package::factory()->create([
        'training_includes' => ['Training 1', 'Training 2'],
        'features' => ['Feature 1', 'Feature 2', 'Feature 3'],
    ]);

    expect($package->training_includes)->toBeArray();
    expect($package->features)->toBeArray();
    expect($package->training_includes)->toHaveCount(2);
    expect($package->features)->toHaveCount(3);
});
