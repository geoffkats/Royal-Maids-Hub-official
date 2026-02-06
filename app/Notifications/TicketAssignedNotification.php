<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification implements ShouldQueue
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
        $clientName = $this->ticket->client?->contact_person ?? 'N/A';

        return (new MailMessage)
            ->subject("New Ticket Assigned: #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new ticket has been assigned to you.")
            ->line("**Ticket:** #{$this->ticket->ticket_number}")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("**Priority:** {$this->ticket->priority}")
            ->line("**Tier:** {$this->ticket->tier_based_priority}")
            ->line("**Client:** {$clientName}")
            ->when($this->ticket->sla_response_due, function ($message) {
                return $message->line("**First Response Due:** {$this->ticket->sla_response_due->format('Y-m-d H:i:s')}");
            })
            ->line("**Description:** {$this->ticket->description}")
            ->action('View & Respond to Ticket', route('tickets.show', $this->ticket))
            ->line('Please acknowledge and begin working on this ticket as soon as possible.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'priority' => $this->ticket->priority,
            'tier' => $this->ticket->tier_based_priority,
            'assigned_at' => now()->toISOString(),
            'message' => "Ticket #{$this->ticket->ticket_number} assigned to you: {$this->ticket->subject}",
        ];
    }
}
