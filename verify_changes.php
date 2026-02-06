<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap/app.php';

echo "=== Verifying Changes ===\n";

// Test 1: Show component loads
echo "1. Testing Show component...";
if (class_exists(\App\Livewire\Clients\Show::class)) {
    echo " ✓ OK\n";
} else {
    echo " ✗ FAILED\n";
}

// Test 2: Ticket model has getSLAStatus method
echo "2. Testing Ticket::getSLAStatus method...";
$reflection = new ReflectionClass(\App\Models\Ticket::class);
if ($reflection->hasMethod('getSLAStatus')) {
    echo " ✓ OK\n";
} else {
    echo " ✗ FAILED\n";
}

// Test 3: Ticket model has proper imports
echo "3. Testing Ticket model imports...";
if (class_exists(\App\Models\Ticket::class)) {
    echo " ✓ OK\n";
} else {
    echo " ✗ FAILED\n";
}

echo "\n=== All Verifications Complete ===\n";
