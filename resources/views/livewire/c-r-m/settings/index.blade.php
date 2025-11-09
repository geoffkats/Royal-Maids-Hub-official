<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with Royal Maids Branding -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">CRM Settings</h1>
                    <p class="mt-2 text-[#D1C4E9]">Configure your CRM system, automation, and backup settings</p>
                </div>
                <div class="flex gap-3">
                    <flux:button wire:click="resetToDefaults" variant="ghost" class="text-[#D1C4E9] hover:text-white border-[#F5B301]/20 hover:border-[#F5B301]/40">
                        <x-flux::icon.arrow-path class="w-4 h-4" />
                        Reset to Defaults
                    </flux:button>
                    <flux:button wire:click="saveSettings" variant="primary" class="bg-gradient-gold hover:bg-gradient-to-r hover:from-[#F5B301] hover:to-[#FFD54F] text-[#3B0A45] font-semibold">
                        <x-flux::icon.check class="w-4 h-4" />
                        Save Settings
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Flash Messages with Brand Colors -->
        @if (session()->has('message'))
            <div class="mb-6 bg-gradient-to-r from-[#4CAF50]/20 to-[#4CAF50]/10 border border-[#4CAF50]/30 rounded-lg p-4">
                <div class="flex">
                    <x-flux::icon.check-circle class="w-5 h-5 text-[#4CAF50]" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-gradient-to-r from-[#E53935]/20 to-[#E53935]/10 border border-[#E53935]/30 rounded-lg p-4">
                <div class="flex">
                    <x-flux::icon.exclamation-triangle class="w-5 h-5 text-[#E53935]" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Settings Tabs with Royal Maids Branding -->
        <div class="bg-gradient-to-br from-[#512B58] to-[#3B0A45] rounded-lg shadow-lg border border-[#F5B301]/20">
            <div class="border-b border-[#F5B301]/20">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="$set('activeTab', 'general')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'general' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.cog-6-tooth class="w-4 h-4 inline mr-2" />
                        General
                    </button>
                    <button wire:click="$set('activeTab', 'leads')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'leads' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.user-plus class="w-4 h-4 inline mr-2" />
                        Leads
                    </button>
                    <button wire:click="$set('activeTab', 'opportunities')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'opportunities' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.chart-bar class="w-4 h-4 inline mr-2" />
                        Opportunities
                    </button>
                    <button wire:click="$set('activeTab', 'activities')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'activities' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.calendar class="w-4 h-4 inline mr-2" />
                        Activities
                    </button>
                    <button wire:click="$set('activeTab', 'backup')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'backup' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.shield-check class="w-4 h-4 inline mr-2" />
                        Backup
                    </button>
                    <button wire:click="$set('activeTab', 'automation')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'automation' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.cpu-chip class="w-4 h-4 inline mr-2" />
                        Automation
                    </button>
                    <button wire:click="$set('activeTab', 'integrations')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'integrations' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.link class="w-4 h-4 inline mr-2" />
                        Integrations
                    </button>
                    <button wire:click="$set('activeTab', 'security')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'security' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.lock-closed class="w-4 h-4 inline mr-2" />
                        Security
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- General Settings -->
                @if($activeTab === 'general')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.cog-6-tooth class="w-5 h-5 text-[#F5B301] mr-2" />
                                General Settings
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">CRM Name</flux:label>
                                        <flux:input wire:model="crm_name" placeholder="Royal Maids CRM" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white placeholder-[#D1C4E9] focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                    </flux:field>
                                </div>
                                <div>
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Default Currency</flux:label>
                                        <flux:select wire:model="default_currency" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20">
                                            <option value="UGX">UGX - Ugandan Shilling</option>
                                            <option value="USD">USD - US Dollar</option>
                                            <option value="EUR">EUR - Euro</option>
                                            <option value="GBP">GBP - British Pound</option>
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <div>
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Timezone</flux:label>
                                        <flux:select wire:model="timezone" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20">
                                            <option value="Africa/Kampala">Africa/Kampala</option>
                                            <option value="UTC">UTC</option>
                                            <option value="America/New_York">America/New_York</option>
                                            <option value="Europe/London">Europe/London</option>
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <div>
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Date Format</flux:label>
                                        <flux:select wire:model="date_format" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20">
                                            <option value="Y-m-d">2024-01-15</option>
                                            <option value="d/m/Y">15/01/2024</option>
                                            <option value="m/d/Y">01/15/2024</option>
                                            <option value="d-m-Y">15-01-2024</option>
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <div>
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Time Format</flux:label>
                                        <flux:select wire:model="time_format" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20">
                                            <option value="H:i">24-hour (14:30)</option>
                                            <option value="h:i A">12-hour (2:30 PM)</option>
                                        </flux:select>
                                    </flux:field>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Lead Settings -->
                @if($activeTab === 'leads')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.user-plus class="w-5 h-5 text-[#F5B301] mr-2" />
                                Lead Management
                            </h3>
                            <div class="space-y-4">
                                <flux:field>
                                    <flux:checkbox wire:model="auto_assign_leads" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Auto-assign leads to available users</flux:label>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="lead_scoring_enabled" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Enable lead scoring system</flux:label>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="lead_duplicate_check" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Check for duplicate leads</flux:label>
                                </flux:field>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Auto-qualify score threshold</flux:label>
                                        <flux:input wire:model="lead_auto_qualify_score" type="number" min="0" max="100" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Leads with score above this will be auto-qualified</flux:description>
                                    </flux:field>
                                    
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Follow-up reminder days</flux:label>
                                        <flux:input wire:model="lead_follow_up_days" type="number" min="1" max="365" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Days to wait before sending follow-up reminders</flux:description>
                                    </flux:field>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Opportunity Settings -->
                @if($activeTab === 'opportunities')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.chart-bar class="w-5 h-5 text-[#F5B301] mr-2" />
                                Opportunity Management
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Auto-close days</flux:label>
                                        <flux:input wire:model="opportunity_auto_close_days" type="number" min="1" max="365" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Days after which opportunities are auto-closed</flux:description>
                                    </flux:field>
                                    
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Default probability</flux:label>
                                        <flux:input wire:model="opportunity_probability_default" type="number" min="0" max="100" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Default probability percentage for new opportunities</flux:description>
                                    </flux:field>
                                </div>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="opportunity_amount_required" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Require amount for opportunities</flux:label>
                                </flux:field>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Activity Settings -->
                @if($activeTab === 'activities')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.calendar class="w-5 h-5 text-[#F5B301] mr-2" />
                                Activity Management
                            </h3>
                            <div class="space-y-4">
                                <flux:field>
                                    <flux:checkbox wire:model="activity_auto_reminders" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Enable automatic activity reminders</flux:label>
                                </flux:field>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Reminder hours before due</flux:label>
                                        <flux:input wire:model="activity_reminder_hours" type="number" min="1" max="168" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Hours before due date to send reminder</flux:description>
                                    </flux:field>
                                </div>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="activity_auto_complete" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Auto-complete overdue activities</flux:label>
                                </flux:field>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Backup Settings -->
                @if($activeTab === 'backup')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.shield-check class="w-5 h-5 text-[#F5B301] mr-2" />
                                Database Backup
                            </h3>
                            
                            <!-- Backup Status -->
                            <div class="bg-gradient-to-r from-[#64B5F6]/20 to-[#64B5F6]/10 border border-[#64B5F6]/30 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <x-flux::icon.shield-check class="w-5 h-5 text-[#64B5F6]" />
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-white">Backup Status</h4>
                                        <p class="text-sm text-[#D1C4E9]">
                                            Last backup: {{ $last_backup_date ? \Carbon\Carbon::parse($last_backup_date)->format('M d, Y H:i') : 'Never' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Backup -->
                            <div class="mb-6">
                                <flux:button wire:click="createBackup" variant="primary" class="mb-4 bg-gradient-gold hover:bg-gradient-to-r hover:from-[#F5B301] hover:to-[#FFD54F] text-[#3B0A45] font-semibold">
                                    <x-flux::icon.arrow-down-tray class="w-4 h-4" />
                                    Create Backup Now
                                </flux:button>
                            </div>

                            <!-- Auto Backup Settings -->
                            <div class="space-y-4">
                                <flux:field>
                                    <flux:checkbox wire:model="auto_backup_enabled" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Enable automatic backups</flux:label>
                                </flux:field>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Backup frequency</flux:label>
                                        <flux:select wire:model="backup_frequency" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </flux:select>
                                    </flux:field>
                                    
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Retention days</flux:label>
                                        <flux:input wire:model="backup_retention_days" type="number" min="1" max="365" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Days to keep backup files</flux:description>
                                    </flux:field>
                                </div>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="backup_email_notifications" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Email notifications for backups</flux:label>
                                </flux:field>
                            </div>

                            <!-- Backup Files List -->
                            @if(count($backupFiles) > 0)
                                <div class="mt-8">
                                    <h4 class="text-lg font-medium text-white mb-4">Backup Files</h4>
                                    <div class="overflow-hidden shadow ring-1 ring-[#F5B301]/20 md:rounded-lg">
                                        <table class="min-w-full divide-y divide-[#F5B301]/20">
                                            <thead class="bg-gradient-to-r from-[#512B58] to-[#3B0A45]">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider">File</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider">Size</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider">Created</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-[#512B58]/30 divide-y divide-[#F5B301]/20">
                                                @foreach($backupFiles as $file)
                                                    <tr class="hover:bg-[#512B58]/50 transition-colors duration-200">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $file['filename'] }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#D1C4E9]">{{ number_format($file['size'] / 1024, 2) }} KB</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#D1C4E9]">{{ \Carbon\Carbon::parse($file['created_at'])->format('M d, Y H:i') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                            <div class="flex space-x-2">
                                                                <flux:button wire:click="downloadBackup('{{ $file['filename'] }}')" variant="ghost" size="sm" class="text-[#64B5F6] hover:text-[#64B5F6]/80 hover:bg-[#64B5F6]/10">
                                                                    <x-flux::icon.arrow-down-tray class="w-4 h-4" />
                                                                </flux:button>
                                                                <flux:button wire:click="deleteBackup('{{ $file['filename'] }}')" variant="ghost" size="sm" class="text-[#E53935] hover:text-[#E53935]/80 hover:bg-[#E53935]/10">
                                                                    <x-flux::icon.trash class="w-4 h-4" />
                                                                </flux:button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Automation Settings -->
                @if($activeTab === 'automation')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.cpu-chip class="w-5 h-5 text-[#F5B301] mr-2" />
                                Automation Rules
                            </h3>
                            <div class="space-y-4">
                                <flux:field>
                                    <flux:checkbox wire:model="auto_lead_conversion" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Auto-convert high-scoring leads</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Automatically convert leads that meet qualification criteria</flux:description>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="auto_opportunity_stage_progression" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Auto-progress opportunity stages</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Automatically move opportunities to next stage based on activity</flux:description>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="auto_activity_creation" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Auto-create follow-up activities</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Automatically create follow-up activities for leads and opportunities</flux:description>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="auto_follow_up_sequences" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Auto-follow-up sequences</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Automatically send follow-up emails based on lead status</flux:description>
                                </flux:field>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Integration Settings -->
                @if($activeTab === 'integrations')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.link class="w-5 h-5 text-[#F5B301] mr-2" />
                                Third-party Integrations
                            </h3>
                            <div class="space-y-4">
                                <flux:field>
                                    <flux:checkbox wire:model="google_calendar_sync" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Google Calendar sync</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Synchronize activities with Google Calendar</flux:description>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="email_integration" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Email integration</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Integrate with email services for notifications</flux:description>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="sms_notifications" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">SMS notifications</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Send SMS notifications for important events</flux:description>
                                </flux:field>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Webhook URL</flux:label>
                                        <flux:input wire:model="webhook_url" placeholder="https://example.com/webhook" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white placeholder-[#D1C4E9] focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">URL to receive webhook notifications</flux:description>
                                    </flux:field>
                                </div>
                                
                                <div class="flex space-x-3">
                                    <flux:button wire:click="testEmailIntegration" variant="ghost" class="text-[#64B5F6] hover:text-[#64B5F6]/80 hover:bg-[#64B5F6]/10 border-[#64B5F6]/20">
                                        <x-flux::icon.envelope class="w-4 h-4" />
                                        Test Email
                                    </flux:button>
                                    <flux:button wire:click="testWebhook" variant="ghost" class="text-[#64B5F6] hover:text-[#64B5F6]/80 hover:bg-[#64B5F6]/10 border-[#64B5F6]/20">
                                        <x-flux::icon.link class="w-4 h-4" />
                                        Test Webhook
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Security Settings -->
                @if($activeTab === 'security')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.lock-closed class="w-5 h-5 text-[#F5B301] mr-2" />
                                Security & Privacy
                            </h3>
                            <div class="space-y-4">
                                <flux:field>
                                    <flux:checkbox wire:model="data_encryption" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Enable data encryption</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Encrypt sensitive CRM data</flux:description>
                                </flux:field>
                                
                                <flux:field>
                                    <flux:checkbox wire:model="audit_logging" class="text-[#F5B301] focus:ring-[#F5B301]/20" />
                                    <flux:label class="text-[#D1C4E9]">Enable audit logging</flux:label>
                                    <flux:description class="text-[#D1C4E9]/70">Log all CRM activities for compliance</flux:description>
                                </flux:field>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">IP Whitelist</flux:label>
                                        <flux:textarea wire:model="ip_whitelist" placeholder="192.168.1.1, 10.0.0.1" rows="3" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white placeholder-[#D1C4E9] focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Comma-separated list of allowed IP addresses</flux:description>
                                    </flux:field>
                                    
                                    <flux:field>
                                        <flux:label class="text-[#D1C4E9]">Session timeout (minutes)</flux:label>
                                        <flux:input wire:model="session_timeout" type="number" min="5" max="480" class="bg-[#512B58]/50 border-[#F5B301]/30 text-white focus:border-[#F5B301] focus:ring-[#F5B301]/20" />
                                        <flux:description class="text-[#D1C4E9]/70">Minutes before user session expires</flux:description>
                                    </flux:field>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Initialize active tab
        document.addEventListener('livewire:init', () => {
            Livewire.on('settings-saved', () => {
                // Show success animation or notification
                console.log('Settings saved successfully!');
            });
        });
    </script>
</div>
