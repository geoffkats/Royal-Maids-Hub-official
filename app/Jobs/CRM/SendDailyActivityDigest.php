<?php

namespace App\Jobs\CRM;

use App\Models\User;
use App\Services\CRM\ActivityReminderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDailyActivityDigest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(ActivityReminderService $reminderService): void
    {
        // Get all users with CRM access (admin, trainer roles)
        $users = User::whereIn('role', ['admin', 'trainer'])->get();

        foreach ($users as $user) {
            $overdueActivities = $reminderService->getOverdueActivitiesForUser($user->id);
            $dueSoonActivities = $reminderService->getActivitiesDueSoonForUser($user->id, 24);

            // Skip if user has no activities
            if ($overdueActivities->isEmpty() && $dueSoonActivities->isEmpty()) {
                continue;
            }

            // Send digest email
            Mail::send('emails.crm.daily-activity-digest', [
                'user' => $user,
                'overdueActivities' => $overdueActivities,
                'dueSoonActivities' => $dueSoonActivities,
            ], function ($message) use ($user, $overdueActivities, $dueSoonActivities) {
                $overdueCount = $overdueActivities->count();
                $dueSoonCount = $dueSoonActivities->count();
                
                $subject = 'Daily Activity Digest';
                if ($overdueCount > 0) {
                    $subject .= " - {$overdueCount} Overdue";
                }
                if ($dueSoonCount > 0) {
                    $subject .= ", {$dueSoonCount} Due Soon";
                }
                
                $message->to($user->email)
                    ->subject($subject);
            });
        }
    }
}
