<div class="max-w-7xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Maid Contracts</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage maid contracts and lifecycle
                </p>
            </div>
            <a href="{{ route('contracts.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Add Contract
            </a>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Search by maid name or code..."
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div>
                    <select 
                        wire:model.live="status_filter"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr class="border-b border-slate-200 dark:border-slate-600">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Maid</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Contract Period</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Worked Days</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Pending Days</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr class="border-b border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white">
                                        {{ $contract->maid->full_name }}
                                    </p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $contract->maid->maid_code }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $client = $contract->getClient();
                                    $clientName = $client?->company_name ?: $client?->contact_person;
                                @endphp
                                @if($client)
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">
                                            {{ $clientName }}
                                        </p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            {{ $client->phone ?? 'N/A' }}
                                        </p>
                                    </div>
                                @else
                                    <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded text-sm">
                                        No deployment
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-slate-900 dark:text-white">
                                        {{ $contract->contract_start_date?->format('M d, Y') }}
                                    </p>
                                    <p class="text-slate-600 dark:text-slate-400">
                                        to {{ $contract->contract_end_date?->format('M d, Y') ?? 'Ongoing' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm font-medium">
                                    {{ $contract->worked_days ?? 0 }} days
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 rounded-full text-sm font-medium">
                                    {{ $contract->pending_days ?? 0 }} days
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    @if($contract->contract_status === 'active') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                    @elseif($contract->contract_status === 'completed') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                    @elseif($contract->contract_status === 'terminated') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                    @else bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300
                                    @endif">
                                    {{ ucfirst($contract->contract_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('contracts.show', $contract) }}" 
                                       class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded transition">
                                        View
                                    </a>
                                    <a href="{{ route('contracts.edit', $contract) }}" 
                                       class="px-3 py-1 text-sm bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 rounded transition">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-600 dark:text-slate-400">
                                @if($search || $status_filter)
                                    No contracts found matching your criteria
                                @else
                                    No contracts yet. <a href="{{ route('contracts.create') }}" class="text-blue-600 hover:underline">Create one</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contracts->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $contracts->links() }}
            </div>
        @endif
    </div>
</div>
