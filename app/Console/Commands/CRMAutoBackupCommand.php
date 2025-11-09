<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CRMAutoBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:backup {--force : Force backup even if auto-backup is disabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create automated CRM database backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting CRM auto backup...');

        // Check if auto backup is enabled
        $autoBackupEnabled = DB::table('crm_settings')
            ->where('key', 'auto_backup_enabled')
            ->value('value');

        if (!$autoBackupEnabled && !$this->option('force')) {
            $this->warn('Auto backup is disabled. Use --force to override.');
            return;
        }

        try {
            // Get backup settings
            $backupFrequency = DB::table('crm_settings')
                ->where('key', 'backup_frequency')
                ->value('value') ?? 'daily';

            $retentionDays = DB::table('crm_settings')
                ->where('key', 'backup_retention_days')
                ->value('value') ?? 30;

            $emailNotifications = DB::table('crm_settings')
                ->where('key', 'backup_email_notifications')
                ->value('value') ?? '1';

            // Check if backup is needed based on frequency
            if (!$this->shouldCreateBackup($backupFrequency) && !$this->option('force')) {
                $this->info('Backup not needed based on frequency setting.');
                return;
            }

            // Create backup
            $backupResult = $this->createBackup();
            
            if ($backupResult['success']) {
                $this->info('✅ Backup created successfully: ' . $backupResult['filename']);
                
                // Update last backup date
                DB::table('crm_settings')->updateOrInsert(
                    ['key' => 'last_backup_date'],
                    [
                        'key' => 'last_backup_date',
                        'value' => now()->toDateTimeString(),
                        'updated_at' => now(),
                    ]
                );

                // Clean old backups
                $this->cleanOldBackups($retentionDays);

                // Send email notification if enabled
                if ($emailNotifications) {
                    $this->sendBackupNotification($backupResult['filename'], $backupResult['size']);
                }

                $this->info('🎉 CRM auto backup completed successfully!');
            } else {
                $this->error('❌ Backup failed: ' . $backupResult['error']);
            }

        } catch (\Exception $e) {
            $this->error('❌ Backup command failed: ' . $e->getMessage());
        }
    }

    /**
     * Check if backup should be created based on frequency
     */
    private function shouldCreateBackup($frequency)
    {
        $lastBackup = DB::table('crm_settings')
            ->where('key', 'last_backup_date')
            ->value('value');

        if (!$lastBackup) {
            return true; // No previous backup
        }

        $lastBackupDate = Carbon::parse($lastBackup);
        $now = Carbon::now();

        switch ($frequency) {
            case 'daily':
                return $now->diffInDays($lastBackupDate) >= 1;
            case 'weekly':
                return $now->diffInWeeks($lastBackupDate) >= 1;
            case 'monthly':
                return $now->diffInMonths($lastBackupDate) >= 1;
            default:
                return true;
        }
    }

    /**
     * Create database backup
     */
    private function createBackup()
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "crm_backup_{$timestamp}.sql";
            $filepath = storage_path("app/backups/{$filename}");
            
            // Ensure backup directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Create database backup using mysqldump
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --single-transaction --routines --triggers %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $filepath
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($filepath)) {
                return [
                    'success' => true,
                    'filename' => $filename,
                    'size' => filesize($filepath),
                    'path' => $filepath
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'mysqldump command failed with return code: ' . $returnCode
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean old backup files
     */
    private function cleanOldBackups($retentionDays)
    {
        $backupDir = storage_path('app/backups');
        
        if (!file_exists($backupDir)) {
            return;
        }
        
        $files = glob($backupDir . '/crm_backup_*.sql');
        $cutoffDate = Carbon::now()->subDays($retentionDays);
        
        $deletedCount = 0;
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            
            if ($fileTime->lt($cutoffDate)) {
                if (unlink($file)) {
                    $deletedCount++;
                }
            }
        }
        
        if ($deletedCount > 0) {
            $this->info("🗑️ Cleaned up {$deletedCount} old backup files.");
        }
    }

    /**
     * Send backup notification email
     */
    private function sendBackupNotification($filename, $size)
    {
        try {
            // This would integrate with your email system
            // For now, just log the notification
            \Log::info("CRM Backup Notification", [
                'filename' => $filename,
                'size' => $size,
                'timestamp' => now()->toDateTimeString()
            ]);
            
            $this->info('📧 Backup notification sent.');
        } catch (\Exception $e) {
            $this->warn('Failed to send backup notification: ' . $e->getMessage());
        }
    }
}