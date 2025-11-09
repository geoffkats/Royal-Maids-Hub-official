<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#512B58]/90 to-[#3B0A45]/90 backdrop-blur-sm border-b border-[#F5B301]/20">
        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white font-['Roboto_Condensed'] tracking-wide">Contact Inquiries</h1>
                        <p class="text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Manage customer inquiries and submissions</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-[#F5B301] font-['Roboto_Condensed'] tracking-wide">{{ $submissions->total() }}</div>
                    <div class="text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Total Submissions</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <div class="space-y-8">
            <!-- Filters Card -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-xl p-6">
                <h3 class="text-xl font-bold text-white mb-6 font-['Roboto_Condensed'] tracking-wide">Filter & Search</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-[#D1C4E9] mb-3 font-['Roboto_Condensed'] tracking-wide">Search</label>
                        <div class="relative">
                            <input type="text" 
                                   id="search"
                                   wire:model.live="search" 
                                   placeholder="Search by name, email, or phone..."
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300">
                            <svg class="absolute right-3 top-3 w-5 h-5 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-[#D1C4E9] mb-3 font-['Roboto_Condensed'] tracking-wide">Status</label>
                        <select id="statusFilter" 
                                wire:model.live="statusFilter"
                                class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                            <option value="" class="bg-[#512B58] text-white">All Statuses</option>
                            <option value="new" class="bg-[#512B58] text-white">New</option>
                            <option value="contacted" class="bg-[#512B58] text-white">Contacted</option>
                            <option value="converted" class="bg-[#512B58] text-white">Converted</option>
                            <option value="closed" class="bg-[#512B58] text-white">Closed</option>
                        </select>
                    </div>

                    <!-- Service Filter -->
                    <div>
                        <label for="serviceFilter" class="block text-sm font-medium text-[#D1C4E9] mb-3 font-['Roboto_Condensed'] tracking-wide">Service</label>
                        <select id="serviceFilter" 
                                wire:model.live="serviceFilter"
                                class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                            <option value="" class="bg-[#512B58] text-white">All Services</option>
                            <option value="maidservant" class="bg-[#512B58] text-white">Professional Maidservant</option>
                            <option value="home-manager" class="bg-[#512B58] text-white">Home Manager</option>
                            <option value="bedside-nurse" class="bg-[#512B58] text-white">Bedside Nurse</option>
                            <option value="elderly-care" class="bg-[#512B58] text-white">Elderly Caretaker</option>
                            <option value="nanny" class="bg-[#512B58] text-white">Nanny Services</option>
                            <option value="temporary" class="bg-[#512B58] text-white">Temporary Maid</option>
                            <option value="stay-out" class="bg-[#512B58] text-white">Stay-out Maid</option>
                            <option value="fast-response" class="bg-[#512B58] text-white">Fast Response Service</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Status Overview Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 backdrop-blur-sm border border-blue-400/30 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold text-blue-300 font-['Roboto_Condensed'] tracking-wide">{{ $statusCounts['new'] ?? 0 }}</div>
                    <div class="text-sm text-blue-200 font-['Roboto_Condensed'] tracking-wide">New</div>
                </div>
                <div class="bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 backdrop-blur-sm border border-yellow-400/30 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-300 font-['Roboto_Condensed'] tracking-wide">{{ $statusCounts['contacted'] ?? 0 }}</div>
                    <div class="text-sm text-yellow-200 font-['Roboto_Condensed'] tracking-wide">Contacted</div>
                </div>
                <div class="bg-gradient-to-br from-green-500/20 to-green-600/20 backdrop-blur-sm border border-green-400/30 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold text-green-300 font-['Roboto_Condensed'] tracking-wide">{{ $statusCounts['converted'] ?? 0 }}</div>
                    <div class="text-sm text-green-200 font-['Roboto_Condensed'] tracking-wide">Converted</div>
                </div>
                <div class="bg-gradient-to-br from-gray-500/20 to-gray-600/20 backdrop-blur-sm border border-gray-400/30 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold text-gray-300 font-['Roboto_Condensed'] tracking-wide">{{ $statusCounts['closed'] ?? 0 }}</div>
                    <div class="text-sm text-gray-200 font-['Roboto_Condensed'] tracking-wide">Closed</div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#F5B301]/20">
                    <h3 class="text-xl font-bold text-white font-['Roboto_Condensed'] tracking-wide">Contact Submissions</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#F5B301]/20">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider font-['Roboto_Condensed']">Contact</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider font-['Roboto_Condensed']">Service</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider font-['Roboto_Condensed']">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider font-['Roboto_Condensed']">Submitted</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-[#D1C4E9] uppercase tracking-wider font-['Roboto_Condensed']">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/5 divide-y divide-[#F5B301]/20">
                            @forelse($submissions as $submission)
                                <tr class="hover:bg-white/10 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-white font-['Roboto_Condensed'] tracking-wide">{{ $submission->name }}</div>
                                            <div class="text-sm text-[#D1C4E9]">{{ $submission->email }}</div>
                                            <div class="text-sm text-[#D1C4E9]">{{ $submission->phone }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white font-['Roboto_Condensed'] tracking-wide">{{ $submission->service_name }}</div>
                                        @if($submission->family_size)
                                            <div class="text-sm text-[#D1C4E9]">{{ $submission->family_size }} members</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full font-['Roboto_Condensed'] tracking-wide
                                            @if($submission->status === 'new') bg-blue-500/20 text-blue-300 border border-blue-400/30
                                            @elseif($submission->status === 'contacted') bg-yellow-500/20 text-yellow-300 border border-yellow-400/30
                                            @elseif($submission->status === 'converted') bg-green-500/20 text-green-300 border border-green-400/30
                                            @else bg-gray-500/20 text-gray-300 border border-gray-400/30
                                            @endif">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">
                                        {{ $submission->created_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <button wire:click="viewSubmission({{ $submission->id }})"
                                                    class="text-[#F5B301] hover:text-[#FFD700] transition-colors font-['Roboto_Condensed'] tracking-wide">
                                                View
                                            </button>
                                            <button wire:click="deleteSubmission({{ $submission->id }})"
                                                    wire:confirm="Are you sure you want to delete this submission?"
                                                    class="text-red-400 hover:text-red-300 transition-colors font-['Roboto_Condensed'] tracking-wide">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-[#F5B301]/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            No submissions found.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-[#F5B301]/20">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal && $selectedSubmission)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 w-11/12 md:w-3/4 lg:w-1/2">
                <div class="bg-gradient-to-br from-[#512B58] to-[#3B0A45] rounded-2xl border border-[#F5B301]/30 shadow-2xl">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white font-['Roboto_Condensed'] tracking-wide">Submission Details</h3>
                            </div>
                            <button wire:click="closeModal" class="text-[#D1C4E9] hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="space-y-6">
                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Name</label>
                                    <p class="text-white font-['Roboto_Condensed'] tracking-wide">{{ $selectedSubmission->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Phone</label>
                                    <p class="text-white font-['Roboto_Condensed'] tracking-wide">{{ $selectedSubmission->phone }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Email</label>
                                    <p class="text-white font-['Roboto_Condensed'] tracking-wide">{{ $selectedSubmission->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Service</label>
                                    <p class="text-white font-['Roboto_Condensed'] tracking-wide">{{ $selectedSubmission->service_name }}</p>
                                </div>
                            </div>

                            <!-- Message -->
                            @if($selectedSubmission->message)
                                <div>
                                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Message</label>
                                    <p class="text-white bg-white/10 p-4 rounded-xl border border-[#F5B301]/30 font-['Roboto_Condensed'] tracking-wide">{{ $selectedSubmission->message }}</p>
                                </div>
                            @endif

                            <!-- Status and Notes -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Status</label>
                                    <select wire:model="status" 
                                            class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                        <option value="new" class="bg-[#512B58] text-white">New</option>
                                        <option value="contacted" class="bg-[#512B58] text-white">Contacted</option>
                                        <option value="converted" class="bg-[#512B58] text-white">Converted</option>
                                        <option value="closed" class="bg-[#512B58] text-white">Closed</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Submitted</label>
                                    <p class="text-white font-['Roboto_Condensed'] tracking-wide">{{ $selectedSubmission->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Notes</label>
                                <textarea wire:model="notes" 
                                          rows="3"
                                          class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                          placeholder="Add notes about this submission..."></textarea>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-[#F5B301]/20">
                                <button wire:click="closeModal" 
                                        class="px-6 py-3 text-sm font-medium text-[#D1C4E9] bg-white/10 border border-[#F5B301]/30 rounded-xl hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-[#F5B301] transition-all duration-300 font-['Roboto_Condensed'] tracking-wide">
                                    Cancel
                                </button>
                                <button wire:click="updateSubmission" 
                                        class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#F5B301] to-[#FFD700] border border-transparent rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] focus:outline-none focus:ring-2 focus:ring-[#F5B301] transition-all duration-300 font-['Roboto_Condensed'] tracking-wide">
                                    Update Submission
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 font-['Roboto_Condensed'] tracking-wide">
            {{ session('message') }}
        </div>
    @endif
</div>