<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with Royal Maids Branding -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">System Settings</h1>
                    <p class="mt-2 text-[#D1C4E9]">Configure your Royal Maids system settings and preferences</p>
                </div>
            </div>
        </div>

        <!-- Settings Tabs with Royal Maids Branding -->
        <div class="bg-gradient-to-br from-[#512B58] to-[#3B0A45] rounded-lg shadow-lg border border-[#F5B301]/20">
            <div class="border-b border-[#F5B301]/20">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="$set('activeTab', 'general')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'general' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.cog-6-tooth class="w-4 h-4 inline mr-2" />
                        General
                    </button>
                    <button wire:click="$set('activeTab', 'crm')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'crm' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.chart-bar class="w-4 h-4 inline mr-2" />
                        CRM Settings
                    </button>
                    <button wire:click="$set('activeTab', 'users')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'users' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.users class="w-4 h-4 inline mr-2" />
                        User Management
                    </button>
                    <button wire:click="$set('activeTab', 'security')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'security' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.lock-closed class="w-4 h-4 inline mr-2" />
                        Security
                    </button>
                    <button wire:click="$set('activeTab', 'backup')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'backup' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-[#D1C4E9] hover:text-white hover:border-[#F5B301]/50' }}">
                        <x-flux::icon.shield-check class="w-4 h-4 inline mr-2" />
                        Backup & Recovery
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
                                General System Settings
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">Application Information</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Application Name:</span>
                                            <span class="text-white">Royal Maids Hub</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Version:</span>
                                            <span class="text-white">v5.0</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Environment:</span>
                                            <span class="text-white">{{ app()->environment() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">System Status</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Database:</span>
                                            <span class="text-green-400">✓ Connected</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Cache:</span>
                                            <span class="text-green-400">✓ Active</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Queue:</span>
                                            <span class="text-green-400">✓ Running</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- CRM Settings -->
                @if($activeTab === 'crm')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.chart-bar class="w-5 h-5 text-[#F5B301] mr-2" />
                                CRM Configuration
                            </h3>
                            <div class="bg-[#512B58]/30 rounded-lg p-6 border border-[#F5B301]/20">
                                <p class="text-[#D1C4E9] mb-4">Manage your CRM system settings, automation rules, and backup configurations.</p>
                                <a href="{{ route('crm.settings.index') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-gold hover:bg-gradient-to-r hover:from-[#F5B301] hover:to-[#FFD54F] text-[#3B0A45] font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <x-flux::icon.cog-6-tooth class="w-5 h-5" />
                                    Open CRM Settings
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Management -->
                @if($activeTab === 'users')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.users class="w-5 h-5 text-[#F5B301] mr-2" />
                                User Management
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B58]/20">
                                    <h4 class="text-white font-semibold mb-2">User Statistics</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Total Users:</span>
                                            <span class="text-white">{{ \App\Models\User::count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Admins:</span>
                                            <span class="text-white">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Trainers:</span>
                                            <span class="text-white">{{ \App\Models\User::where('role', 'trainer')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Clients:</span>
                                            <span class="text-white">{{ \App\Models\User::where('role', 'client')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">Quick Actions</h4>
                                    <div class="space-y-3">
                                        <a href="{{ route('trainers.index') }}" 
                                           class="block w-full text-center px-4 py-2 bg-[#F5B301]/20 hover:bg-[#F5B301]/30 text-white rounded-lg transition-colors duration-200">
                                            Manage Trainers
                                        </a>
                                        <a href="{{ route('clients.index') }}" 
                                           class="block w-full text-center px-4 py-2 bg-[#F5B301]/20 hover:bg-[#F5B301]/30 text-white rounded-lg transition-colors duration-200">
                                            Manage Clients
                                        </a>
                                    </div>
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
                                Security Configuration
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">Authentication</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Two-Factor Auth:</span>
                                            <span class="text-green-400">✓ Enabled</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Email Verification:</span>
                                            <span class="text-green-400">✓ Required</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Password Policy:</span>
                                            <span class="text-green-400">✓ Active</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">Session Management</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Session Timeout:</span>
                                            <span class="text-white">60 minutes</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Remember Me:</span>
                                            <span class="text-green-400">✓ Enabled</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">CSRF Protection:</span>
                                            <span class="text-green-400">✓ Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Backup & Recovery -->
                @if($activeTab === 'backup')
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <x-flux::icon.shield-check class="w-5 h-5 text-[#F5B301] mr-2" />
                                Backup & Recovery
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">Backup Status</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Last Backup:</span>
                                            <span class="text-white">Today, 02:00 AM</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Auto Backup:</span>
                                            <span class="text-green-400">✓ Enabled</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#D1C4E9]">Retention:</span>
                                            <span class="text-white">30 days</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-[#512B58]/30 rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="text-white font-semibold mb-2">Quick Actions</h4>
                                    <div class="space-y-3">
                                        <button class="block w-full text-center px-4 py-2 bg-gradient-gold hover:bg-gradient-to-r hover:from-[#F5B301] hover:to-[#FFD54F] text-[#3B0A45] font-semibold rounded-lg transition-all duration-200">
                                            Create Manual Backup
                                        </button>
                                        <a href="{{ route('crm.settings.index') }}" 
                                           class="block w-full text-center px-4 py-2 bg-[#F5B301]/20 hover:bg-[#F5B301]/30 text-white rounded-lg transition-colors duration-200">
                                            Configure Backup Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
