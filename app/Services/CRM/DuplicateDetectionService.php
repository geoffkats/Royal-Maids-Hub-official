<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DuplicateDetectionService
{
    /**
     * Find potential duplicate leads
     */
    public function findDuplicates(Lead $lead): Collection
    {
        $duplicates = collect();

        // Exact email match (highest priority)
        if ($lead->email) {
            $emailMatches = Lead::where('email', $lead->email)
                ->where('id', '!=', $lead->id ?? 0)
                ->get();
            
            foreach ($emailMatches as $match) {
                $duplicates->push([
                    'lead' => $match,
                    'score' => 100,
                    'reason' => 'Exact email match',
                    'match_type' => 'email',
                ]);
            }
        }

        // Exact phone match
        if ($lead->phone) {
            $normalizedPhone = $this->normalizePhone($lead->phone);
            
            $phoneMatches = Lead::whereNotNull('phone')
                ->where('id', '!=', $lead->id ?? 0)
                ->get()
                ->filter(function($existingLead) use ($normalizedPhone) {
                    return $this->normalizePhone($existingLead->phone) === $normalizedPhone;
                });
            
            foreach ($phoneMatches as $match) {
                if (!$duplicates->contains(fn($d) => $d['lead']->id === $match->id)) {
                    $duplicates->push([
                        'lead' => $match,
                        'score' => 95,
                        'reason' => 'Exact phone match',
                        'match_type' => 'phone',
                    ]);
                }
            }
        }

        // Name + Company match (fuzzy)
        if ($lead->first_name && $lead->last_name && $lead->company) {
            $nameCompanyMatches = Lead::where('company', 'LIKE', '%' . $lead->company . '%')
                ->where('id', '!=', $lead->id ?? 0)
                ->get()
                ->filter(function($existingLead) use ($lead) {
                    $similarity = $this->nameSimilarity($lead, $existingLead);
                    return $similarity > 0.8; // 80% similar
                });
            
            foreach ($nameCompanyMatches as $match) {
                if (!$duplicates->contains(fn($d) => $d['lead']->id === $match->id)) {
                    $similarity = $this->nameSimilarity($lead, $match);
                    $duplicates->push([
                        'lead' => $match,
                        'score' => (int)($similarity * 100),
                        'reason' => 'Similar name and company',
                        'match_type' => 'name_company',
                    ]);
                }
            }
        }

        // Name only match (very fuzzy)
        if ($lead->first_name && $lead->last_name) {
            $nameMatches = Lead::where('id', '!=', $lead->id ?? 0)
                ->get()
                ->filter(function($existingLead) use ($lead) {
                    $similarity = $this->nameSimilarity($lead, $existingLead);
                    return $similarity > 0.9; // 90% similar for name-only
                });
            
            foreach ($nameMatches as $match) {
                if (!$duplicates->contains(fn($d) => $d['lead']->id === $match->id)) {
                    $similarity = $this->nameSimilarity($lead, $match);
                    $duplicates->push([
                        'lead' => $match,
                        'score' => (int)($similarity * 80), // Lower score for name-only
                        'reason' => 'Very similar name',
                        'match_type' => 'name',
                    ]);
                }
            }
        }

        // Sort by score descending
        return $duplicates->sortByDesc('score')->values();
    }

    /**
     * Check if lead has high-confidence duplicates
     */
    public function hasHighConfidenceDuplicates(Lead $lead, int $threshold = 90): bool
    {
        $duplicates = $this->findDuplicates($lead);
        return $duplicates->where('score', '>=', $threshold)->isNotEmpty();
    }

    /**
     * Get duplicate summary for UI display
     */
    public function getDuplicateSummary(Lead $lead): array
    {
        $duplicates = $this->findDuplicates($lead);

        return [
            'has_duplicates' => $duplicates->isNotEmpty(),
            'total_count' => $duplicates->count(),
            'high_confidence_count' => $duplicates->where('score', '>=', 90)->count(),
            'medium_confidence_count' => $duplicates->whereBetween('score', [70, 89])->count(),
            'low_confidence_count' => $duplicates->where('score', '<', 70)->count(),
            'duplicates' => $duplicates->take(5)->toArray(), // Top 5
        ];
    }

    /**
     * Normalize phone number for comparison
     */
    protected function normalizePhone(string $phone): string
    {
        // Remove all non-numeric characters
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Calculate name similarity between two leads
     */
    protected function nameSimilarity(Lead $lead1, Lead $lead2): float
    {
        $name1 = strtolower(trim($lead1->first_name . ' ' . $lead1->last_name));
        $name2 = strtolower(trim($lead2->first_name . ' ' . $lead2->last_name));

        // Levenshtein similarity
        $maxLen = max(strlen($name1), strlen($name2));
        if ($maxLen === 0) return 0;

        $distance = levenshtein($name1, $name2);
        return 1 - ($distance / $maxLen);
    }

    /**
     * Find leads that might be the same person using multiple fields
     */
    public function findPotentialMatches(array $attributes): Collection
    {
        $leads = collect();

        // Email match
        if (!empty($attributes['email'])) {
            $emailLeads = Lead::where('email', $attributes['email'])->get();
            $leads = $leads->merge($emailLeads);
        }

        // Phone match
        if (!empty($attributes['phone'])) {
            $normalizedPhone = $this->normalizePhone($attributes['phone']);
            $phoneLeads = Lead::whereNotNull('phone')
                ->get()
                ->filter(function($lead) use ($normalizedPhone) {
                    return $this->normalizePhone($lead->phone) === $normalizedPhone;
                });
            $leads = $leads->merge($phoneLeads);
        }

        // Name + Company match
        if (!empty($attributes['first_name']) && !empty($attributes['last_name']) && !empty($attributes['company'])) {
            $nameCompanyLeads = Lead::where('first_name', 'LIKE', '%' . $attributes['first_name'] . '%')
                ->where('last_name', 'LIKE', '%' . $attributes['last_name'] . '%')
                ->where('company', 'LIKE', '%' . $attributes['company'] . '%')
                ->get();
            $leads = $leads->merge($nameCompanyLeads);
        }

        return $leads->unique('id');
    }

    /**
     * Validate if two leads should be considered duplicates
     */
    public function areDuplicates(Lead $lead1, Lead $lead2): bool
    {
        // Exact email match
        if ($lead1->email && $lead2->email && $lead1->email === $lead2->email) {
            return true;
        }

        // Exact phone match
        if ($lead1->phone && $lead2->phone) {
            if ($this->normalizePhone($lead1->phone) === $this->normalizePhone($lead2->phone)) {
                return true;
            }
        }

        // Name similarity + company match
        if ($lead1->company && $lead2->company) {
            if (stripos($lead1->company, $lead2->company) !== false || 
                stripos($lead2->company, $lead1->company) !== false) {
                if ($this->nameSimilarity($lead1, $lead2) > 0.85) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Find duplicate clients by email or phone
     */
    public function findDuplicateClients(array $attributes): Collection
    {
        $clients = collect();

        // Email match
        if (!empty($attributes['email'])) {
            // Check clients table directly (email might be in users table)
            $emailClients = Client::with('user')
                ->whereHas('user', function($query) use ($attributes) {
                    $query->where('email', $attributes['email']);
                })->get();
            $clients = $clients->merge($emailClients);
        }

        // Phone match
        if (!empty($attributes['phone'])) {
            $normalizedPhone = $this->normalizePhone($attributes['phone']);
            $phoneClients = Client::with('user')
                ->whereNotNull('phone')
                ->get()
                ->filter(function($client) use ($normalizedPhone) {
                    return $this->normalizePhone($client->phone) === $normalizedPhone;
                });
            $clients = $clients->merge($phoneClients);
        }

        return $clients->unique('id');
    }

    /**
     * Check if attributes match an existing client
     */
    public function checkForClientDuplicates(array $attributes): bool
    {
        $clients = $this->findDuplicateClients($attributes);
        return $clients->isNotEmpty();
    }

    /**
     * Find existing client by email or phone
     */
    public function findExistingClient(array $attributes): ?Client
    {
        $clients = $this->findDuplicateClients($attributes);
        return $clients->first();
    }

    /**
     * Get unified duplicate report for both leads and clients
     */
    public function getUnifiedDuplicateReport(array $attributes): array
    {
        $leads = $this->findPotentialMatches($attributes);
        $clients = $this->findDuplicateClients($attributes);

        return [
            'has_duplicates' => $leads->isNotEmpty() || $clients->isNotEmpty(),
            'leads' => $leads->map(function($lead) {
                return [
                    'type' => 'lead',
                    'id' => $lead->id,
                    'name' => $lead->full_name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'status' => $lead->status,
                ];
            })->toArray(),
            'clients' => $clients->map(function($client) {
                return [
                    'type' => 'client',
                    'id' => $client->id,
                    'name' => $client->contact_person,
                    'email' => $client->user->email ?? null,
                    'phone' => $client->phone,
                    'subscription_status' => $client->subscription_status,
                ];
            })->toArray(),
            'total_count' => $leads->count() + $clients->count(),
        ];
    }
}
