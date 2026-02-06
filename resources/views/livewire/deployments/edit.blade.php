<div class="mx-auto max-w-4xl">
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] shadow-lg">
        <!-- Header -->
        <div class="border-b border-[#F5B301]/20 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">
                Edit Deployment - {{ $deployment->maid->full_name }}
            </h2>
            <p class="mt-1 text-sm text-[#D1C4E9]">
                Client: {{ $deployment->client->contact_person ?? 'Unknown' }}
            </p>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-[#4CAF50]/40 bg-[#4CAF50]/10 p-4">
                    <p class="text-[#4CAF50]">{{ session('success') }}</p>
                </div>
            @endif

            @can('updateSensitiveFinancials')
                <form wire:submit="updateFinancials" class="space-y-6">
                <!-- Financial Information Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Maid Salary -->
                    <div>
                        <label for="maid_salary" class="mb-2 block text-sm font-semibold text-[#D1C4E9]">
                            Maid Salary
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                type="number" 
                                wire:model="maid_salary" 
                                id="maid_salary"
                                step="1000"
                                min="0"
                                class="flex-1 rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] px-4 py-3 text-white shadow-sm placeholder:text-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/40"
                                placeholder="0">
                            <span class="whitespace-nowrap text-[#D1C4E9]">{{ $currency }}</span>
                        </div>
                        @error('maid_salary')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client Payment -->
                    <div>
                        <label for="client_payment" class="mb-2 block text-sm font-semibold text-[#D1C4E9]">
                            Client Payment
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                type="number" 
                                wire:model="client_payment" 
                                id="client_payment"
                                step="1000"
                                min="0"
                                class="flex-1 rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] px-4 py-3 text-white shadow-sm placeholder:text-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/40"
                                placeholder="0">
                            <span class="whitespace-nowrap text-[#D1C4E9]">{{ $currency }}</span>
                        </div>
                        @error('client_payment')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Paid -->
                    <div>
                        <label for="service_paid" class="mb-2 block text-sm font-semibold text-[#D1C4E9]">
                            Service Fee
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                type="number" 
                                wire:model="service_paid" 
                                id="service_paid"
                                step="1000"
                                min="0"
                                class="flex-1 rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] px-4 py-3 text-white shadow-sm placeholder:text-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/40"
                                placeholder="0">
                            <span class="whitespace-nowrap text-[#D1C4E9]">{{ $currency }}</span>
                        </div>
                        @error('service_paid')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="mb-2 block text-sm font-semibold text-[#D1C4E9]">
                            Payment Status
                        </label>
                        <select 
                            wire:model="payment_status" 
                            id="payment_status"
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] px-4 py-3 text-white shadow-sm focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/40">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salary Paid Date -->
                    <div>
                        <label for="salary_paid_date" class="mb-2 block text-sm font-semibold text-[#D1C4E9]">
                            Salary Paid Date
                        </label>
                        <input 
                            type="date" 
                            wire:model="salary_paid_date" 
                            id="salary_paid_date"
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] px-4 py-3 text-white shadow-sm focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/40">
                        @error('salary_paid_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div>
                        <label for="currency" class="mb-2 block text-sm font-semibold text-[#D1C4E9]">
                            Currency
                        </label>
                        <input 
                            type="text" 
                            wire:model="currency" 
                            id="currency"
                            maxlength="3"
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] px-4 py-3 text-white shadow-sm placeholder:text-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/40"
                            placeholder="UGX">
                        @error('currency')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Summary Box -->
                @if ($maid_salary !== null && $client_payment !== null && $service_paid !== null)
                    <div class="rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] p-4">
                        <p class="text-sm text-[#D1C4E9]">
                            <span class="font-semibold">Profit Margin:</span> 
                            {{ number_format($client_payment - $maid_salary + $service_paid, 2) }} {{ $currency }}
                            @if ($client_payment > 0)
                                ({{ number_format(($client_payment - $maid_salary + $service_paid) / $client_payment * 100, 1) }}%)
                            @endif
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-4 border-t border-[#F5B301]/20 pt-4">
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="rounded-lg bg-[#F5B301] px-6 py-3 font-semibold text-[#3B0A45] transition hover:bg-[#F5B301]/90 disabled:bg-[#F5B301]/60">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </button>
                    <a 
                        href="{{ route('deployments.show', $deployment) }}"
                        class="rounded-lg border border-[#F5B301]/30 px-6 py-3 font-semibold text-[#D1C4E9] transition hover:bg-[#F5B301]/10">
                        Cancel
                    </a>
                </div>
                </form>
            @else
                <div class="rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200">
                    {{ __('You do not have permission to edit financial fields.') }}
                </div>
            @endcan
        </div>
    </div>
</div>
