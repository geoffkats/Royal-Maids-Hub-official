<?php

namespace App\Notifications\CRM;

use App\Models\CRM\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OpportunityWonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Opportunity $opportunity;

    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $clientName = $this->opportunity->client 
            ? $this->opportunity->client->name 
            : ($this->opportunity->lead ? $this->opportunity->lead->full_name : 'Unknown');

        $assignedToName = $this->opportunity->assignedTo 
            ? $this->opportunity->assignedTo->name 
            : 'Unassigned';

        return (new MailMessage)
            ->subject('🎉 Opportunity Won: ' . $this->opportunity->title)
            ->greeting('Great News!')
            ->line('An opportunity has been won!')
            ->line('**Opportunity Details:**')
            ->line('Title: ' . $this->opportunity->title)
            ->line('Client: ' . $clientName)
            ->line('Amount: $' . number_format($this->opportunity->amount, 2))
            ->line('Assigned To: ' . $assignedToName)
            ->line('Won Date: ' . $this->opportunity->won_at->format('M d, Y'))
            ->action('View Opportunity', url('/crm/opportunities/' . $this->opportunity->id))
            ->line('Please coordinate with the operations team to begin service delivery.');
    }

    public function toArray($notifiable): array
    {
        return [
            'opportunity_id' => $this->opportunity->id,
            'opportunity_title' => $this->opportunity->title,
            'amount' => $this->opportunity->amount,
            'client_id' => $this->opportunity->client_id,
            'client_name' => $this->opportunity->client ? $this->opportunity->client->name : null,
            'assigned_to' => $this->opportunity->assigned_to,
            'won_at' => $this->opportunity->won_at,
            'message' => 'Opportunity won: ' . $this->opportunity->title . ' - $' . number_format($this->opportunity->amount, 2),
        ];
    }
}
