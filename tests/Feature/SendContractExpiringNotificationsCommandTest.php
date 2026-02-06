<?php

declare(strict_types=1);

use App\Models\MaidContract;
use App\Models\User;
use App\Notifications\ContractExpiringNotification;
use Illuminate\Support\Facades\Notification;

describe('SendContractExpiringNotifications Command', function () {
    it('sends notifications for contracts expiring within default 30 days', function () {
        Notification::fake();

        // Create users to notify
        $admins = User::factory(2)->admin()->create();
        User::factory()->trainer()->create();

        // Create contracts at different expiry times
        $maid1 = \App\Models\Maid::factory()->create();
        $maid2 = \App\Models\Maid::factory()->create();
        $maid3 = \App\Models\Maid::factory()->create();

        // Contract expiring in 15 days (should be notified)
        MaidContract::factory()
            ->for($maid1)
            ->create([
                'contract_end_date' => now()->addDays(15),
                'contract_status' => 'active',
            ]);

        // Contract expiring in 25 days (should be notified)
        MaidContract::factory()
            ->for($maid2)
            ->create([
                'contract_end_date' => now()->addDays(25),
                'contract_status' => 'active',
            ]);

        // Contract expiring in 45 days (should NOT be notified - beyond 30 day window)
        MaidContract::factory()
            ->for($maid3)
            ->create([
                'contract_end_date' => now()->addDays(45),
                'contract_status' => 'active',
            ]);

        $this->artisan('contracts:send-expiring-notifications')
            ->assertSuccessful();

        // Verify notifications were sent for the correct contracts
        Notification::assertSentTo($admins[0], ContractExpiringNotification::class);
    });

    it('respects custom days option', function () {
        Notification::fake();

        User::factory()->admin()->create();

        $maid = \App\Models\Maid::factory()->create();
        MaidContract::factory()
            ->for($maid)
            ->create([
                'contract_end_date' => now()->addDays(60),
                'contract_status' => 'active',
            ]);

        $this->artisan('contracts:send-expiring-notifications --days=90')
            ->assertSuccessful();

        Notification::assertSentTo(
            User::where('role', 'admin')->first(),
            ContractExpiringNotification::class
        );
    });

    it('ignores inactive contracts', function () {
        Notification::fake();

        $admin = User::factory()->admin()->create();

        // Delete any previously created contracts
        MaidContract::query()->delete();

        $maid = \App\Models\Maid::factory()->create();
        MaidContract::factory()
            ->for($maid)
            ->create([
                'contract_end_date' => now()->addDays(10),
                'contract_status' => 'completed', // Not active
            ]);

        $this->artisan('contracts:send-expiring-notifications')
            ->assertSuccessful();

        // Verify no notifications sent when no active contracts
        Notification::assertNotSentTo($admin, ContractExpiringNotification::class);
    });

    it('sends notifications to all admin and trainer users', function () {
        Notification::fake();

        $admin1 = User::factory()->admin()->create();
        $admin2 = User::factory()->admin()->create();
        $trainer1 = User::factory()->trainer()->create();

        $maid = \App\Models\Maid::factory()->create();
        $contract = MaidContract::factory()
            ->for($maid)
            ->create([
                'contract_end_date' => now()->addDays(15),
                'contract_status' => 'active',
            ]);

        $this->artisan('contracts:send-expiring-notifications')
            ->assertSuccessful();

        // Verify all admins and trainers received the notification
        Notification::assertSentTo($admin1, ContractExpiringNotification::class);
        Notification::assertSentTo($admin2, ContractExpiringNotification::class);
        Notification::assertSentTo($trainer1, ContractExpiringNotification::class);
    });
});
