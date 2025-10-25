<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSlaRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // Platinum
            ['rule_name' => 'Platinum Low', 'package_tier' => 'platinum', 'priority' => 'low', 'response_time_minutes' => 30, 'resolution_time_minutes' => 240, 'auto_boost_priority' => true, 'boosted_priority' => 'medium'],
            ['rule_name' => 'Platinum Medium', 'package_tier' => 'platinum', 'priority' => 'medium', 'response_time_minutes' => 15, 'resolution_time_minutes' => 120, 'auto_boost_priority' => true, 'boosted_priority' => 'high'],
            ['rule_name' => 'Platinum High', 'package_tier' => 'platinum', 'priority' => 'high', 'response_time_minutes' => 10, 'resolution_time_minutes' => 60, 'auto_boost_priority' => true, 'boosted_priority' => 'urgent'],
            ['rule_name' => 'Platinum Urgent', 'package_tier' => 'platinum', 'priority' => 'urgent', 'response_time_minutes' => 5, 'resolution_time_minutes' => 30, 'auto_boost_priority' => true, 'boosted_priority' => 'critical'],
            ['rule_name' => 'Platinum Critical', 'package_tier' => 'platinum', 'priority' => 'critical', 'response_time_minutes' => 5, 'resolution_time_minutes' => 30, 'auto_boost_priority' => false, 'boosted_priority' => null],
            // Gold
            ['rule_name' => 'Gold Low', 'package_tier' => 'gold', 'priority' => 'low', 'response_time_minutes' => 60, 'resolution_time_minutes' => 480, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Gold Medium', 'package_tier' => 'gold', 'priority' => 'medium', 'response_time_minutes' => 30, 'resolution_time_minutes' => 240, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Gold High', 'package_tier' => 'gold', 'priority' => 'high', 'response_time_minutes' => 15, 'resolution_time_minutes' => 120, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Gold Urgent', 'package_tier' => 'gold', 'priority' => 'urgent', 'response_time_minutes' => 15, 'resolution_time_minutes' => 120, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Gold Critical', 'package_tier' => 'gold', 'priority' => 'critical', 'response_time_minutes' => 10, 'resolution_time_minutes' => 60, 'auto_boost_priority' => false, 'boosted_priority' => null],
            // Silver
            ['rule_name' => 'Silver Low', 'package_tier' => 'silver', 'priority' => 'low', 'response_time_minutes' => 120, 'resolution_time_minutes' => 1440, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Silver Medium', 'package_tier' => 'silver', 'priority' => 'medium', 'response_time_minutes' => 60, 'resolution_time_minutes' => 480, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Silver High', 'package_tier' => 'silver', 'priority' => 'high', 'response_time_minutes' => 30, 'resolution_time_minutes' => 240, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Silver Urgent', 'package_tier' => 'silver', 'priority' => 'urgent', 'response_time_minutes' => 30, 'resolution_time_minutes' => 240, 'auto_boost_priority' => false, 'boosted_priority' => null],
            ['rule_name' => 'Silver Critical', 'package_tier' => 'silver', 'priority' => 'critical', 'response_time_minutes' => 15, 'resolution_time_minutes' => 120, 'auto_boost_priority' => false, 'boosted_priority' => null],
        ];
        DB::table('ticket_sla_rules')->insert($rules);
    }
}
