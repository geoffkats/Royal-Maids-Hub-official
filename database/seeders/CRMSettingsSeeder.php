<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CRMSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🔧 Starting CRM Settings seeding...\n";

        $settings = [
            // General Settings
            ['key' => 'crm_name', 'value' => 'Royal Maids CRM', 'description' => 'Name of the CRM system', 'type' => 'string'],
            ['key' => 'default_currency', 'value' => 'UGX', 'description' => 'Default currency for the CRM', 'type' => 'string'],
            ['key' => 'timezone', 'value' => 'Africa/Kampala', 'description' => 'Default timezone', 'type' => 'string'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'description' => 'Date format', 'type' => 'string'],
            ['key' => 'time_format', 'value' => 'H:i', 'description' => 'Time format', 'type' => 'string'],
            
            // Lead Settings
            ['key' => 'auto_assign_leads', 'value' => '0', 'description' => 'Auto-assign leads to available users', 'type' => 'boolean'],
            ['key' => 'lead_scoring_enabled', 'value' => '1', 'description' => 'Enable lead scoring system', 'type' => 'boolean'],
            ['key' => 'lead_duplicate_check', 'value' => '1', 'description' => 'Check for duplicate leads', 'type' => 'boolean'],
            ['key' => 'lead_auto_qualify_score', 'value' => '80', 'description' => 'Auto-qualify score threshold', 'type' => 'integer'],
            ['key' => 'lead_follow_up_days', 'value' => '7', 'description' => 'Follow-up reminder days', 'type' => 'integer'],
            
            // Opportunity Settings
            ['key' => 'opportunity_auto_close_days', 'value' => '90', 'description' => 'Auto-close days for opportunities', 'type' => 'integer'],
            ['key' => 'opportunity_probability_default', 'value' => '50', 'description' => 'Default probability for opportunities', 'type' => 'integer'],
            ['key' => 'opportunity_amount_required', 'value' => '0', 'description' => 'Require amount for opportunities', 'type' => 'boolean'],
            
            // Activity Settings
            ['key' => 'activity_auto_reminders', 'value' => '1', 'description' => 'Enable automatic activity reminders', 'type' => 'boolean'],
            ['key' => 'activity_reminder_hours', 'value' => '24', 'description' => 'Reminder hours before due', 'type' => 'integer'],
            ['key' => 'activity_auto_complete', 'value' => '0', 'description' => 'Auto-complete overdue activities', 'type' => 'boolean'],
            
            // Email Settings
            ['key' => 'email_notifications', 'value' => '1', 'description' => 'Enable email notifications', 'type' => 'boolean'],
            ['key' => 'email_lead_assignment', 'value' => '1', 'description' => 'Email notifications for lead assignment', 'type' => 'boolean'],
            ['key' => 'email_opportunity_updates', 'value' => '1', 'description' => 'Email notifications for opportunity updates', 'type' => 'boolean'],
            ['key' => 'email_activity_reminders', 'value' => '1', 'description' => 'Email notifications for activity reminders', 'type' => 'boolean'],
            
            // Backup Settings
            ['key' => 'auto_backup_enabled', 'value' => '0', 'description' => 'Enable automatic backups', 'type' => 'boolean'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'description' => 'Backup frequency', 'type' => 'string'],
            ['key' => 'backup_retention_days', 'value' => '30', 'description' => 'Backup retention days', 'type' => 'integer'],
            ['key' => 'backup_email_notifications', 'value' => '1', 'description' => 'Email notifications for backups', 'type' => 'boolean'],
            
            // Automation Settings
            ['key' => 'auto_lead_conversion', 'value' => '0', 'description' => 'Auto-convert high-scoring leads', 'type' => 'boolean'],
            ['key' => 'auto_opportunity_stage_progression', 'value' => '0', 'description' => 'Auto-progress opportunity stages', 'type' => 'boolean'],
            ['key' => 'auto_activity_creation', 'value' => '0', 'description' => 'Auto-create follow-up activities', 'type' => 'boolean'],
            ['key' => 'auto_follow_up_sequences', 'value' => '0', 'description' => 'Auto-follow-up sequences', 'type' => 'boolean'],
            
            // Integration Settings
            ['key' => 'google_calendar_sync', 'value' => '0', 'description' => 'Google Calendar sync', 'type' => 'boolean'],
            ['key' => 'email_integration', 'value' => '0', 'description' => 'Email integration', 'type' => 'boolean'],
            ['key' => 'sms_notifications', 'value' => '0', 'description' => 'SMS notifications', 'type' => 'boolean'],
            ['key' => 'webhook_url', 'value' => '', 'description' => 'Webhook URL for notifications', 'type' => 'string'],
            
            // Security Settings
            ['key' => 'data_encryption', 'value' => '0', 'description' => 'Enable data encryption', 'type' => 'boolean'],
            ['key' => 'audit_logging', 'value' => '1', 'description' => 'Enable audit logging', 'type' => 'boolean'],
            ['key' => 'ip_whitelist', 'value' => '', 'description' => 'IP whitelist', 'type' => 'string'],
            ['key' => 'session_timeout', 'value' => '60', 'description' => 'Session timeout in minutes', 'type' => 'integer'],
        ];

        foreach ($settings as $setting) {
            DB::table('crm_settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        echo "✓ CRM Settings seeded successfully.\n";
        echo "📊 Created " . count($settings) . " settings.\n";
    }
}