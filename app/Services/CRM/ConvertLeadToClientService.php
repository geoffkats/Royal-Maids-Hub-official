<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use App\Models\CRM\Activity;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConvertLeadToClientService
{
    /**
     * Convert a lead to a client
     * 
     * @param Lead $lead The lead to convert
     * @param int|null $targetClientId Existing client ID to convert to (optional)
     * @param array $clientData Additional client data if creating new client
     * @return Client The created or existing client
     * @throws \Exception
     */
    public function convert(Lead $lead, ?int $targetClientId = null, array $clientData = []): Client
    {
        // Validation
        if (!$lead->canBeConverted()) {
            throw new \Exception("Lead cannot be converted. Current status: {$lead->status}");
        }

        if ($lead->isConverted()) {
            throw new \Exception('Lead has already been converted.');
        }

        return DB::transaction(function () use ($lead, $targetClientId, $clientData) {
            // Step 1: Check if lead is already linked to a client
            if ($lead->client_id && !$targetClientId) {
                $existingClient = Client::find($lead->client_id);
                if ($existingClient) {
                    // Lead is already linked to a client, just transfer bookings and update status
                    $this->transferBookings($lead, $existingClient);
                    $lead->update(['status' => 'converted', 'converted_at' => now()]);
                    Log::info("Lead #{$lead->id} was already linked to client #{$existingClient->id}. Updated status and transferred bookings.");
                    return $existingClient;
                }
            }

            // Step 2: Create or use existing client
            if ($targetClientId) {
                $client = Client::findOrFail($targetClientId);
                Log::info("Converting lead #{$lead->id} to existing client #{$client->id}");
                // Merge lead data into existing client
                $this->mergeClientData($client, $lead, $clientData);
            } else {
                // Check for existing client before creating new one
                $existingClient = $this->findExistingClient($lead);
                
                if ($existingClient) {
                    $client = $existingClient;
                    Log::info("Found existing client #{$client->id} for lead #{$lead->id}. Merging data.");
                    // Merge lead data into existing client
                    $this->mergeClientData($client, $lead, $clientData);
                } else {
                    $client = $this->createClientFromLead($lead, $clientData);
                    Log::info("Created new client #{$client->id} from lead #{$lead->id}");
                }
            }

            // Step 3: Mark lead as converted
            $lead->markAsConverted($client->id);

            // Step 4: Transfer bookings from lead to client
            $this->transferBookings($lead, $client);

            // Step 5: Re-link tickets from lead to client
            $this->relinkTickets($lead, $client);

            // Step 6: Re-parent opportunities to client
            $this->reparentOpportunities($lead, $client);

            // Step 7: Log conversion activity
            $this->logConversionActivity($lead, $client);

            // Step 8: Update lead status history
            $this->recordStatusChange($lead, 'converted');

            Log::info("Successfully converted lead #{$lead->id} to client #{$client->id}");

            return $client;
        });
    }

    /**
     * Create a new client from lead data
     */
    protected function createClientFromLead(Lead $lead, array $additionalData = []): Client
    {
        // First, create a user account for the client
        $user = User::create([
            'name' => $lead->full_name,
            'email' => $lead->email ?? 'client_' . time() . '@royalmaids.temp',
            'password' => bcrypt(\Str::random(16)), // Random password
            'role' => 'client',
            'email_verified_at' => null, // Client will need to verify
        ]);

        // Then create the client record
        // Extract district from address if not provided
        $district = $lead->district ?? $this->extractDistrictFromAddress($lead->address) ?? 'Kampala';
        
        $client = Client::create([
            'user_id' => $user->id,
            'contact_person' => $lead->full_name,
            // Provide safe fallbacks for required fields
            'phone' => $lead->phone ?: '0000000000',
            'company_name' => $lead->company,
            'address' => $lead->address ?: 'Address not provided',
            'city' => $lead->city ?: 'Kampala',
            'district' => $district,
            'notes' => $lead->notes ? "Converted from lead.\n\n" . $lead->notes : 'Converted from lead.',
            ...$additionalData
        ]);

        return $client;
    }

    /**
     * Re-link all tickets from lead to client
     */
    protected function relinkTickets(Lead $lead, Client $client): void
    {
        $tickets = Ticket::where('requester_type', 'lead')
            ->where('requester_id', $lead->id)
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'requester_type' => 'client',
                'requester_id' => $client->id,
                'client_id' => $client->id, // Also set the direct client_id field
            ]);

            Log::info("Re-linked ticket #{$ticket->ticket_number} from lead to client #{$client->id}");
        }

        Log::info("Re-linked {$tickets->count()} tickets from lead #{$lead->id} to client #{$client->id}");
    }

    /**
     * Re-parent opportunities to client (keep lead_id for audit)
     */
    protected function reparentOpportunities(Lead $lead, Client $client): void
    {
        // Get opportunities that are not won or lost (i.e., open opportunities)
        $opportunities = $lead->opportunities()
            ->whereNull('won_at')
            ->whereNull('lost_at')
            ->get();

        foreach ($opportunities as $opportunity) {
            $opportunity->update([
                'client_id' => $client->id,
                // Keep lead_id for audit trail
            ]);

            // Log stage history
            if ($opportunity->stage_id) {
                $opportunity->stageHistory()->create([
                    'from_stage_id' => $opportunity->stage_id,
                    'to_stage_id' => $opportunity->stage_id,
                    'changed_by' => auth()->id(),
                    'notes' => "Opportunity re-parented to client during lead conversion",
                ]);
            }

            Log::info("Re-parented opportunity #{$opportunity->id} to client #{$client->id}");
        }

        Log::info("Re-parented {$opportunities->count()} opportunities from lead #{$lead->id} to client #{$client->id}");
    }

    /**
     * Log conversion activity
     */
    protected function logConversionActivity(Lead $lead, Client $client): void
    {
        Activity::create([
            'type' => 'note',
            'subject' => 'Lead Converted to Client',
            'description' => "Lead {$lead->full_name} (ID: {$lead->id}) was successfully converted to client {$client->contact_person} (ID: {$client->id}).",
            'status' => 'completed',
            'completed_at' => now(),
            'priority' => 'medium',
            'related_type' => 'lead',
            'related_id' => $lead->id,
            'owner_id' => $lead->owner_id,
            'assigned_to' => $lead->owner_id,
            'created_by' => auth()->id(),
        ]);

        // Also create activity for client
        Activity::create([
            'type' => 'note',
            'subject' => 'Client Created from Lead',
            'description' => "Client account created from lead conversion. Original lead ID: {$lead->id}",
            'status' => 'completed',
            'completed_at' => now(),
            'priority' => 'medium',
            'related_type' => 'client',
            'related_id' => $client->id,
            'owner_id' => $lead->owner_id,
            'assigned_to' => $lead->owner_id,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Record status change in history
     */
    protected function recordStatusChange(Lead $lead, string $newStatus): void
    {
        $lead->statusHistory()->create([
            'from_status' => $lead->getOriginal('status'),
            'to_status' => $newStatus,
            'changed_by' => auth()->id(),
            'reason' => 'Lead converted to client',
            'notes' => "Lead successfully converted. Client ID: {$lead->client_id}",
        ]);
    }

    /**
     * Find existing client by email or phone from lead
     */
    protected function findExistingClient(Lead $lead): ?Client
    {
        $query = Client::query();

        // Check by email (through user relationship)
        if ($lead->email) {
            $clientByEmail = Client::whereHas('user', function($q) use ($lead) {
                $q->where('email', $lead->email);
            })->first();

            if ($clientByEmail) {
                return $clientByEmail;
            }
        }

        // Check by phone
        if ($lead->phone) {
            $normalizedPhone = $this->normalizePhone($lead->phone);
            $clients = Client::whereNotNull('phone')->get();
            foreach ($clients as $client) {
                if ($this->normalizePhone($client->phone) === $normalizedPhone) {
                    return $client;
                }
            }
        }

        return null;
    }

    /**
     * Transfer bookings from lead to client
     */
    protected function transferBookings(Lead $lead, Client $client): void
    {
        $bookings = Booking::where('lead_id', $lead->id)->get();

        foreach ($bookings as $booking) {
            $booking->update([
                'client_id' => $client->id,
                // Keep lead_id for audit trail
            ]);

            Log::info("Transferred booking #{$booking->id} from lead #{$lead->id} to client #{$client->id}");
        }

        // Update client booking counters
        if ($bookings->count() > 0) {
            $client->increment('total_bookings', $bookings->count());
            $activeBookings = $bookings->whereIn('status', ['pending', 'confirmed', 'active'])->count();
            if ($activeBookings > 0) {
                $client->increment('active_bookings', $activeBookings);
            }
        }

        Log::info("Transferred {$bookings->count()} bookings from lead #{$lead->id} to client #{$client->id}");
    }

    /**
     * Merge lead data into existing client
     */
    protected function mergeClientData(Client $client, Lead $lead, array $additionalData = []): void
    {
        $updates = [];

        // Update contact person if missing or different
        if (empty($client->contact_person) || $client->contact_person !== $lead->full_name) {
            $updates['contact_person'] = $lead->full_name;
        }

        // Update phone if missing
        if (empty($client->phone) && !empty($lead->phone)) {
            $updates['phone'] = $lead->phone;
        }

        // Update address if missing
        if (empty($client->address) && !empty($lead->address)) {
            $updates['address'] = $lead->address;
        }

        // Update city if missing
        if (empty($client->city) && !empty($lead->city)) {
            $updates['city'] = $lead->city;
        }

        // Update company name if missing
        if (empty($client->company_name) && !empty($lead->company)) {
            $updates['company_name'] = $lead->company;
        }

        // Merge notes
        if (!empty($lead->notes)) {
            $existingNotes = $client->notes ?? '';
            $updates['notes'] = trim($existingNotes . "\n\n--- Merged from lead #{$lead->id} ---\n" . $lead->notes);
        }

        // Merge additional data
        $updates = array_merge($updates, $additionalData);

        if (!empty($updates)) {
            $client->update($updates);
            Log::info("Merged lead data into existing client #{$client->id}");
        }
    }

    /**
     * Normalize phone number for comparison
     */
    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Extract district from address string
     */
    protected function extractDistrictFromAddress(?string $address): ?string
    {
        if (empty($address)) {
            return null;
        }

        // Common districts in Uganda
        $districts = ['Kampala', 'Wakiso', 'Mukono', 'Jinja', 'Mbarara', 'Gulu', 'Mbale', 'Masaka'];
        
        foreach ($districts as $district) {
            if (stripos($address, $district) !== false) {
                return $district;
            }
        }

        return null;
    }

    /**
     * Handle automatic booking creation if opportunity was won
     */
    public function createBookingIfOpportunityWon(Lead $lead, Client $client): void
    {
        $wonOpportunity = $lead->opportunities()
            ->whereNotNull('won_at')
            ->where('package_id', '!=', null)
            ->first();

        if ($wonOpportunity) {
            // TODO: Create booking/subscription based on won opportunity
            Log::info("Won opportunity found for lead #{$lead->id}. Consider creating booking for client #{$client->id}");
            
            // This would integrate with your booking system
            // Example:
            // Booking::create([
            //     'client_id' => $client->id,
            //     'package_id' => $wonOpportunity->package_id,
            //     'status' => 'pending',
            //     // ... other booking fields
            // ]);
        }
    }
}
