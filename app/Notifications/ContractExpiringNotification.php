<?php

namespace App\Notifications;

use App\Models\MaidContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly MaidContract $contract,
        public readonly int $daysUntilExpiry
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $maidName = $this->contract->maid->full_name;
        $endDate = $this->contract->contract_end_date?->format('M d, Y');
        
        $message = (new MailMessage)
            ->subject("⚠️ Contract Expiring Soon: {$maidName}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("The employment contract for **{$maidName}** will expire in **{$this->daysUntilExpiry} days**.")
            ->line("**Contract Details:**")
            ->line("• **Maid:** {$maidName} ({$this->contract->maid->maid_code})")
            ->line("• **End Date:** {$endDate}")
            ->line("• **Current Status:** " . ucfirst($this->contract->contract_status))
            ->line("• **Days Worked:** {$this->contract->worked_days}")
            ->line("• **Pending Days:** {$this->contract->pending_days}");

        if ($this->contract->getActiveDeployment()) {
            $deployment = $this->contract->getActiveDeployment();
            $clientName = $deployment->client->company_name ?? $deployment->client->contact_person;
            $message->line("• **Client:** {$clientName}");
        }

        return $message
            ->action('View Contract', route('contracts.show', $this->contract))
            ->line('Please review and renew the contract if the maid is to continue employment.');
    }

    public function toArray($notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'maid_id' => $this->contract->maid_id,
            'maid_name' => $this->contract->maid->full_name,
            'maid_code' => $this->contract->maid->maid_code,
            'end_date' => $this->contract->contract_end_date,
            'days_until_expiry' => $this->daysUntilExpiry,
            'status' => $this->contract->contract_status,
            'message' => "Contract for {$this->contract->maid->full_name} expires in {$this->daysUntilExpiry} days",
        ];
    }
}
