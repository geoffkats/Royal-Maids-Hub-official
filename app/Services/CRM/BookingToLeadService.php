<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use App\Models\CRM\Source;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingToLeadService
{
    protected DuplicateDetectionService $duplicateDetectionService;

    public function __construct(DuplicateDetectionService $duplicateDetectionService)
    {
        $this->duplicateDetectionService = $duplicateDetectionService;
    }

    /**
     * Find or create a lead from booking form data
     * 
     * @param array $bookingData Booking form data
     * @return Lead The found or created lead
     */
    public function findOrCreateLeadFromBooking(array $bookingData): Lead
    {
        return DB::transaction(function () use ($bookingData) {
            // Extract contact information
            $email = $bookingData['email'] ?? null;
            $phone = $bookingData['phone'] ?? null;
            $fullName = $bookingData['full_name'] ?? '';

            // Split full name into first and last name
            $nameParts = $this->splitFullName($fullName);
            $firstName = $nameParts['first_name'];
            $lastName = $nameParts['last_name'];

            // Check for existing lead
            $existingLead = $this->checkForExistingLead($email, $phone);

            // Check for existing client
            $existingClient = $this->duplicateDetectionService->findExistingClient([
                'email' => $email,
                'phone' => $phone,
            ]);

            if ($existingLead) {
                // Lead exists - update it with booking data if needed
                Log::info("Found existing lead #{$existingLead->id} for booking", [
                    'lead_id' => $existingLead->id,
                    'email' => $email,
                ]);

                // Update lead with any missing information
                $this->mergeLeadWithBooking($existingLead, $bookingData);

                // If client exists, link lead to client
                if ($existingClient && !$existingLead->client_id) {
                    $existingLead->update(['client_id' => $existingClient->id]);
                    Log::info("Linked existing lead #{$existingLead->id} to existing client #{$existingClient->id}");
                }

                return $existingLead->fresh();
            }

            // No lead exists - create new one
            // If client exists, link the new lead to the client
            $clientId = $existingClient ? $existingClient->id : null;
            
            // Determine lead status based on whether client exists
            $status = $existingClient ? 'qualified' : 'new';

            // Get or create Website source
            $websiteSource = $this->getWebsiteSource();

            // Get default owner (admin or system user)
            $ownerId = $this->getDefaultOwnerId();

            $lead = Lead::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'city' => $bookingData['city'] ?? null,
                'address' => $this->buildAddress($bookingData),
                'source_id' => $websiteSource->id,
                'owner_id' => $ownerId,
                'status' => $status,
                'client_id' => $clientId,
                'interested_package_id' => $bookingData['package_id'] ?? null,
                'score' => $this->calculateInitialScore($bookingData),
                'notes' => $this->buildLeadNotes($bookingData),
            ]);

            Log::info("Created new lead #{$lead->id} from booking form", [
                'lead_id' => $lead->id,
                'email' => $email,
                'client_id' => $clientId,
            ]);

            return $lead;
        });
    }

    /**
     * Check for existing lead by email or phone
     */
    protected function checkForExistingLead(?string $email, ?string $phone): ?Lead
    {
        $query = Lead::query();

        if ($email) {
            $query->where('email', $email);
        }

        if ($phone) {
            $normalizedPhone = $this->normalizePhone($phone);
            $leads = Lead::whereNotNull('phone')->get();
            foreach ($leads as $lead) {
                if ($this->normalizePhone($lead->phone) === $normalizedPhone) {
                    return $lead;
                }
            }
        }

        if ($email) {
            return $query->first();
        }

        return null;
    }

    /**
     * Merge booking data into existing lead
     */
    protected function mergeLeadWithBooking(Lead $lead, array $bookingData): void
    {
        $updates = [];

        // Update city if missing
        if (empty($lead->city) && !empty($bookingData['city'])) {
            $updates['city'] = $bookingData['city'];
        }

        // Update address if missing
        if (empty($lead->address) && !empty($bookingData['address'])) {
            $updates['address'] = $this->buildAddress($bookingData);
        }

        // Update package interest if missing
        if (empty($lead->interested_package_id) && !empty($bookingData['package_id'])) {
            $updates['interested_package_id'] = $bookingData['package_id'];
        }

        // Update notes with booking reference
        if (!empty($bookingData)) {
            $bookingNote = "\n\n--- Booking submitted: " . now()->format('Y-m-d H:i') . " ---";
            $updates['notes'] = ($lead->notes ?? '') . $bookingNote;
        }

        if (!empty($updates)) {
            $lead->update($updates);
        }
    }

    /**
     * Get or create Website source
     */
    protected function getWebsiteSource(): Source
    {
        return Source::firstOrCreate(
            ['name' => 'Website'],
            ['active' => true]
        );
    }

    /**
     * Get default owner ID (first admin or system user)
     */
    protected function getDefaultOwnerId(): int
    {
        $admin = User::whereIn('role', ['admin', 'trainer'])
            ->first();

        return $admin ? $admin->id : 1; // Fallback to user ID 1
    }

    /**
     * Split full name into first and last name
     */
    protected function splitFullName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName));
        
        if (count($parts) === 1) {
            return [
                'first_name' => $parts[0],
                'last_name' => '',
            ];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
    }

    /**
     * Build address string from booking data
     */
    protected function buildAddress(array $bookingData): ?string
    {
        $parts = array_filter([
            $bookingData['address'] ?? null,
            $bookingData['city'] ?? null,
            $bookingData['division'] ?? null,
            $bookingData['parish'] ?? null,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Calculate initial lead score from booking data
     */
    protected function calculateInitialScore(array $bookingData): int
    {
        $score = 0;

        // Basic information completeness
        if (!empty($bookingData['email'])) $score += 10;
        if (!empty($bookingData['phone'])) $score += 10;
        if (!empty($bookingData['full_name'])) $score += 10;
        if (!empty($bookingData['city'])) $score += 5;
        if (!empty($bookingData['address'])) $score += 5;

        // Package selection indicates interest
        if (!empty($bookingData['package_id'])) $score += 20;

        // Service details indicate serious interest
        if (!empty($bookingData['service_tier'])) $score += 15;
        if (!empty($bookingData['start_date'])) $score += 10;
        if (!empty($bookingData['work_days']) && is_array($bookingData['work_days'])) {
            $score += 5;
        }

        // Family size and details indicate commitment
        if (!empty($bookingData['family_size'])) $score += 10;

        return min($score, 100); // Cap at 100
    }

    /**
     * Build lead notes from booking data
     */
    protected function buildLeadNotes(array $bookingData): ?string
    {
        $notes = [];

        if (!empty($bookingData['special_requirements'])) {
            $notes[] = "Special Requirements: " . $bookingData['special_requirements'];
        }

        if (!empty($bookingData['additional_requirements'])) {
            $notes[] = "Additional Requirements: " . $bookingData['additional_requirements'];
        }

        if (!empty($bookingData['service_tier'])) {
            $notes[] = "Interested in: " . $bookingData['service_tier'] . " service tier";
        }

        if (!empty($bookingData['start_date'])) {
            $notes[] = "Preferred start date: " . $bookingData['start_date'];
        }

        return !empty($notes) ? implode("\n", $notes) : null;
    }

    /**
     * Normalize phone number for comparison
     */
    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Check for duplicates and return report
     */
    public function checkForDuplicates(array $bookingData): array
    {
        return $this->duplicateDetectionService->getUnifiedDuplicateReport([
            'email' => $bookingData['email'] ?? null,
            'phone' => $bookingData['phone'] ?? null,
            'first_name' => $this->splitFullName($bookingData['full_name'] ?? '')['first_name'],
            'last_name' => $this->splitFullName($bookingData['full_name'] ?? '')['last_name'],
        ]);
    }
}

