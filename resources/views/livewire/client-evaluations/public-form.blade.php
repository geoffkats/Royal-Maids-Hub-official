<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69] py-10">
    <div class="container mx-auto px-4">
        <div class="mx-auto max-w-3xl space-y-6">
            <div class="rounded-2xl border border-[#F5B301]/30 bg-white/10 p-8 shadow-2xl backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#F5B301] to-[#FFD700]">
                        <flux:icon.clipboard-document-check class="size-6 text-[#512B58]" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ __('Client Evaluation') }}</h1>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Share your feedback about the service you received.') }}</p>
                    </div>
                </div>
            </div>

            @if ($isExpired)
                <flux:callout variant="warning">
                    {{ __('This evaluation link has expired. Please request a new link from the Royal Maids Hub team.') }}
                </flux:callout>
            @elseif ($isSubmitted)
                <flux:callout variant="success">
                    {{ __('Thank you! Your evaluation has already been submitted.') }}
                </flux:callout>
            @else
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-white">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-xs font-semibold text-[#D1C4E9]">{{ __('Maid') }}</div>
                            <div class="text-lg font-semibold">{{ $booking?->maid?->full_name ?? __('Not assigned') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-[#D1C4E9]">{{ __('Package') }}</div>
                            <div class="text-lg font-semibold">{{ $booking?->package?->name ?? __('Not specified') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-[#D1C4E9]">{{ __('Booking Dates') }}</div>
                            <div class="text-base">
                                {{ $booking?->start_date?->format('M d, Y') ?? __('N/A') }}
                                @if($booking?->end_date)
                                    – {{ $booking->end_date?->format('M d, Y') }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-[#D1C4E9]">{{ __('Booking ID') }}</div>
                            <div class="text-base">#{{ $booking?->id }}</div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submit" class="space-y-6">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h2 class="text-lg font-semibold text-white">{{ __('Your Details') }}</h2>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <flux:input wire:model.defer="respondent_name" :label="__('Full Name')" placeholder="{{ __('Your name') }}" />
                                @error('respondent_name') <span class="text-sm text-red-300">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <flux:input wire:model.defer="respondent_email" :label="__('Email')" placeholder="{{ __('you@email.com') }}" />
                                @error('respondent_email') <span class="text-sm text-red-300">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h2 class="text-lg font-semibold text-white">{{ __('Evaluation Questions') }}</h2>

                        <div class="mt-4 space-y-6">
                            @foreach ($questions as $question)
                                <div>
                                    <div class="text-sm font-semibold text-white">
                                        {{ $question->question }}
                                        @if($question->is_required)
                                            <span class="text-[#F5B301]">*</span>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        @if ($question->type === 'rating')
                                            <div class="flex flex-wrap gap-4">
                                                @foreach ([1, 2, 3, 4, 5] as $rating)
                                                    <label class="flex items-center gap-2 text-sm text-white">
                                                        <input type="radio" wire:model.defer="answers.{{ $question->id }}" value="{{ $rating }}" class="text-[#F5B301]" />
                                                        {{ $rating }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif ($question->type === 'yes_no')
                                            <flux:select wire:model.defer="answers.{{ $question->id }}" :label="__('Select')">
                                                <option value="">{{ __('Choose...') }}</option>
                                                <option value="yes">{{ __('Yes') }}</option>
                                                <option value="no">{{ __('No') }}</option>
                                            </flux:select>
                                        @else
                                            <flux:textarea wire:model.defer="answers.{{ $question->id }}" rows="3" placeholder="{{ __('Your response') }}" />
                                        @endif

                                        @error('answers.' . $question->id)
                                            <span class="text-sm text-red-300">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <flux:textarea wire:model.defer="general_comments" rows="4" :label="__('Additional Comments')" placeholder="{{ __('Any extra feedback for us?') }}" />
                        @error('general_comments') <span class="text-sm text-red-300">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <flux:button variant="primary" class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58]" type="submit">
                            {{ __('Submit Evaluation') }}
                        </flux:button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
