<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketApproachingSLANotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $deadline = $this->ticket->first_response_at 
            ? $this->ticket->sla_resolution_due 
            : $this->ticket->sla_response_due;

        $type = $this->ticket->first_response_at ? 'resolution' : 'response';

        return (new MailMessage)
            ->subject("⏰ Warning: Ticket SLA Approaching - #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your ticket is approaching its SLA deadline.")
            ->line("**Ticket:** #{$this->ticket->ticket_number}")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("**Deadline:** {$deadline->format('Y-m-d H:i:s')} (in {$deadline->diffForHumans()})")
            ->line("**Type:** Remaining {$type} time")
            ->line("**Priority:** {$this->ticket->priority}")
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('Please start working on this ticket immediately to avoid SLA breach.');
    }

    public function toDatabase($notifiable): array
    {
        $deadline = $this->ticket->first_response_at 
            ? $this->ticket->sla_resolution_due 
            : $this->ticket->sla_response_due;

        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'priority' => $this->ticket->priority,
            'sla_deadline' => $deadline->toISOString(),
            'message' => "Ticket #{$this->ticket->ticket_number} SLA deadline approaching: {$deadline->diffForHumans()}",
        ];
    }
}
