<?php

namespace App\Console\Commands;

use App\Models\Maid;
use App\Models\Booking;
use App\Models\Package;
use App\Models\CrmLead;
use App\Services\BookingToLeadService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateOldDatabase extends Command
{
    protected $signature = 'migrate:old-database 
                            {--dry-run : Run without actually inserting data}
                            {--maids : Only migrate maids}
                            {--bookings : Only migrate bookings}
                            {--evaluations : Only migrate evaluations}';

    protected $description = 'Migrate data from old Royal Maids database to new v5.0 system';

    private $maidIdMap = [];
    private $stats = [
        'maids' => ['total' => 0, 'success' => 0, 'failed' => 0],
        'bookings' => ['total' => 0, 'success' => 0, 'failed' => 0],
        'evaluations' => ['total' => 0, 'success' => 0, 'failed' => 0],
    ];

    public function handle()
    {
        $this->info('🚀 Starting Old Database Migration...');
        $this->newLine();

        if ($this->option('dry-run')) {
            $this->warn('⚠️  DRY RUN MODE - No data will be inserted');
            $this->newLine();
        }

        $migrateAll = !$this->option('maids') && !$this->option('bookings') && !$this->option('evaluations');

        if ($migrateAll || $this->option('maids')) {
            $this->migrateMaids();
        }

        if ($migrateAll || $this->option('bookings')) {
            $this->migrateBookings();
        }

        if ($migrateAll || $this->option('evaluations')) {
            $this->migrateEvaluations();
        }

        $this->displaySummary();

        return Command::SUCCESS;
    }

    private function migrateMaids()
    {
        $this->info('📋 Migrating Maids...');

        $sqlFile = base_path('old database/maids (1).sql');
        
        if (!file_exists($sqlFile)) {
            $this->error("❌ File not found: {$sqlFile}");
            return;
        }

        $sql = file_get_contents($sqlFile);
        
        // Extract INSERT statements
        preg_match('/INSERT INTO `maids`.*?VALUES\s+(.*?);/s', $sql, $matches);
        
        if (!isset($matches[1])) {
            $this->error('❌ Could not parse maids SQL file');
            return;
        }

        // Parse individual maid records
        $records = $this->parseInsertValues($matches[1]);
        $this->stats['maids']['total'] = count($records);

        $bar = $this->output->createProgressBar(count($records));
        $bar->start();

        foreach ($records as $record) {
            try {
                $maidData = $this->mapMaidData($record);
                
                if (!$this->option('dry-run')) {
                    $maid = Maid::create($maidData);
                    $this->maidIdMap[$record[0]] = $maid->id; // Map old ID to new ID
                }
                
                $this->stats['maids']['success']++;
            } catch (\Exception $e) {
                $this->stats['maids']['failed']++;
                $this->newLine();
                $this->error("Failed to migrate maid: {$e->getMessage()}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function migrateBookings()
    {
        $this->info('📋 Migrating Bookings...');

        $sqlFile = base_path('old database/bookings.sql');
        
        if (!file_exists($sqlFile)) {
            $this->error("❌ File not found: {$sqlFile}");
            return;
        }

        $sql = file_get_contents($sqlFile);
        
        // Extract INSERT statements
        preg_match('/INSERT INTO `bookings`.*?VALUES\s+(.*?);/s', $sql, $matches);
        
        if (!isset($matches[1])) {
            $this->error('❌ Could not parse bookings SQL file');
            return;
        }

        $records = $this->parseInsertValues($matches[1]);
        $this->stats['bookings']['total'] = count($records);

        $bar = $this->output->createProgressBar(count($records));
        $bar->start();

        foreach ($records as $record) {
            try {
                $bookingData = $this->mapBookingData($record);
                
                if (!$this->option('dry-run')) {
                    // Create lead first using BookingToLeadService
                    $leadResult = BookingToLeadService::createOrFindLead([
                        'first_name' => $bookingData['first_name'],
                        'last_name' => $bookingData['last_name'],
                        'email' => $bookingData['email'],
                        'phone' => $bookingData['phone'],
                        'address' => $bookingData['address'],
                        'city' => $bookingData['city'],
                        'source' => 'old_system_migration'
                    ]);

                    // Create booking and link to lead
                    $bookingData['lead_id'] = $leadResult['lead']->id;
                    $bookingData['client_id'] = $leadResult['client']->id ?? null;
                    
                    Booking::create($bookingData);
                }
                
                $this->stats['bookings']['success']++;
            } catch (\Exception $e) {
                $this->stats['bookings']['failed']++;
                $this->newLine();
                $this->error("Failed to migrate booking: {$e->getMessage()}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function migrateEvaluations()
    {
        $this->info('📋 Migrating Evaluations...');

        $sqlFile = base_path('old database/maid_evaluations (1).sql');
        
        if (!file_exists($sqlFile)) {
            $this->error("❌ File not found: {$sqlFile}");
            return;
        }

        $sql = file_get_contents($sqlFile);
        
        // Extract INSERT statements
        preg_match('/INSERT INTO `maid_evaluations`.*?VALUES\s+(.*?);/s', $sql, $matches);
        
        if (!isset($matches[1])) {
            $this->error('❌ Could not parse evaluations SQL file');
            return;
        }

        $records = $this->parseInsertValues($matches[1]);
        $this->stats['evaluations']['total'] = count($records);

        $bar = $this->output->createProgressBar(count($records));
        $bar->start();

        foreach ($records as $record) {
            try {
                $evaluationData = $this->mapEvaluationData($record);
                
                if (!$this->option('dry-run') && $evaluationData) {
                    DB::table('evaluations')->insert($evaluationData);
                }
                
                $this->stats['evaluations']['success']++;
            } catch (\Exception $e) {
                $this->stats['evaluations']['failed']++;
                $this->newLine();
                $this->error("Failed to migrate evaluation: {$e->getMessage()}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function mapMaidData(array $record): array
    {
        // Field mapping based on old database structure
        // [0]=maid_id, [1]=maid_code, [2]=first_name, [3]=last_name, [4]=nationality, 
        // [5]=date_of_birth, [6]=passport_number, [7]=email, [8]=phone, [9]=address,
        // [10]=profile_image, [11]=status, etc.

        $status = $this->mapMaidStatus($record[11], $record[71] ?? null);

        return [
            'first_name' => $this->cleanValue($record[2]),
            'last_name' => $this->cleanValue($record[3]),
            'email' => $this->cleanValue($record[7]) ?: null,
            'phone' => $this->cleanValue($record[8]),
            'date_of_birth' => $this->cleanValue($record[5]) !== '0000-00-00' ? $this->cleanValue($record[5]) : null,
            'nationality' => $this->cleanValue($record[4]),
            'status' => $status,
            'profile_picture' => $this->cleanValue($record[10]),
            'hire_date' => $this->cleanValue($record[79]) !== '0000-00-00' ? $this->cleanValue($record[79]) : null,
            'documents' => json_encode([
                'maid_code' => $this->cleanValue($record[1]),
                'nin_number' => $this->cleanValue($record[55]),
                'passport_number' => $this->cleanValue($record[6]),
            ]),
            'created_at' => $this->cleanValue($record[51]),
            'updated_at' => $this->cleanValue($record[52]),
        ];
    }

    private function mapBookingData(array $record): array
    {
        // [0]=id, [1]=status, [2]=full_name, [3]=phone, [4]=email, [5]=country,
        // [6]=city, [7]=division, [8]=parish, etc.

        [$firstName, $lastName] = $this->splitFullName($this->cleanValue($record[2]));
        $packageId = $this->getPackageId($this->cleanValue($record[24])); // service_tier

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $this->cleanValue($record[3]),
            'email' => $this->cleanValue($record[4]),
            'address' => $this->cleanValue($record[8]), // parish
            'city' => $this->cleanValue($record[6]),
            'state' => $this->cleanValue($record[7]), // division
            'country' => $this->cleanValue($record[5]) ?: 'Uganda',
            'property_type' => $this->mapPropertyType($this->cleanValue($record[10])),
            'bedrooms' => (int)$this->cleanValue($record[11]),
            'bathrooms' => (int)$this->cleanValue($record[12]),
            'service_type' => 'one_time', // Default, adjust as needed
            'service_date' => now()->addDays(7), // Default future date
            'service_time' => '09:00:00',
            'status' => $this->mapBookingStatus($this->cleanValue($record[1])),
            'package_id' => $packageId,
            'special_instructions' => $this->cleanValue($record[33]),
            'created_at' => $this->cleanValue($record[34]),
            'updated_at' => $this->cleanValue($record[35]),
        ];
    }

    private function mapEvaluationData(array $record): ?array
    {
        // [0]=id, [1]=trainer_id, [2]=facilitator, [3]=status, [4]=observation_date,
        // [5]=trainee_name, [6]=confidence, [8]=self_awareness, [10]=emotional_stability, etc.

        $traineeName = $this->cleanValue($record[5]);
        $maid = Maid::where('first_name', 'LIKE', "%{$traineeName}%")
                    ->orWhere('last_name', 'LIKE', "%{$traineeName}%")
                    ->first();

        if (!$maid) {
            $this->warn("⚠️  Could not find maid for evaluation: {$traineeName}");
            return null;
        }

        // Convert 1-5 scale to 0-100 scale
        $punctuality = $this->scaleScore($this->cleanValue($record[14]));
        $respect = $this->scaleScore($this->cleanValue($record[16]));
        $confidence = $this->scaleScore($this->cleanValue($record[6]));

        return [
            'maid_id' => $maid->id,
            'trainer_id' => null, // Set manually if trainer exists
            'evaluation_date' => $this->cleanValue($record[4]),
            'punctuality' => $punctuality,
            'professionalism' => $respect,
            'quality_of_work' => $this->scaleScore($this->cleanValue($record[22])), // cleaning
            'communication' => $confidence,
            'reliability' => $this->scaleScore($this->cleanValue($record[18])), // ownership
            'initiative' => $this->scaleScore($this->cleanValue($record[12])), // growth_mindset
            'attention_to_detail' => $this->scaleScore($this->cleanValue($record[22])), // cleaning
            'time_management' => $punctuality,
            'notes' => "Migrated from old system. Facilitator: " . $this->cleanValue($record[2]),
            'created_at' => $this->cleanValue($record[30]),
        ];
    }

    private function mapMaidStatus(?string $status, ?string $secondaryStatus): string
    {
        // Use secondary_status if more specific
        $statusToMap = $secondaryStatus ?: $status;

        return match(strtolower($statusToMap)) {
            'available' => 'available',
            'in-training' => 'training',
            'booked' => 'available',
            'deployed' => 'deployed',
            'on-leave' => 'on_leave',
            'absconded', 'terminated' => 'terminated',
            default => 'available',
        };
    }

    private function mapBookingStatus(string $status): string
    {
        return match(strtolower($status)) {
            'approved' => 'confirmed',
            'rejected' => 'cancelled',
            'pending' => 'pending',
            default => 'pending',
        };
    }

    private function mapPropertyType(?string $homeType): string
    {
        return match(strtolower($homeType ?? '')) {
            'apartment' => 'apartment',
            'bungalow' => 'house',
            'maisonette' => 'villa',
            default => 'house',
        };
    }

    private function getPackageId(?string $serviceTier): ?int
    {
        if (!$serviceTier) return null;

        $package = Package::where('name', 'LIKE', "%{$serviceTier}%")->first();
        return $package?->id;
    }

    private function splitFullName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);
        return [
            $parts[0] ?? 'Unknown',
            $parts[1] ?? 'User'
        ];
    }

    private function scaleScore($score): int
    {
        if (!$score || $score == 0) return 0;
        return min(100, (int)$score * 20); // Convert 1-5 to 0-100
    }

    private function cleanValue($value): string
    {
        if ($value === 'NULL' || $value === null) return '';
        return trim($value, "'\"");
    }

    private function parseInsertValues(string $values): array
    {
        // Parse SQL INSERT VALUES into array of records
        $records = [];
        $pattern = '/\(([^)]+)\)/';
        preg_match_all($pattern, $values, $matches);

        foreach ($matches[1] as $match) {
            $fields = str_getcsv($match, ',', "'");
            $records[] = $fields;
        }

        return $records;
    }

    private function displaySummary()
    {
        $this->newLine();
        $this->info('📊 Migration Summary:');
        $this->newLine();

        $this->table(
            ['Entity', 'Total', 'Success', 'Failed'],
            [
                ['Maids', $this->stats['maids']['total'], $this->stats['maids']['success'], $this->stats['maids']['failed']],
                ['Bookings', $this->stats['bookings']['total'], $this->stats['bookings']['success'], $this->stats['bookings']['failed']],
                ['Evaluations', $this->stats['evaluations']['total'], $this->stats['evaluations']['success'], $this->stats['evaluations']['failed']],
            ]
        );

        $this->newLine();
        
        if ($this->option('dry-run')) {
            $this->warn('⚠️  This was a DRY RUN - no data was actually inserted');
        } else {
            $this->info('✅ Migration completed!');
        }
    }
}
