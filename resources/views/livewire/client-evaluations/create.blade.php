<div class="min-h-screen bg-[#3B0A45] dark:bg-[#3B0A45] p-6">
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FF9800] rounded-lg flex items-center justify-center">
                        <flux:icon.clipboard-document-check class="w-6 h-6 text-[#3B0A45]" />
                    </div>
                    <div>
                        <flux:heading size="xl" class="text-white">{{ __('New Client Evaluation') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">{{ __('Capture client feedback and follow-up timelines') }}</flux:subheading>
                    </div>
                </div>

                <flux:button as="a" :href="route('client-evaluations.index')" variant="ghost" icon="arrow-left" class="bg-[#F5B301]/10 hover:bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/30">
                    {{ __('Back') }}
                </flux:button>
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <flux:heading size="lg" class="mb-4 text-white">{{ __('Evaluation Details') }}</flux:heading>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:select wire:model.defer="client_id" :label="__('Client')" required>
                        <option value="">{{ __('Select Client') }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->contact_person }} ({{ $client->user?->email }})</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.defer="booking_id" :label="__('Booking (Optional)')">
                        <option value="">{{ __('Select Booking') }}</option>
                        @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}">#{{ $booking->id }} - {{ $booking->client?->contact_person }}</option>
                        @endforeach
                    </flux:select>

                    @if(auth()->user()->role === 'admin')
                        <flux:select wire:model.defer="trainer_id" :label="__('Trainer (Optional)')">
                            <option value="">{{ __('Assign Trainer') }}</option>
                            @foreach ($trainers as $trainer)
                                <option value="{{ $trainer->id }}">{{ $trainer->user?->name }}</option>
                            @endforeach
                        </flux:select>
                    @endif

                    <flux:input type="date" wire:model.defer="evaluation_date" :label="__('Evaluation Date')" required />

                    <flux:select wire:model.live="evaluation_type" :label="__('Evaluation Type')" required>
                        <option value="custom">{{ __('Custom') }}</option>
                        <option value="3_months">{{ __('3 Months') }}</option>
                        <option value="6_months">{{ __('6 Months') }}</option>
                        <option value="12_months">{{ __('12 Months') }}</option>
                    </flux:select>

                    <flux:select wire:model.defer="overall_rating" :label="__('Overall Rating')">
                        <option value="">{{ __('Select Rating') }}</option>
                        <option value="1">1 - {{ __('Poor') }}</option>
                        <option value="2">2 - {{ __('Needs Improvement') }}</option>
                        <option value="3">3 - {{ __('Satisfactory') }}</option>
                        <option value="4">4 - {{ __('Good') }}</option>
                        <option value="5">5 - {{ __('Excellent') }}</option>
                    </flux:select>

                    <flux:input type="date" wire:model.defer="next_evaluation_date" :label="__('Next Evaluation Date')" />
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <flux:heading size="lg" class="mb-4 text-white">{{ __('Feedback') }}</flux:heading>

                <div class="space-y-4">
                    <flux:textarea wire:model.defer="strengths" :label="__('Strengths')" rows="3" placeholder="{{ __('Strengths highlighted by the client...') }}" />
                    <flux:textarea wire:model.defer="areas_for_improvement" :label="__('Areas for Improvement')" rows="3" placeholder="{{ __('Areas to improve based on client feedback...') }}" />
                    <flux:textarea wire:model.defer="comments" :label="__('Additional Comments')" rows="4" placeholder="{{ __('Additional notes or observations...') }}" />
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button type="button" as="a" :href="route('client-evaluations.index')" variant="ghost" class="bg-[#F5B301]/10 hover:bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/30">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="bg-gradient-to-r from-[#F5B301] to-[#FF9800] hover:from-[#FF9800] hover:to-[#F5B301] text-[#3B0A45] font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                    {{ __('Create Evaluation') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
