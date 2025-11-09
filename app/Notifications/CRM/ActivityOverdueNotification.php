<?php

namespace App\Notifications\CRM;

use Illuminate\Support\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Collection $overdueActivities;

    public function __construct(Collection $overdueActivities)
    {
        $this->overdueActivities = $overdueActivities;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $count = $this->overdueActivities->count();
        
        $message = (new MailMessage)
            ->subject("You have {$count} overdue " . str_plural('activity', $count))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("You have {$count} overdue " . str_plural('activity', $count) . ' that require your attention:');

        foreach ($this->overdueActivities->take(10) as $activity) {
            $daysOverdue = now()->diffInDays($activity->due_date);
            $message->line("• **{$activity->subject}** - {$daysOverdue} " . str_plural('day', $daysOverdue) . ' overdue');
        }

        if ($count > 10) {
            $message->line("... and " . ($count - 10) . " more");
        }

        return $message
            ->action('View All Activities', url('/crm/activities'))
            ->line('Please complete these activities as soon as possible.');
    }

    public function toArray($notifiable): array
    {
        return [
            'overdue_count' => $this->overdueActivities->count(),
            'activities' => $this->overdueActivities->map(fn($activity) => [
                'id' => $activity->id,
                'subject' => $activity->subject,
                'due_date' => $activity->due_date,
                'type' => $activity->type,
                'priority' => $activity->priority,
            ])->toArray(),
            'message' => $this->overdueActivities->count() . ' overdue activities require your attention',
        ];
    }
}
