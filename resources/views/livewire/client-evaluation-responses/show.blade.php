<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69] py-8">
    <div class="container mx-auto px-4">
        <div class="space-y-6">
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <flux:heading size="xl" class="text-white">{{ __('Client Feedback') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">
                            {{ __('Booking') }} #{{ $response->booking_id }}
                        </flux:subheading>
                    </div>
                    <flux:button as="a" :href="route('client-feedback.index')" variant="outline" icon="arrow-left" class="border-white/20 text-white hover:bg-white/10">
                        {{ __('Back') }}
                    </flux:button>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                        <flux:heading size="lg" class="text-white">{{ __('Responses') }}</flux:heading>

                        <div class="mt-4 space-y-4">
                            @foreach ($questions as $question)
                                @php
                                    $answer = $response->answers[$question->id] ?? null;
                                @endphp
                                <div class="rounded-lg border border-white/10 bg-white/5 p-4">
                                    <div class="text-sm font-semibold text-white">{{ $question->question }}</div>
                                    <div class="mt-2 text-sm text-[#D1C4E9]">
                                        @if($question->type === 'rating')
                                            {{ $answer ? $answer . ' / 5' : __('No response') }}
                                        @elseif($question->type === 'yes_no')
                                            {{ $answer ? ucfirst($answer) : __('No response') }}
                                        @else
                                            {{ $answer ?: __('No response') }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($response->general_comments)
                        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                            <flux:heading size="lg" class="text-white">{{ __('Additional Comments') }}</flux:heading>
                            <p class="mt-3 text-sm text-[#D1C4E9]">{{ $response->general_comments }}</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                        <flux:heading size="lg" class="text-white">{{ __('Client') }}</flux:heading>
                        <div class="mt-3 text-sm text-[#D1C4E9]">
                            <div class="font-semibold text-white">{{ $response->client?->contact_person ?? $response->client?->user?->name ?? '—' }}</div>
                            <div>{{ $response->client?->user?->email ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                        <flux:heading size="lg" class="text-white">{{ __('Maid') }}</flux:heading>
                        <div class="mt-3 text-sm text-[#D1C4E9]">
                            <div class="font-semibold text-white">{{ $response->maid?->full_name ?? '—' }}</div>
                            <div>{{ $response->maid?->maid_code ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                        <flux:heading size="lg" class="text-white">{{ __('Respondent') }}</flux:heading>
                        <div class="mt-3 text-sm text-[#D1C4E9]">
                            <div class="font-semibold text-white">{{ $response->respondent_name }}</div>
                            <div>{{ $response->respondent_email }}</div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                        <flux:heading size="lg" class="text-white">{{ __('Summary') }}</flux:heading>
                        <div class="mt-3 space-y-2 text-sm text-[#D1C4E9]">
                            <div>{{ __('Overall Rating') }}: {{ $response->overall_rating ? number_format((float) $response->overall_rating, 1) : __('N/A') }}</div>
                            <div>{{ __('Submitted') }}: {{ $response->submitted_at?->format('M d, Y H:i') ?? __('N/A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
