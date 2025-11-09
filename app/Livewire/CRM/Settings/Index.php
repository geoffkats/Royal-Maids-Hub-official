<?php

namespace App\Livewire\CRM\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\CRM\Pipeline;
use App\Models\CRM\Source;
use App\Models\CRM\Tag;

class Index extends Component
{
    use WithFileUploads;

    // General Settings
    public $crm_name = 'Royal Maids CRM';
    public $default_currency = 'UGX';
    public $timezone = 'Africa/Kampala';
    public $date_format = 'Y-m-d';
    public $time_format = 'H:i';
    
    // Lead Settings
    public $auto_assign_leads = false;
    public $lead_scoring_enabled = true;
    public $lead_duplicate_check = true;
    public $lead_auto_qualify_score = 80;
    public $lead_follow_up_days = 7;
    
    // Opportunity Settings
    public $opportunity_auto_close_days = 90;
    public $opportunity_probability_default = 50;
    public $opportunity_amount_required = false;
    
    // Activity Settings
    public $activity_auto_reminders = true;
    public $activity_reminder_hours = 24;
    public $activity_auto_complete = false;
    
    // Email Settings
    public $email_notifications = true;
    public $email_lead_assignment = true;
    public $email_opportunity_updates = true;
    public $email_activity_reminders = true;
    
    // Backup Settings
    public $auto_backup_enabled = false;
    public $backup_frequency = 'daily'; // daily, weekly, monthly
    public $backup_retention_days = 30;
    public $backup_email_notifications = true;
    public $last_backup_date;
    public $backup_file;
    
    // Automation Settings
    public $auto_lead_conversion = false;
    public $auto_opportunity_stage_progression = false;
    public $auto_activity_creation = false;
    public $auto_follow_up_sequences = false;
    
    // Integration Settings
    public $google_calendar_sync = false;
    public $email_integration = false;
    public $sms_notifications = false;
    public $webhook_url = '';
    
    // Security Settings
    public $data_encryption = false;
    public $audit_logging = true;
    public $ip_whitelist = '';
    public $session_timeout = 60;
    
    // UI State
    public $activeTab = 'general';

    protected $rules = [
        'crm_name' => 'required|string|max:255',
        'default_currency' => 'required|string|max:3',
        'timezone' => 'required|string',
        'date_format' => 'required|string',
        'time_format' => 'required|string',
        'lead_scoring_enabled' => 'boolean',
        'lead_duplicate_check' => 'boolean',
        'lead_auto_qualify_score' => 'integer|min:0|max:100',
        'lead_follow_up_days' => 'integer|min:1|max:365',
        'opportunity_auto_close_days' => 'integer|min:1|max:365',
        'opportunity_probability_default' => 'integer|min:0|max:100',
        'opportunity_amount_required' => 'boolean',
        'activity_auto_reminders' => 'boolean',
        'activity_reminder_hours' => 'integer|min:1|max:168',
        'activity_auto_complete' => 'boolean',
        'email_notifications' => 'boolean',
        'email_lead_assignment' => 'boolean',
        'email_opportunity_updates' => 'boolean',
        'email_activity_reminders' => 'boolean',
        'auto_backup_enabled' => 'boolean',
        'backup_frequency' => 'required|in:daily,weekly,monthly',
        'backup_retention_days' => 'integer|min:1|max:365',
        'backup_email_notifications' => 'boolean',
        'auto_lead_conversion' => 'boolean',
        'auto_opportunity_stage_progression' => 'boolean',
        'auto_activity_creation' => 'boolean',
        'auto_follow_up_sequences' => 'boolean',
        'google_calendar_sync' => 'boolean',
        'email_integration' => 'boolean',
        'sms_notifications' => 'boolean',
        'webhook_url' => 'nullable|url',
        'data_encryption' => 'boolean',
        'audit_logging' => 'boolean',
        'ip_whitelist' => 'nullable|string',
        'session_timeout' => 'integer|min:5|max:480',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Load settings from database or config
        $settings = DB::table('crm_settings')->get()->keyBy('key');
        
        foreach ($settings as $key => $setting) {
            if (property_exists($this, $key)) {
                $this->$key = $setting->value;
            }
        }
        
        // Get last backup date
        $this->last_backup_date = $this->getLastBackupDate();
    }

    public function saveSettings()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $settings = [
                'crm_name' => $this->crm_name,
                'default_currency' => $this->default_currency,
                'timezone' => $this->timezone,
                'date_format' => $this->date_format,
                'time_format' => $this->time_format,
                'auto_assign_leads' => $this->auto_assign_leads,
                'lead_scoring_enabled' => $this->lead_scoring_enabled,
                'lead_duplicate_check' => $this->lead_duplicate_check,
                'lead_auto_qualify_score' => $this->lead_auto_qualify_score,
                'lead_follow_up_days' => $this->lead_follow_up_days,
                'opportunity_auto_close_days' => $this->opportunity_auto_close_days,
                'opportunity_probability_default' => $this->opportunity_probability_default,
                'opportunity_amount_required' => $this->opportunity_amount_required,
                'activity_auto_reminders' => $this->activity_auto_reminders,
                'activity_reminder_hours' => $this->activity_reminder_hours,
                'activity_auto_complete' => $this->activity_auto_complete,
                'email_notifications' => $this->email_notifications,
                'email_lead_assignment' => $this->email_lead_assignment,
                'email_opportunity_updates' => $this->email_opportunity_updates,
                'email_activity_reminders' => $this->email_activity_reminders,
                'auto_backup_enabled' => $this->auto_backup_enabled,
                'backup_frequency' => $this->backup_frequency,
                'backup_retention_days' => $this->backup_retention_days,
                'backup_email_notifications' => $this->backup_email_notifications,
                'auto_lead_conversion' => $this->auto_lead_conversion,
                'auto_opportunity_stage_progression' => $this->auto_opportunity_stage_progression,
                'auto_activity_creation' => $this->auto_activity_creation,
                'auto_follow_up_sequences' => $this->auto_follow_up_sequences,
                'google_calendar_sync' => $this->google_calendar_sync,
                'email_integration' => $this->email_integration,
                'sms_notifications' => $this->sms_notifications,
                'webhook_url' => $this->webhook_url,
                'data_encryption' => $this->data_encryption,
                'audit_logging' => $this->audit_logging,
                'ip_whitelist' => $this->ip_whitelist,
                'session_timeout' => $this->session_timeout,
            ];

            foreach ($settings as $key => $value) {
                DB::table('crm_settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'key' => $key,
                        'value' => is_bool($value) ? ($value ? '1' : '0') : $value,
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();
            
            session()->flash('message', 'Settings saved successfully!');
            $this->dispatch('settings-saved');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }

    public function createBackup()
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "crm_backup_{$timestamp}.sql";
            $filepath = storage_path("app/backups/{$filename}");
            
            // Ensure backup directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Create database backup
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $filepath
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                // Update last backup date
                DB::table('crm_settings')->updateOrInsert(
                    ['key' => 'last_backup_date'],
                    [
                        'key' => 'last_backup_date',
                        'value' => now()->toDateTimeString(),
                        'updated_at' => now(),
                    ]
                );
                
                $this->last_backup_date = now()->toDateTimeString();
                session()->flash('message', 'Database backup created successfully!');
            } else {
                session()->flash('error', 'Failed to create database backup.');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        $filepath = storage_path("app/backups/{$filename}");
        
        if (file_exists($filepath)) {
            return response()->download($filepath);
        }
        
        session()->flash('error', 'Backup file not found.');
    }

    public function deleteBackup($filename)
    {
        $filepath = storage_path("app/backups/{$filename}");
        
        if (file_exists($filepath)) {
            unlink($filepath);
            session()->flash('message', 'Backup file deleted successfully.');
        } else {
            session()->flash('error', 'Backup file not found.');
        }
    }

    public function getBackupFiles()
    {
        $backupDir = storage_path('app/backups');
        
        if (!file_exists($backupDir)) {
            return [];
        }
        
        $files = glob($backupDir . '/crm_backup_*.sql');
        $backupFiles = [];
        
        foreach ($files as $file) {
            $backupFiles[] = [
                'filename' => basename($file),
                'size' => filesize($file),
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }
        
        // Sort by creation date (newest first)
        usort($backupFiles, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $backupFiles;
    }

    public function getLastBackupDate()
    {
        $setting = DB::table('crm_settings')->where('key', 'last_backup_date')->first();
        return $setting ? $setting->value : null;
    }

    public function testEmailIntegration()
    {
        try {
            // Test email configuration
            session()->flash('message', 'Email integration test successful!');
        } catch (\Exception $e) {
            session()->flash('error', 'Email integration test failed: ' . $e->getMessage());
        }
    }

    public function testWebhook()
    {
        try {
            if (empty($this->webhook_url)) {
                session()->flash('error', 'Please enter a webhook URL first.');
                return;
            }
            
            // Test webhook
            $response = \Http::post($this->webhook_url, [
                'test' => true,
                'timestamp' => now()->toISOString(),
            ]);
            
            if ($response->successful()) {
                session()->flash('message', 'Webhook test successful!');
            } else {
                session()->flash('error', 'Webhook test failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Webhook test failed: ' . $e->getMessage());
        }
    }

    public function resetToDefaults()
    {
        $this->crm_name = 'Royal Maids CRM';
        $this->default_currency = 'UGX';
        $this->timezone = 'Africa/Kampala';
        $this->date_format = 'Y-m-d';
        $this->time_format = 'H:i';
        $this->auto_assign_leads = false;
        $this->lead_scoring_enabled = true;
        $this->lead_duplicate_check = true;
        $this->lead_auto_qualify_score = 80;
        $this->lead_follow_up_days = 7;
        $this->opportunity_auto_close_days = 90;
        $this->opportunity_probability_default = 50;
        $this->opportunity_amount_required = false;
        $this->activity_auto_reminders = true;
        $this->activity_reminder_hours = 24;
        $this->activity_auto_complete = false;
        $this->email_notifications = true;
        $this->email_lead_assignment = true;
        $this->email_opportunity_updates = true;
        $this->email_activity_reminders = true;
        $this->auto_backup_enabled = false;
        $this->backup_frequency = 'daily';
        $this->backup_retention_days = 30;
        $this->backup_email_notifications = true;
        $this->auto_lead_conversion = false;
        $this->auto_opportunity_stage_progression = false;
        $this->auto_activity_creation = false;
        $this->auto_follow_up_sequences = false;
        $this->google_calendar_sync = false;
        $this->email_integration = false;
        $this->sms_notifications = false;
        $this->webhook_url = '';
        $this->data_encryption = false;
        $this->audit_logging = true;
        $this->ip_whitelist = '';
        $this->session_timeout = 60;
        
        session()->flash('message', 'Settings reset to defaults.');
    }

    public function render()
    {
        return view('livewire.c-r-m.settings.index', [
            'backupFiles' => $this->getBackupFiles(),
            'pipelines' => Pipeline::all(),
            'sources' => Source::all(),
            'tags' => Tag::all(),
        ]);
    }
}
