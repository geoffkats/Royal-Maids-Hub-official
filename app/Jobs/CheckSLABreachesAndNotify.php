<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketSLABreachedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSLABreachesAndNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $this->checkActivitySLABreaches();
        $this->checkTicketSLABreaches();
        $this->checkApproachingSLA();
    }

    /**
     * Check for overdue activities in tickets and notify assigned users
     */
    protected function checkActivitySLABreaches(): void
    {
        // Get all open tickets with overdue activities
        $tickets = Ticket::open()
            ->with(['assignedTo', 'comments'])
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->checkSLABreach();
        }

        \Log::info('SLA Check: Processed ' . $tickets->count() . ' open tickets for SLA breaches');
    }

    /**
     * Check for tickets approaching SLA deadline and notify
     */
    protected function checkApproachingSLA(): void
    {
        // Find tickets approaching SLA deadline (within 2 hours)
        $ticketsApproachingSLA = Ticket::open()
            ->where(function ($query) {
                $query->whereBetween('sla_response_due', [now(), now()->addHours(2)])
                    ->orWhereBetween('sla_resolution_due', [now(), now()->addHours(2)]);
            })
            ->whereNotNull('assigned_to')
            ->with(['assignedTo'])
            ->get();

        foreach ($ticketsApproachingSLA as $ticket) {
            if ($ticket->assignedTo) {
                // Send a warning notification
                $ticket->assignedTo->notify(
                    new \App\Notifications\TicketApproachingSLANotification($ticket)
                );
            }
        }

        \Log::info('SLA Check: ' . $ticketsApproachingSLA->count() . ' tickets approaching SLA deadline');
    }

    /**
     * Check for tickets that breached stage SLAs (for opportunities)
     */
    protected function checkTicketSLABreaches(): void
    {
        // This integrates with opportunities/CRM stage SLA checking
        // Already handled in CheckSLABreaches job, included here for completeness
    }
}
