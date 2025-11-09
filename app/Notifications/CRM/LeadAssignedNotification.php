<?php

namespace App\Notifications\CRM;

use App\Models\CRM\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Lead $lead;
    protected ?string $assignedBy;

    public function __construct(Lead $lead, ?string $assignedBy = null)
    {
        $this->lead = $lead;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $assignerName = $this->assignedBy ?? 'System';
        
        return (new MailMessage)
            ->subject('New Lead Assigned: ' . $this->lead->full_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new lead has been assigned to you by ' . $assignerName)
            ->line('**Lead Details:**')
            ->line('Name: ' . $this->lead->full_name)
            ->line('Email: ' . ($this->lead->email ?? 'N/A'))
            ->line('Phone: ' . ($this->lead->phone ?? 'N/A'))
            ->line('Company: ' . ($this->lead->company ?? 'N/A'))
            ->line('Status: ' . ucfirst($this->lead->status))
            ->line('Score: ' . $this->lead->score . '/100')
            ->action('View Lead', url('/crm/leads/' . $this->lead->id))
            ->line('Please follow up with this lead as soon as possible.');
    }

    public function toArray($notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'lead_email' => $this->lead->email,
            'lead_phone' => $this->lead->phone,
            'lead_score' => $this->lead->score,
            'assigned_by' => $this->assignedBy,
            'message' => 'New lead assigned: ' . $this->lead->full_name,
        ];
    }
}
