<?php

namespace Database\Seeders;

use App\Models\Trainer;
use App\Models\TrainerSidebarPermission;
use Illuminate\Database\Seeder;

class TrainerSidebarPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Default trainer-accessible items (excludes admin-only items)
        $defaultItems = [
            // Training & Development
            TrainerSidebarPermission::ITEM_MY_PROGRAMS,
            TrainerSidebarPermission::ITEM_MY_EVALUATIONS,
            TrainerSidebarPermission::ITEM_DEPLOYMENTS,
            TrainerSidebarPermission::ITEM_WEEKLY_BOARD,
            // Analytics & Reports
            TrainerSidebarPermission::ITEM_REPORTS,
            TrainerSidebarPermission::ITEM_KPI_DASHBOARD,
            // Support & Tickets
            TrainerSidebarPermission::ITEM_TICKETS,
            TrainerSidebarPermission::ITEM_TICKETS_INBOX,
            // CRM (core access)
            TrainerSidebarPermission::ITEM_CRM_PIPELINE,
            TrainerSidebarPermission::ITEM_CRM_LEADS,
            TrainerSidebarPermission::ITEM_CRM_OPPORTUNITIES,
            TrainerSidebarPermission::ITEM_CRM_ACTIVITIES,
            TrainerSidebarPermission::ITEM_CRM_REPORTS,
            // Business
            TrainerSidebarPermission::ITEM_BOOKINGS,
            TrainerSidebarPermission::ITEM_SCHEDULE,
        ];

        $trainers = Trainer::all();

        foreach ($trainers as $trainer) {
            foreach ($defaultItems as $item) {
                TrainerSidebarPermission::firstOrCreate(
                    [
                        'trainer_id' => $trainer->id,
                        'sidebar_item' => $item,
                    ],
                    [
                        'granted_at' => now(),
                    ]
                );
            }
        }

        echo "✅ Trainer sidebar permissions seeded successfully!\n";
        echo "📊 Total trainers with permissions: " . $trainers->count() . "\n";
    }
}
