<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    {{-- Soft Delete Notice --}}
    @if($deployment->trashed())
        <flux:callout variant="warning">
            {{ __('This deployment has been deleted. Click "Restore Deployment" to undelete it.') }}
        </flux:callout>
    @endif

    {{-- Page Header --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-white">{{ __('Deployment Details') }}</flux:heading>
            <flux:subheading class="mt-1 text-[#D1C4E9]">{{ __('View deployment information and status') }}</flux:subheading>
        </div>
        <div class="flex gap-3">
            @if($deployment->trashed())
                <flux:button wire:click="restore" variant="primary" icon="arrow-path" class="bg-[#F5B301] text-[#3B0A45] hover:bg-[#F5B301]/90">
                    {{ __('Restore Deployment') }}
                </flux:button>
            @endif
            <flux:button as="a" href="{{ route('deployments.index') }}" variant="ghost" icon="arrow-left" class="text-[#D1C4E9]" wire:navigate>
                {{ __('Back to Deployments') }}
            </flux:button>
        </div>
    </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Deployment Information --}}
    <div class="rounded-xl border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Maid Information --}}
            <div>
                <h3 class="mb-4 text-lg font-semibold text-white">{{ __('Maid Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Name') }}</label>
                        @if($deployment->maid)
                            <a href="{{ route($prefix . 'maids.show', $deployment->maid) }}" wire:navigate class="font-semibold text-white hover:text-[#F5B301]">
                                {{ $deployment->maid->full_name }}
                            </a>
                        @else
                            <p class="text-white">{{ __('Not assigned') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Status') }}</label>
                        <p class="text-white">{{ ucfirst($deployment->maid?->status ?? 'Unknown') }}</p>
                    </div>
                </div>
            </div>

            {{-- Client Information --}}
            <div>
                <h3 class="mb-4 text-lg font-semibold text-white">{{ __('Client Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Contact Person') }}</label>
                        @if($deployment->client)
                            <a href="{{ route($prefix . 'clients.show', $deployment->client) }}" wire:navigate class="font-semibold text-white hover:text-[#F5B301]">
                                {{ $deployment->client->contact_person }}
                            </a>
                        @else
                            <p class="text-white">{{ __('Not assigned') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Phone') }}</label>
                        <p class="text-white">{{ $deployment->client?->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deployment Details --}}
        <div class="mt-6 border-t border-[#F5B301]/20 pt-6">
            <h3 class="mb-4 text-lg font-semibold text-white">{{ __('Deployment Details') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Deployment Date') }}</label>
                    <p class="text-white">{{ $deployment->deployment_date?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Status') }}</label>
                    <p class="text-white">{{ ucfirst($deployment->status ?? 'Unknown') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Created') }}</label>
                    <p class="text-white">{{ $deployment->created_at?->format('M d, Y') ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>

        @can('viewSensitiveFinancials')
            {{-- Financial Information --}}
            <div class="mt-6 border-t border-[#F5B301]/20 pt-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">{{ __('Financial Information') }}</h3>
                    <a href="{{ route('deployments.edit', $deployment) }}" class="rounded-md bg-[#F5B301]/20 px-3 py-1 text-sm font-semibold text-[#F5B301] hover:bg-[#F5B301]/30">
                        {{ __('Edit') }}
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Maid Salary') }}</label>
                        <p class="text-lg font-semibold text-white">
                            {{ $deployment->maid_salary ? number_format($deployment->maid_salary, 2) : '0.00' }} 
                            <span class="text-sm font-normal text-[#D1C4E9]">{{ $deployment->currency ?? 'UGX' }}</span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Client Payment') }}</label>
                        <p class="text-lg font-semibold text-white">
                            {{ $deployment->client_payment ? number_format($deployment->client_payment, 2) : '0.00' }} 
                            <span class="text-sm font-normal text-[#D1C4E9]">{{ $deployment->currency ?? 'UGX' }}</span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Service Fee') }}</label>
                        <p class="text-lg font-semibold text-white">
                            {{ $deployment->service_paid ? number_format($deployment->service_paid, 2) : '0.00' }} 
                            <span class="text-sm font-normal text-[#D1C4E9]">{{ $deployment->currency ?? 'UGX' }}</span>
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Payment Status') }}</label>
                        <p class="mt-1">
                            <span class="inline-block rounded-full px-3 py-1 text-sm font-semibold
                                @if($deployment->payment_status === 'paid') bg-[#4CAF50]/20 text-[#4CAF50]
                                @elseif($deployment->payment_status === 'partial') bg-[#F5B301]/20 text-[#F5B301]
                                @else bg-white/10 text-[#D1C4E9]
                                @endif">
                                {{ ucfirst($deployment->payment_status ?? 'pending') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Salary Paid Date') }}</label>
                        <p class="text-white">{{ $deployment->salary_paid_date?->format('M d, Y') ?? 'Not yet' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Retained Fee') }}</label>
                        @if($deployment->client_payment !== null && $deployment->maid_salary !== null && $deployment->service_paid !== null)
                            <p class="text-lg font-semibold
                                @if(($deployment->client_payment - $deployment->maid_salary + $deployment->service_paid) >= 0) text-[#4CAF50]
                                @else text-[#E53935]
                                @endif">
                                {{ number_format($deployment->client_payment - $deployment->maid_salary + $deployment->service_paid, 2) }} {{ $deployment->currency ?? 'UGX' }}
                            </p>
                        @else
                            <p class="text-[#D1C4E9]">{{ __('Not calculated') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endcan

        @if($deployment->notes)
            <div class="mt-6 border-t border-[#F5B301]/20 pt-6">
                <h3 class="mb-4 text-lg font-semibold text-white">{{ __('Notes') }}</h3>
                <p class="text-[#D1C4E9]">{{ $deployment->notes }}</p>
            </div>
        @endif

        <!-- Audit Trail -->
        <livewire:components.audit-trail
            :createdBy="$deployment->created_by"
            :updatedBy="$deployment->updated_by"
            :createdAt="$deployment->created_at"
            :updatedAt="$deployment->updated_at"
        />
