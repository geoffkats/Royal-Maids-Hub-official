<?php

namespace Database\Seeders;

use App\Models\Maid;
use App\Models\Booking;
use App\Models\Package;
use App\Models\CrmLead;
use App\Services\BookingToLeadService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportOldDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting Old Data Import from SQL files...');
        $this->command->newLine();

        $this->importMaids();
        $this->importBookings();
        $this->importEvaluations();
        
        $this->command->newLine();
        $this->command->info('✅ Import completed!');
    }

    private function importMaids()
    {
        $this->command->info('📋 Importing Maids from SQL file...');

        // Manually define the maids from the SQL file
        $maidsData = [
            ['M005', 'Semmy', 'Adong', 'Uganda, Langi', '2003-06-23', 'Simmie.adong@rmh.com', '0787596159', 'uploads/maid_40_1755530471.jpeg', 'deployed', 'deployed', '2025-08-11'],
            ['M006', 'Robina', 'Akao', 'Lira, Ugandan', '2002-06-03', 'Akao.Robina@rmh.com', '0780962044', 'uploads/maid_41_1755530447.jpeg', '', 'terminated', '0000-00-00'],
            ['M007', 'Pauline', 'Awori Mary', 'Japadola, Ugandan', '1994-05-15', 'Awori.Mary@rmh.com', '0777764586', 'uploads/maid_42_1755530419.jpeg', 'deployed', '', '2025-08-04'],
            ['M008', 'Harriet', 'Asemo', 'Ugandan', '1996-02-23', 'Harriet.Asemo@rmh.com', '0773301995', 'uploads/harriet.jpeg', 'deployed', 'absconded', '0000-00-00'],
            ['M009', 'Concy', 'Ajok', 'Acholi, Uganda', '1997-09-04', 'concy.ajok@rmh.com', '0762797092', 'uploads/concy.jpeg', '', 'absconded', '2025-08-11'],
            ['M010', 'Flavia', 'Ajalo', 'Langi, Uganda', '1998-03-12', 'flavia.ajalo@rmh.com', '0771005433', 'uploads/flavia.jpeg', '', 'absconded', '2025-07-01'],
            ['M011', 'Innocent', 'Lalam', 'Langi, Uganda', '2005-05-18', 'innocent.lalam@rmh.com', '0765054837', 'uploads/innocent.jpeg', '', 'absconded', '0000-00-00'],
            ['M012', 'Sandra', 'Ayot', 'Uganda', '2002-09-10', 'sandra.ayot@rmh.com', '0783204328', 'uploads/sandraayot1.jpeg', 'deployed', 'deployed', '2025-08-20'],
            ['M013', 'Daphine', 'Asero', 'Uganda', '2003-02-02', 'asero@rmh.com', '0768566849', '', 'deployed', 'deployed', '0000-00-00'],
            ['M015', 'Mary', 'Awori', 'Uganda', '2003-06-08', null, '0760907910', 'uploads/mary awori1.jpeg', 'deployed', 'deployed', '2025-09-22'],
            ['M016', 'Fiona', 'Ocwee', 'Ugandan', '2003-04-18', null, '0771022125', 'uploads/fiona ocwee.jpg', 'in-training', 'available', '2025-10-02'],
            ['M017', 'Josephine', 'Nanyonga', 'Ugandan', '1986-10-11', null, '0742145824', 'uploads/josephine nanyonga.jpeg', '', 'absconded', '2025-10-06'],
            ['M018', 'Juliet', 'Athieno', 'Ugandan', '1994-12-17', null, '0702825092', 'uploads/Juliet.jpeg', 'in-training', 'available', '2025-10-11'],
            ['M019', 'Vicky', 'Akello', 'Uganda', '1994-12-30', null, '0768457028', 'uploads/akello vicky.jpeg', 'booked', 'deployed', '2025-10-13'],
            ['M020', 'Olivier', 'Nangobya', 'Uganda', '2000-02-14', null, '0709793091', 'uploads/olivier Nangobya.jpeg', 'in-training', 'booked', '2025-10-18'],
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($maidsData));
        $bar->start();

        foreach ($maidsData as $data) {
            try {
                $status = $this->mapMaidStatus($data[8], $data[9]);
                
                Maid::create([
                    'first_name' => $data[1],
                    'last_name' => $data[2],
                    'email' => $data[5] ?: null,
                    'phone' => $data[6],
                    'date_of_birth' => $data[4] !== '0000-00-00' ? $data[4] : null,
                    'nationality' => $data[3],
                    'status' => $status,
                    'profile_picture' => $data[7],
                    'hire_date' => $data[10] !== '0000-00-00' ? $data[10] : null,
                    'documents' => json_encode(['maid_code' => $data[0]]),
                ]);
            } catch (\Exception $e) {
                $this->command->error("Failed: {$data[1]} {$data[2]} - {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('✓ Maids imported: ' . Maid::count());
        $this->command->newLine();
    }

    private function importBookings()
    {
        $this->command->info('📋 Importing Bookings from SQL file...');

        // Manually define key bookings from the SQL file
        $bookingsData = [
            ['kato Geoffrey', '0751801685', 'katogeoffreyg@gmail.com', 'approved', 'Silver', 'Bungalow', 2, 2, 'Kampala', 'gre'],
            ['Lilian Arinda', '+256778286164', 'arindalilian22@gmail.com', 'rejected', 'Platinum', 'Apartment', 2, 1, 'Kampala', 'Nakawa'],
            ['Rehema Nakirya Ssemyalo', '0772506802', 'rehema.nakirya@gmail.com', 'pending', 'Gold', 'Maisonette', 5, 4, 'Kampala', 'Kira'],
            ['Karen Hasahya', '0762936536', 'karen.hasahya@gmail.com', 'rejected', 'Silver', 'Apartment', 3, 2, 'Kampala', 'Nakawa'],
            ['Victoria', '0777800646', 'akankundavickie6@gmail.com', 'pending', 'Silver', 'Apartment', 2, 2, 'Kampala', 'Nakawa'],
            ['Naume Musiimenta', '0785198235', 'odalinconsult2023@gmail.com', 'pending', 'Silver', '', 0, 0, 'Kampala', 'Kiira'],
            ['Victoria', '0777800646', 'akankundavickie6@gmail.com', 'rejected', 'Silver', 'Apartment', 2, 2, 'Kampala', 'Nakawa'],
            ['Victoria', '0777800646', 'akankundavickie6@gmail.com', 'approved', 'Silver', 'Apartment', 2, 2, 'Kampala', 'Nakawa'],
            ['Ellah Luwaga Kagumire', '0752720993', 'luwagaellah@gmail.com', 'rejected', 'Silver', '', 0, 0, 'Kampala', 'Kungu'],
            ['MUKASHYAKA IRENE', '0759402530', 'mukashaka12@gmail.com', 'rejected', 'Silver', 'Bungalow', 2, 2, 'Kampala', 'Complex Hall'],
            ['Loretta Tukahirwa', '778040140', 'loretta.tukahirwa@icloud.com', 'pending', 'Silver', 'Apartment', 3, 4, 'Kampala', 'Kungu'],
            ['Rhoda Nalugya', '0773630541', 'rhodahaleynalugya@gmail.com', 'pending', 'Gold', 'Apartment', 2, 2, 'Kampala', 'Nakawa'],
            ['Rhoda Nalugya', '0773630541', 'rhodahaleynalugya@gmail.com', 'pending', 'Gold', 'Apartment', 2, 2, 'Kampala', 'Nakawa'],
            ['Shirley Nafula', '0787490734', 'nafulasharon193@gmail.com', 'pending', 'Platinum', 'Maisonette', 6, 6, 'KAMPALA', 'Kira'],
            ['Josephine Nalugo', '0773375900', 'joseynals@gmail.com', 'approved', 'Silver', 'Bungalow', 3, 2, 'Kampala', 'Kiira'],
            ['Precious Turinawe', '0776016303', 'preciousturi@gmail.com', 'approved', 'Silver', 'Bungalow', 4, 4, 'Entebbe', 'A'],
            ['Immaculate Mukuye', '0755555581', 'immacnkm@gmail.com', 'pending', 'Silver', 'Bungalow', 0, 2, 'Kampala', 'Rubaga'],
            ['Immaculate Mukuye', '0755555581', 'immacnkm@gmail.com', 'pending', 'Silver', 'Bungalow', 0, 2, 'Kampala', 'Rubaga'],
            ['Josephine Nalugo', '0773375900', 'joseynals@gmail.com', 'pending', 'Silver', 'Apartment', 3, 2, 'Kampala', 'Kiira'],
            ['JUSTINE NAMULI', '0772700862', 'nmljustine1@gmail.com', 'approved', 'Silver', 'Bungalow', 3, 3, 'MPIGI', 'MAWOKOTA'],
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($bookingsData));
        $bar->start();

        foreach ($bookingsData as $data) {
            try {
                [$firstName, $lastName] = $this->splitFullName($data[0]);
                $packageId = $this->getPackageId($data[4]);

                // Create lead first
                $leadResult = BookingToLeadService::createOrFindLead([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $data[2],
                    'phone' => $data[1],
                    'address' => $data[9],
                    'city' => $data[8],
                    'source' => 'old_system_migration'
                ]);

                // Create booking
                Booking::create([
                    'lead_id' => $leadResult['lead']->id,
                    'client_id' => $leadResult['client']->id ?? null,
                    'service_type' => 'one_time',
                    'service_date' => now()->addDays(7),
                    'service_time' => '09:00:00',
                    'duration' => 2,
                    'status' => $this->mapBookingStatus($data[3]),
                    'property_type' => $this->mapPropertyType($data[5]),
                    'bedrooms' => $data[6],
                    'bathrooms' => $data[7],
                    'package_id' => $packageId,
                ]);
            } catch (\Exception $e) {
                $this->command->error("Failed: {$data[0]} - {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('✓ Bookings imported: ' . Booking::count());
        $this->command->info('✓ Leads created: ' . CrmLead::where('source', 'old_system_migration')->count());
        $this->command->newLine();
    }

    private function importEvaluations()
    {
        $this->command->info('📋 Importing Evaluations...');
        $this->command->warn('⚠️  Skipping evaluations - will need maid name matching');
        $this->command->info('✓ You can manually create evaluations later if needed');
        $this->command->newLine();
    }

    private function mapMaidStatus(?string $status, ?string $secondaryStatus): string
    {
        $statusToMap = $secondaryStatus ?: $status;

        return match(strtolower($statusToMap ?? 'available')) {
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
}
