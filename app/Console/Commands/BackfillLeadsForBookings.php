<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Client;
use App\Models\CRM\Lead;
use App\Models\CRM\Source;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillLeadsForBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:backfill-bookings {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill leads for existing bookings that have clients but no leads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 Running in DRY RUN mode - no changes will be made');
            $this->newLine();
        }

        $this->info('Starting lead backfill process...');
        $this->newLine();

        // Get bookings that have client_id but no lead_id
        $bookingsWithoutLeads = Booking::whereNotNull('client_id')
            ->whereNull('lead_id')
            ->with('client.user')
            ->get();

        $this->info("Found {$bookingsWithoutLeads->count()} bookings without leads");
        $this->newLine();

        if ($bookingsWithoutLeads->isEmpty()) {
            $this->info('✅ No bookings need backfilling. All bookings are properly linked.');
            return Command::SUCCESS;
        }

        $progressBar = $this->output->createProgressBar($bookingsWithoutLeads->count());
        $progressBar->start();

        $stats = [
            'leads_created' => 0,
            'leads_found' => 0,
            'bookings_linked' => 0,
            'errors' => 0,
        ];

        foreach ($bookingsWithoutLeads as $booking) {
            try {
                $client = $booking->client;
                
                if (!$client) {
                    $stats['errors']++;
                    $progressBar->advance();
                    continue;
                }

                // Check if lead already exists for this client
                $existingLead = Lead::where('client_id', $client->id)
                    ->where('status', 'converted')
                    ->first();

                if (!$existingLead) {
                    // Check by email/phone
                    $email = $client->user?->email ?? $booking->email;
                    $phone = $client->phone ?? $booking->phone;

                    if ($email) {
                        $existingLead = Lead::where('email', $email)->first();
                    }

                    if (!$existingLead && $phone) {
                        $normalizedPhone = preg_replace('/[^0-9]/', '', $phone);
                        $existingLead = Lead::whereNotNull('phone')
                            ->get()
                            ->first(function($lead) use ($normalizedPhone) {
                                return preg_replace('/[^0-9]/', '', $lead->phone) === $normalizedPhone;
                            });
                    }
                }

                if ($existingLead) {
                    // Link booking to existing lead
                    if (!$dryRun) {
                        $booking->update(['lead_id' => $existingLead->id]);
                        
                        // Ensure lead is linked to client and marked as converted
                        if (!$existingLead->client_id) {
                            $existingLead->update([
                                'client_id' => $client->id,
                                'status' => 'converted',
                                'converted_at' => $existingLead->converted_at ?? now(),
                            ]);
                        }
                    }
                    $stats['leads_found']++;
                    $stats['bookings_linked']++;
                } else {
                    // Create new lead for this client
                    if (!$dryRun) {
                        $lead = $this->createLeadFromClient($client, $booking);
                        $booking->update(['lead_id' => $lead->id]);
                    }
                    $stats['leads_created']++;
                    $stats['bookings_linked']++;
                }

            } catch (\Exception $e) {
                $this->error("Error processing booking #{$booking->id}: " . $e->getMessage());
                $stats['errors']++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display summary
        $this->info('📊 Backfill Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Leads Created', $stats['leads_created']],
                ['Existing Leads Found', $stats['leads_found']],
                ['Bookings Linked', $stats['bookings_linked']],
                ['Errors', $stats['errors']],
            ]
        );

        if ($dryRun) {
            $this->newLine();
            $this->warn('⚠️  This was a DRY RUN. Run without --dry-run to apply changes.');
        } else {
            $this->newLine();
            $this->info('✅ Backfill completed successfully!');
        }

        return Command::SUCCESS;
    }

    /**
     * Create a lead from client data
     */
    protected function createLeadFromClient(Client $client, Booking $booking): Lead
    {
        // Get or create "Backfill" source
        $source = Source::firstOrCreate(
            ['name' => 'Data Migration'],
            ['description' => 'Leads created during data backfill process']
        );

        // Get default owner (first admin user)
        $owner = User::where('role', 'admin')->first();

        // Split contact person name
        $nameParts = $this->splitName($client->contact_person ?? 'Unknown Client');

        return Lead::create([
            'first_name' => $nameParts['first_name'],
            'last_name' => $nameParts['last_name'],
            'full_name' => $client->contact_person ?? 'Unknown Client',
            'email' => $client->user?->email ?? $booking->email,
            'phone' => $client->phone ?? $booking->phone,
            'company' => $client->company_name,
            'city' => $client->city ?? $booking->city,
            'address' => $client->address ?? ($booking->parish ?? $booking->city),
            'source_id' => $source->id,
            'owner_id' => $owner?->id,
            'status' => 'converted',
            'client_id' => $client->id,
            'converted_at' => $client->created_at ?? now(),
            'notes' => "Lead created during data backfill for existing client #{$client->id}",
        ]);
    }

    /**
     * Split full name into first and last name
     */
    protected function splitName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);
        
        return [
            'first_name' => $parts[0] ?? 'Unknown',
            'last_name' => $parts[1] ?? '',
        ];
    }
}
