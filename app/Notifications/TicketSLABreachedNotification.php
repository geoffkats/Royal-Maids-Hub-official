<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketSLABreachedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $breachType = 'response', // 'response' or 'resolution'
        public float $hoursOverdue = 0
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = match ($this->breachType) {
            'response' => 'URGENT: Ticket Response SLA Breached',
            'resolution' => 'URGENT: Ticket Resolution SLA Breached',
            default => 'URGENT: Ticket SLA Breached',
        };

        $message = match ($this->breachType) {
            'response' => "This ticket has not received a response within the SLA deadline and is now {$this->hoursOverdue} hours overdue.",
            'resolution' => "This ticket has not been resolved within the SLA deadline and is now {$this->hoursOverdue} hours overdue.",
            default => "This ticket has breached its SLA deadline by {$this->hoursOverdue} hours.",
        };

        $clientName = $this->ticket->client?->contact_person ?? 'N/A';

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line("**Ticket #{$this->ticket->ticket_number}** - {$this->ticket->subject}")
            ->line($message)
            ->line("**Priority:** {$this->ticket->priority}")
            ->line("**Tier:** {$this->ticket->tier_based_priority}")
            ->line("**Client:** {$clientName}")
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('Immediate action is required to meet SLA requirements.')
            ->line('Thank you for your swift attention to this matter.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'breach_type' => $this->breachType,
            'hours_overdue' => round($this->hoursOverdue, 1),
            'priority' => $this->ticket->priority,
            'tier' => $this->ticket->tier_based_priority,
            'message' => "Ticket #{$this->ticket->ticket_number} has breached {$this->breachType} SLA by {$this->hoursOverdue} hours.",
        ];
    }
}
