<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketAutoAssignmentService
{
    /**
     * Auto-assign a ticket based on configured rules
     */
    public function assign(Ticket $ticket): ?User
    {
        // Check if auto-assignment is enabled
        if (!$this->isAutoAssignmentEnabled()) {
            return null;
        }

        // If ticket is already assigned, skip
        if ($ticket->assigned_to) {
            Log::info("Ticket {$ticket->ticket_number} already assigned, skipping auto-assignment");
            return null;
        }

        // Get assignment strategy from settings
        $strategy = $this->getAssignmentStrategy();

        $assignedUser = match ($strategy) {
            'workload' => $this->assignByWorkload($ticket),
            'round-robin' => $this->assignByRoundRobin($ticket),
            'category' => $this->assignByCategory($ticket),
            default => $this->assignByWorkload($ticket),
        };

        if ($assignedUser) {
            $ticket->update(['assigned_to' => $assignedUser->id]);
            
            // Log assignment action
            $ticket->comments()->create([
                'user_id' => 1, // System user
                'body' => "🤖 **System auto-assigned** to {$assignedUser->name} (strategy: {$strategy})",
                'is_internal' => true,
            ]);

            // Send notification to assigned user
            $assignedUser->notify(new TicketAssignedNotification($ticket));

            Log::info("Ticket {$ticket->ticket_number} auto-assigned to {$assignedUser->name} using {$strategy} strategy");

            return $assignedUser;
        }

        Log::warning("Could not find available user for auto-assignment of ticket {$ticket->ticket_number}");
        return null;
    }

    /**
     * Assign ticket to user with least open tickets (workload-based)
     */
    protected function assignByWorkload(Ticket $ticket): ?User
    {
        // Get staff members eligible for this ticket
        $eligibleUsers = $this->getEligibleUsers($ticket);

        if ($eligibleUsers->isEmpty()) {
            return null;
        }

        // Count open tickets per user
        $userWorkload = [];
        foreach ($eligibleUsers as $user) {
            $openCount = Ticket::where('assigned_to', $user->id)
                ->whereIn('status', ['open', 'in_progress', 'pending'])
                ->count();
            
            $userWorkload[$user->id] = [
                'user' => $user,
                'open_tickets' => $openCount,
            ];
        }

        // Sort by workload (ascending)
        usort($userWorkload, fn($a, $b) => $a['open_tickets'] <=> $b['open_tickets']);

        return $userWorkload[0]['user'] ?? null;
    }

    /**
     * Assign ticket using round-robin rotation
     */
    protected function assignByRoundRobin(Ticket $ticket): ?User
    {
        $eligibleUsers = $this->getEligibleUsers($ticket);

        if ($eligibleUsers->isEmpty()) {
            return null;
        }

        // Get the user who was last assigned a ticket
        $lastAssignedUser = Ticket::whereIn('assigned_to', $eligibleUsers->pluck('id'))
            ->latest('created_at')
            ->first()?->assignedTo;

        if (!$lastAssignedUser) {
            return $eligibleUsers->first();
        }

        // Find next user in round-robin order
        $userIds = $eligibleUsers->pluck('id')->toArray();
        $currentIndex = array_search($lastAssignedUser->id, $userIds);

        if ($currentIndex === false || $currentIndex === count($userIds) - 1) {
            return $eligibleUsers->first();
        }

        return $eligibleUsers[$currentIndex + 1];
    }

    /**
     * Assign ticket based on category expertise
     */
    protected function assignByCategory(Ticket $ticket): ?User
    {
        $eligibleUsers = $this->getEligibleUsers($ticket);

        if ($eligibleUsers->isEmpty()) {
            return null;
        }

        // TODO: Implement category-based expertise routing
        // For now, fallback to workload-based assignment
        return $this->assignByWorkload($ticket);
    }

    /**
     * Get users eligible for ticket assignment based on role and tier
     */
    protected function getEligibleUsers(Ticket $ticket): \Illuminate\Database\Eloquent\Collection
    {
        // Base: get all active staff (admin, trainer, support roles)
        $users = User::whereIn('role', ['admin', 'trainer'])
            ->get();

        // Filter: Platinum tier tickets should be assigned to priority/senior staff only (if configured)
        if ($ticket->tier_based_priority === 'platinum') {
            // Prioritize admin/senior staff
            $admin = $users->where('role', 'admin')->first();
            if ($admin) {
                return User::whereIn('id', [$admin->id])->get();
            }
        }

        return $users;
    }

    /**
     * Check if auto-assignment is enabled in settings
     */
    protected function isAutoAssignmentEnabled(): bool
    {
        $setting = DB::table('crm_settings')
            ->where('key', 'ticket_auto_assign_enabled')
            ->value('value');

        return $setting === '1' || $setting === 'true';
    }

    /**
     * Get configured assignment strategy from settings
     */
    protected function getAssignmentStrategy(): string
    {
        $strategy = DB::table('crm_settings')
            ->where('key', 'ticket_assignment_strategy')
            ->value('value');

        return $strategy ?? 'workload'; // Default to workload-based
    }

    /**
     * Bulk assign multiple unassigned tickets
     */
    public function assignBulk(array $ticketIds): array
    {
        $results = [];

        foreach ($ticketIds as $ticketId) {
            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                $assignedUser = $this->assign($ticket);
                $results[$ticketId] = [
                    'success' => !!$assignedUser,
                    'assigned_to' => $assignedUser?->name,
                    'assigned_to_id' => $assignedUser?->id,
                ];
            }
        }

        return $results;
    }

    /**
     * Get statistics on assignment effectiveness
     */
    public function getAssignmentStats(): array
    {
        $totalTickets = Ticket::count();
        $unassignedTickets = Ticket::whereNull('assigned_to')->count();
        $avgTicketsPerUser = $totalTickets > 0 ? round($totalTickets / User::where('role', '!=', 'client')->count(), 1) : 0;

        $userWorkload = User::where('role', '!=', 'client')
            ->selectRaw('users.id, users.name, COUNT(tickets.id) as ticket_count')
            ->leftJoin('tickets', 'tickets.assigned_to', '=', 'users.id')
            ->whereIn('tickets.status', ['open', 'in_progress', 'pending'])
            ->groupBy('users.id', 'users.name')
            ->get();

        return [
            'total_tickets' => $totalTickets,
            'unassigned_tickets' => $unassignedTickets,
            'avg_tickets_per_user' => $avgTicketsPerUser,
            'user_workload' => $userWorkload,
            'assignment_strategy' => $this->getAssignmentStrategy(),
            'auto_assignment_enabled' => $this->isAutoAssignmentEnabled(),
        ];
    }
}
