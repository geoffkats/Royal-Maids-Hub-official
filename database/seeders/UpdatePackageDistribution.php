<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Database\Seeder;

class UpdatePackageDistribution extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $silver = Package::where('name', 'Silver')->first();
        $gold = Package::where('name', 'Gold')->first();
        $platinum = Package::where('name', 'Platinum')->first();

        if (!$gold || !$platinum) {
            $this->command->error('Gold or Platinum package not found!');
            return;
        }

        // Get bookings without packages or currently Silver
        $bookingsToUpdate = Booking::all();
        
        if ($bookingsToUpdate->isEmpty()) {
            $this->command->warn('No bookings found to update');
            return;
        }

        $goldCount = 0;
        $platinumCount = 0;
        $silverCount = 0;

        // Distribute packages: 50% Silver, 30% Gold, 20% Platinum
        foreach ($bookingsToUpdate as $index => $booking) {
            $rand = ($index % 10); // Distribute evenly
            
            if ($rand < 5) {
                // 50% Silver
                $booking->update([
                    'package_id' => $silver->id,
                    'family_size' => $booking->family_size ?? 3,
                    'calculated_price' => $silver->calculatePrice($booking->family_size ?? 3)
                ]);
                $silverCount++;
            } elseif ($rand < 8) {
                // 30% Gold
                $booking->update([
                    'package_id' => $gold->id,
                    'family_size' => $booking->family_size ?? 3,
                    'calculated_price' => $gold->calculatePrice($booking->family_size ?? 3)
                ]);
                $goldCount++;
            } else {
                // 20% Platinum
                $booking->update([
                    'package_id' => $platinum->id,
                    'family_size' => $booking->family_size ?? 3,
                    'calculated_price' => $platinum->calculatePrice($booking->family_size ?? 3)
                ]);
                $platinumCount++;
            }
        }

        $this->command->info("✓ Updated package distribution:");
        $this->command->info("  Silver: {$silverCount} bookings");
        $this->command->info("  Gold: {$goldCount} bookings");
        $this->command->info("  Platinum: {$platinumCount} bookings");
    }
}
