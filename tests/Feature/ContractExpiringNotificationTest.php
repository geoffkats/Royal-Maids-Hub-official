<?php

declare(strict_types=1);

use App\Models\MaidContract;
use App\Models\User;
use App\Notifications\ContractExpiringNotification;
use Illuminate\Support\Facades\Notification;

describe('Contract Expiring Notifications', function () {
    beforeEach(function () {
        Notification::fake();
    });

    it('sends contract expiring notification with correct details', function () {
        $maid = \App\Models\Maid::factory()->create();
        $contract = MaidContract::factory()
            ->for($maid)
            ->create([
                'contract_start_date' => now()->subDays(30),
                'contract_end_date' => now()->addDays(15),
                'contract_status' => 'active',
                'worked_days' => 30,
                'pending_days' => 5,
            ]);

        $user = User::factory()->admin()->create();

        $user->notify(new ContractExpiringNotification($contract, 15));

        Notification::assertSentTo(
            $user,
            ContractExpiringNotification::class,
            function ($notification) use ($contract) {
                return $notification->contract->id === $contract->id
                    && $notification->daysUntilExpiry === 15;
            }
        );
    });

    it('notification includes maid details in database array', function () {
        $maid = \App\Models\Maid::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);
        $contract = MaidContract::factory()
            ->for($maid)
            ->create([
                'contract_end_date' => now()->addDays(20),
                'contract_status' => 'active',
            ]);

        $user = User::factory()->admin()->create();
        $notification = new ContractExpiringNotification($contract, 20);
        $databaseArray = $notification->toArray($user);

        expect($databaseArray)
            ->toHaveKey('contract_id', $contract->id)
            ->toHaveKey('maid_name', 'Jane Doe')
            ->toHaveKey('days_until_expiry', 20)
            ->toHaveKey('status', 'active');
    });

    it('notification uses mail and database channels', function () {
        $maid = \App\Models\Maid::factory()->create();
        $contract = MaidContract::factory()->for($maid)->create();
        $user = User::factory()->admin()->create();
        $notification = new ContractExpiringNotification($contract, 10);

        expect($notification->via($user))
            ->toBe(['mail', 'database']);
    });
});
