<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Client;
use App\Models\Maid;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for ticket creation
        $adminUsers = User::where('role', 'admin')->get();
        $trainerUsers = User::where('role', 'trainer')->get();
        
        // Get clients with their packages
        $clients = Client::with('activeBooking.package')->get();
        $platinumClients = $clients->filter(fn($c) => $c->activeBooking?->package?->tier === 'platinum');
        $goldClients = $clients->filter(fn($c) => $c->activeBooking?->package?->tier === 'gold');
        $silverClients = $clients->filter(fn($c) => $c->activeBooking?->package?->tier === 'silver');
        
        // Get maids and bookings
        $maids = Maid::all();
        $bookings = Booking::with('package')->get();
        
        // Ensure we have admin user
        $admin = $adminUsers->first() ?? User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        
        $ticketsData = [];
        
        // ============================================
        // PLATINUM CLIENT TICKETS (Auto-Boosted)
        // ============================================
        
        if ($platinumClients->isNotEmpty()) {
            $platinumClient = $platinumClients->first();
            $platinumBooking = $platinumClient->activeBooking;
            
            // 1. Critical Issue - Safety Concern (Auto-boosted to Critical)
            $ticketsData[] = [
                'type' => 'client_issue',
                'category' => 'Safety Concern',
                'priority' => 'urgent', // Will be auto-boosted to critical
                'subject' => 'Maid injured at work - emergency assistance needed',
                'description' => 'Our maid had an accident while cleaning. She fell from a ladder and may have injured her ankle. Need immediate assistance.',
                'requester_id' => $platinumClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $platinumClient->id,
                'maid_id' => $maids->random()->id ?? null,
                'booking_id' => $platinumBooking?->id,
                'package_id' => $platinumBooking?->package_id,
                'status' => 'in_progress',
            ];
            
            // 2. High Priority - Maid Absence (Platinum boost: high → urgent)
            $ticketsData[] = [
                'type' => 'deployment_issue',
                'category' => 'Maid Absence',
                'priority' => 'high', // Will be boosted to urgent
                'subject' => 'Scheduled maid did not arrive this morning',
                'description' => 'The maid was scheduled to arrive at 8 AM today but has not shown up. No communication received. We need immediate replacement as we have guests arriving.',
                'requester_id' => $platinumClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $platinumClient->id,
                'booking_id' => $platinumBooking?->id,
                'package_id' => $platinumBooking?->package_id,
                'status' => 'open',
            ];
            
            // 3. Medium Priority - Billing Issue (Platinum boost: medium → high)
            $ticketsData[] = [
                'type' => 'billing',
                'category' => 'Incorrect Charge',
                'priority' => 'medium', // Will be boosted to high
                'subject' => 'Charged for extra hours not requested',
                'description' => 'Last invoice shows 45 hours but we only requested 40 hours of service. Please review and correct.',
                'requester_id' => $platinumClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $platinumClient->id,
                'booking_id' => $platinumBooking?->id,
                'package_id' => $platinumBooking?->package_id,
                'status' => 'resolved',
                'resolved_at' => now()->subDays(2),
            ];
            
            // 4. Admin creates ticket ON BEHALF of Platinum client (phone call)
            $ticketsData[] = [
                'type' => 'client_issue',
                'category' => 'Service Quality',
                'priority' => 'medium', // Will be boosted to high
                'subject' => 'Request for maid replacement - personality conflict',
                'description' => 'Client called to report that current maid is not a good fit. Requests replacement with someone more experienced. Client prefers someone who has worked with elderly care.',
                'requester_id' => $admin->id,
                'requester_type' => 'admin',
                'created_on_behalf_of' => $platinumClient->id,
                'created_on_behalf_type' => 'client',
                'client_id' => $platinumClient->id,
                'maid_id' => $maids->random()->id ?? null,
                'booking_id' => $platinumBooking?->id,
                'package_id' => $platinumBooking?->package_id,
                'status' => 'pending',
            ];
        }
        
        // ============================================
        // GOLD CLIENT TICKETS (Standard Priority)
        // ============================================
        
        if ($goldClients->isNotEmpty()) {
            $goldClient = $goldClients->first();
            $goldBooking = $goldClient->activeBooking;
            
            // 5. High Priority - Schedule Change
            $ticketsData[] = [
                'type' => 'client_issue',
                'category' => 'Schedule Change',
                'priority' => 'high', // Stays high
                'subject' => 'Need to change maid schedule for next week',
                'description' => 'We are traveling next week and need to reschedule the maid service to the following week. Please confirm availability.',
                'requester_id' => $goldClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $goldClient->id,
                'booking_id' => $goldBooking?->id,
                'package_id' => $goldBooking?->package_id,
                'status' => 'open',
            ];
            
            // 6. Medium Priority - Service Request
            $ticketsData[] = [
                'type' => 'client_issue',
                'category' => 'Service Request',
                'priority' => 'medium', // Stays medium
                'subject' => 'Add deep cleaning to monthly service',
                'description' => 'Would like to add deep cleaning service to our existing package. Please provide pricing and availability.',
                'requester_id' => $goldClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $goldClient->id,
                'booking_id' => $goldBooking?->id,
                'package_id' => $goldBooking?->package_id,
                'status' => 'closed',
                'resolved_at' => now()->subDays(5),
                'closed_at' => now()->subDays(4),
            ];
        }
        
        // ============================================
        // SILVER CLIENT TICKETS (Capped Priority)
        // ============================================
        
        if ($silverClients->isNotEmpty()) {
            $silverClient = $silverClients->first();
            $silverBooking = $silverClient->activeBooking;
            
            // 7. Urgent Priority - Capped at High for Silver
            $ticketsData[] = [
                'type' => 'client_issue',
                'category' => 'Service Quality',
                'priority' => 'urgent', // Will be capped at high
                'subject' => 'Maid missed several areas during cleaning',
                'description' => 'Last cleaning session was not thorough. Several areas were missed including bathrooms and kitchen. Please address.',
                'requester_id' => $silverClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $silverClient->id,
                'booking_id' => $silverBooking?->id,
                'package_id' => $silverBooking?->package_id,
                'status' => 'open',
            ];
            
            // 8. Low Priority - General Inquiry
            $ticketsData[] = [
                'type' => 'general',
                'category' => 'General Inquiry',
                'priority' => 'low', // Stays low
                'subject' => 'Question about contract renewal',
                'description' => 'My contract expires next month. What are the renewal options and pricing?',
                'requester_id' => $silverClient->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $silverClient->id,
                'booking_id' => $silverBooking?->id,
                'package_id' => $silverBooking?->package_id,
                'status' => 'resolved',
                'resolved_at' => now()->subHours(12),
            ];
        }
        
        // ============================================
        // MAID SUPPORT TICKETS
        // ============================================
        
        if ($maids->isNotEmpty()) {
            $maid = $maids->first();
            
            // 9. Admin creates ticket ON BEHALF of Maid (phone call)
            $ticketsData[] = [
                'type' => 'maid_support',
                'category' => 'Payment Issue',
                'priority' => 'high',
                'subject' => 'Maid reports late salary payment',
                'description' => 'Maid called to report that salary for last month has not been received. Expected payment date was 3 days ago. Please investigate urgently.',
                'requester_id' => $admin->id,
                'requester_type' => 'admin',
                'created_on_behalf_of' => $maid->id,
                'created_on_behalf_type' => 'maid',
                'maid_id' => $maid->id,
                'status' => 'in_progress',
            ];
            
            // 10. Maid creates ticket - Workplace Issue
            $ticketsData[] = [
                'type' => 'maid_support',
                'category' => 'Workplace Issue',
                'priority' => 'medium',
                'subject' => 'Need new cleaning supplies',
                'description' => 'Current vacuum cleaner is broken and mop is worn out. Need replacement equipment to maintain service quality.',
                'requester_id' => $maid->user_id ?? $admin->id,
                'requester_type' => 'maid',
                'maid_id' => $maid->id,
                'status' => 'open',
            ];
        }
        
        // ============================================
        // TRAINING & OPERATIONS TICKETS
        // ============================================
        
        // 11. Trainer creates ticket - Training Issue
        if ($trainerUsers->isNotEmpty()) {
            $trainer = $trainerUsers->first();
            $ticketsData[] = [
                'type' => 'training',
                'category' => 'Equipment Issue',
                'priority' => 'medium',
                'subject' => 'Training room projector not working',
                'description' => 'The projector in Training Room B is not turning on. Have important training session tomorrow morning. Need urgent repair or replacement.',
                'requester_id' => $trainer->id,
                'requester_type' => 'trainer',
                'trainer_id' => $trainer->id,
                'status' => 'open',
            ];
        }
        
        // 12. Operations ticket - Admin creates
        $ticketsData[] = [
            'type' => 'operations',
            'category' => 'Administrative',
            'priority' => 'low',
            'subject' => 'Update company contact information',
            'description' => 'Need to update company phone number and email address on website and all documentation. New phone: +123-456-7890',
            'requester_id' => $admin->id,
            'requester_type' => 'admin',
            'status' => 'open',
        ];
        
        // ============================================
        // ADDITIONAL REALISTIC SCENARIOS
        // ============================================
        
        // 13. Client reports maid issue with maid linked
        if ($clients->isNotEmpty() && $maids->isNotEmpty()) {
            $client = $clients->random();
            $maid = $maids->random();
            $booking = $client->activeBooking;
            
            $ticketsData[] = [
                'type' => 'client_issue',
                'category' => 'Maid Performance',
                'priority' => 'medium',
                'subject' => 'Maid arriving late consistently',
                'description' => 'For the past 3 weeks, the maid has been arriving 30-45 minutes late. This is causing inconvenience as I need to adjust my schedule.',
                'requester_id' => $client->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $client->id,
                'maid_id' => $maid->id,
                'booking_id' => $booking?->id,
                'package_id' => $booking?->package_id,
                'status' => 'pending',
            ];
        }
        
        // 14. Billing dispute - resolved
        if ($clients->isNotEmpty()) {
            $client = $clients->random();
            $booking = $client->activeBooking;
            
            $ticketsData[] = [
                'type' => 'billing',
                'category' => 'Invoice Dispute',
                'priority' => 'high',
                'subject' => 'Duplicate charge on credit card',
                'description' => 'I was charged twice for the same service period. Transaction IDs: TXN123456 and TXN123457. Please refund the duplicate charge.',
                'requester_id' => $client->user_id ?? $admin->id,
                'requester_type' => 'client',
                'client_id' => $client->id,
                'booking_id' => $booking?->id,
                'package_id' => $booking?->package_id,
                'status' => 'closed',
                'resolved_at' => now()->subDays(7),
                'closed_at' => now()->subDays(6),
            ];
        }
        
        // 15. Deployment issue - early termination
        if ($bookings->isNotEmpty() && $maids->isNotEmpty()) {
            $booking = $bookings->random();
            $maid = $maids->random();
            
            $ticketsData[] = [
                'type' => 'deployment_issue',
                'category' => 'Early Termination',
                'priority' => 'urgent',
                'subject' => 'Client requesting immediate maid removal',
                'description' => 'Client has requested immediate termination of service. Cited unprofessional behavior. Need to arrange pickup and replacement ASAP.',
                'requester_id' => $admin->id,
                'requester_type' => 'admin',
                'client_id' => $booking->client_id,
                'maid_id' => $maid->id,
                'booking_id' => $booking->id,
                'package_id' => $booking->package_id,
                'status' => 'in_progress',
            ];
        }
        
        // Create all tickets
        foreach ($ticketsData as $ticketData) {
            // Let the model handle ticket_number, auto_priority, tier_based_priority, and SLA calculations
            Ticket::create($ticketData);
        }
        
        $this->command->info('✅ Ticket seeder completed successfully!');
        $this->command->info('📊 Created ' . count($ticketsData) . ' tickets with various scenarios:');
        $this->command->info('   - Platinum client tickets (auto-boosted priority)');
        $this->command->info('   - Gold client tickets (standard priority)');
        $this->command->info('   - Silver client tickets (capped priority)');
        $this->command->info('   - On-behalf creation scenarios');
        $this->command->info('   - Maid support tickets');
        $this->command->info('   - Training & operations tickets');
        $this->command->info('   - Various statuses (open, pending, in_progress, resolved, closed)');
    }
}
