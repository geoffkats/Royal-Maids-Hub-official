<div class="space-y-6">
    <!-- Header Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-full bg-[#F5B301] p-2">
                    <flux:icon.calendar class="size-6 text-[#3B0A45]" />
                </div>
                <div>
                    <flux:heading size="xl" class="text-white">{{ __('Create Booking') }}</flux:heading>
                    <flux:subheading class="text-[#D1C4E9]">{{ __('Create a new booking for a client') }}</flux:subheading>
                </div>
            </div>
            <flux:button as="a" :href="route('bookings.index')" variant="outline" icon="arrow-left">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.calendar class="size-5 text-[#F5B301]" />
                <flux:heading size="lg" class="text-white">{{ __('Booking Information') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- Client Selection --}}
                <div>
                    <flux:select wire:model="client_id" :label="__('Client')" :required="true">
                        <option value="">{{ __('Select Client') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->contact_person }}
                                @if($client->company_name)
                                    ({{ $client->company_name }})
                                @endif
                            </option>
                        @endforeach
                    </flux:select>
                    @error('client_id')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                {{-- Maid Selection --}}
                <div>
                    <flux:select wire:model="maid_id" :label="__('Maid')" :required="true">
                        <option value="">{{ __('Select Maid') }}</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}">
                                {{ $maid->full_name }} - {{ ucfirst(str_replace('_', ' ', $maid->role)) }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('maid_id')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                {{-- Booking Type --}}
                <div>
                    <flux:select wire:model="booking_type" :label="__('Booking Type')" :required="true">
                        <option value="">{{ __('Select Type') }}</option>
                        <option value="brokerage">{{ __('Brokerage') }}</option>
                        <option value="long-term">{{ __('Long-term') }}</option>
                        <option value="part-time">{{ __('Part-time') }}</option>
                        <option value="full-time">{{ __('Full-time') }}</option>
                    </flux:select>
                    @error('booking_type')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <flux:input 
                        wire:model="amount" 
                        :label="__('Amount (UGX)')" 
                        type="number" 
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                    />
                    @error('amount')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <flux:input 
                        wire:model="start_date" 
                        :label="__('Start Date')" 
                        type="date"
                        :required="true"
                    />
                    @error('start_date')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <flux:input 
                        wire:model="end_date" 
                        :label="__('End Date')" 
                        type="date"
                    />
                    @error('end_date')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>
            </div>

            {{-- Notes --}}
            <div class="mt-6">
                <flux:textarea 
                    wire:model="notes" 
                    :label="__('Notes')" 
                    rows="4"
                    placeholder="{{ __('Add any additional notes or requirements...') }}"
                />
                @error('notes')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                    <flux:icon.information-circle class="size-4" />
                    <span>{{ __('Select client and maid, then set booking dates') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <flux:button as="a" :href="route('bookings.index')" variant="outline" icon="arrow-left">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="check">
                        {{ __('Create Booking') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
