<div class="space-y-6">
    <!-- Success Message -->
    @if ($message)
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
            <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ $message }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">Manage Trainer Permissions</flux:heading>
            <flux:subheading class="mt-1">Control access to sidebar items for each trainer</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <!-- Search Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">Search Trainers</h3>
        </div>

        <flux:input 
            wire:model.live.debounce.400ms="search" 
            placeholder="Search by name, email, or specialization..."
            icon="magnifying-glass"
        />
    </div>

    <!-- No Trainers Found -->
    @if ($trainers->isEmpty() && $search)
        <div class="rounded-lg border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 p-6">
            <div class="flex items-center gap-3">
                <flux:icon.exclamation-circle class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                <p class="text-amber-800 dark:text-amber-200">No trainers found matching "{{ $search }}"</p>
            </div>
        </div>
    @elseif ($trainers->isEmpty())
        <div class="rounded-lg border border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700 p-6">
            <div class="flex items-center gap-3">
                <flux:icon.information-circle class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                <p class="text-blue-800 dark:text-blue-200">No trainers available in the system</p>
            </div>
        </div>
    @else
        <!-- Info Box -->
        <div class="rounded-lg border border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700 p-4 mb-6">
            <div class="flex gap-3">
                <flux:icon.information-circle class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <p class="font-medium mb-1">Dashboard is always visible</p>
                    <p>The dashboard is automatically accessible to all trainers and cannot be restricted.</p>
                </div>
            </div>
        </div>

        <!-- Permissions by Section -->
        <div class="space-y-8">
            @foreach ($itemsBySection as $sectionName => $sectionItems)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Section Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $sectionName }}</h3>
                    </div>

                    <!-- Items Grid -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 sticky left-0 bg-gray-50 dark:bg-gray-800/50">
                                        Item
                                    </th>
                                    @foreach ($trainers as $trainer)
                                        <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            <div class="text-xs font-medium">
                                                {{ $trainer->user?->name ?? 'N/A' }}
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($sectionItems as $itemKey => $itemData)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 sticky left-0 bg-white dark:bg-gray-800 font-medium text-gray-900 dark:text-gray-100">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-indigo-700 dark:text-indigo-400">{{ $itemData['label'] }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $itemData['description'] }}</span>
                                            </div>
                                        </td>

                                        @foreach ($trainers as $trainer)
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex justify-center">
                                                    <flux:checkbox 
                                                        wire:model.live="permissions.{{ $trainer->id }}.{{ $itemKey }}"
                                                        :label="false"
                                                        class="rounded"
                                                    />
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Save Button -->
        <div class="flex justify-end gap-3 mt-8 sticky bottom-0 bg-white dark:bg-gray-900 py-4 border-t border-gray-200 dark:border-gray-700">
            <flux:button 
                wire:click="savePermissions"
                wire:loading.attr="disabled"
                variant="primary"
                icon="shield-check"
            >
                <span wire:loading.remove>Save All Permissions</span>
                <span wire:loading>
                    <flux:icon.arrow-path class="animate-spin w-4 h-4" />
                </span>
            </flux:button>
        </div>
    @endif
</div>
