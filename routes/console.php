<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// CRM Scheduled Jobs
Schedule::job(new \App\Jobs\CRM\SendDailyActivityDigest())
    ->dailyAt('08:00')
    ->timezone('America/New_York')
    ->name('crm-daily-activity-digest')
    ->description('Send daily activity digest to all users with pending/overdue activities');

Schedule::job(new \App\Jobs\CRM\CheckSLABreaches())
    ->hourly()
    ->name('crm-check-sla-breaches')
    ->description('Check for activity and stage SLA breaches');

// Ticket SLA Check and Notifications - runs every 30 minutes
Schedule::job(new \App\Jobs\CheckSLABreachesAndNotify())
    ->everyThirtyMinutes()
    ->name('ticket-check-sla-breaches-and-notify')
    ->description('Check for ticket SLA breaches and send notifications');

// Auto-create follow-ups for stale leads (weekly)
Schedule::call(function () {
    $service = new \App\Services\CRM\ActivityReminderService();
    $count = $service->createFollowUpsForStaleLeads(7);
    \Log::info("Created {$count} follow-up activities for stale leads");
})
    ->weekly()
    ->mondays()
    ->at('09:00')
    ->timezone('America/New_York')
    ->name('crm-stale-leads-followup')
    ->description('Create follow-up activities for leads without contact in 7 days');

// CRM Auto Backup - runs based on settings
Schedule::command('crm:backup')
    ->daily()
    ->at('02:00')
    ->timezone('Africa/Kampala')
    ->name('crm-auto-backup')
    ->description('Create automated CRM database backup based on settings');

// CRM Automation Processing - runs every hour
Schedule::call(function () {
    $service = new \App\Services\CRM\CRMAutomationService();
    $processed = $service->processAutomationRules();
    \Log::info("CRM automation processed {$processed} items");
})
    ->hourly()
    ->name('crm-automation-processing')
    ->description('Process CRM automation rules (lead conversion, stage progression, etc.)');

// Contract Expiring Notifications - runs daily
Schedule::command('contracts:send-expiring-notifications --days=30')
    ->dailyAt('09:00')
    ->timezone('Africa/Kampala')
    ->name('contracts-expiring-notifications')
    ->description('Send notifications for contracts expiring within 30 days');

