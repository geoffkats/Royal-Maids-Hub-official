<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ $trainer->user?->name }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Trainer Profile') }}</flux:subheading>
        </div>

        <div class="flex gap-2">
            <flux:button as="a" :href="route($prefix . 'trainers.edit', $trainer)" variant="primary" icon="pencil">
                {{ __('Edit') }}
            </flux:button>
            <flux:button as="a" :href="route($prefix . 'trainers.index')" variant="ghost" icon="arrow-left">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Account Information') }}</flux:heading>
        
        <div class="mb-4 flex items-center gap-4">
            <img src="{{ $trainer->photo_url }}" alt="{{ $trainer->user?->name }}" class="h-20 w-20 rounded-full object-cover ring-1 ring-neutral-200 dark:ring-neutral-700">
            <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Profile Photo') }}</div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Name') }}</flux:label>
                <flux:input value="{{ $trainer->user?->name }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Email') }}</flux:label>
                <flux:input value="{{ $trainer->user?->email }}" disabled />
            </flux:field>
        </div>
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Professional Details') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Specialization') }}</flux:label>
                <flux:input value="{{ $trainer->specialization ?? '-' }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Experience (years)') }}</flux:label>
                <flux:input value="{{ $trainer->experience_years }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Status') }}</flux:label>
                <flux:badge :color="$trainer->status === 'active' ? 'green' : 'zinc'">
                    {{ ucfirst($trainer->status) }}
                </flux:badge>
            </flux:field>
        </div>
        
        @if($trainer->bio)
            <div class="mt-4">
                <flux:label>{{ __('Bio') }}</flux:label>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $trainer->bio }}</p>
            </div>
        @endif
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Metadata') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Created') }}</flux:label>
                <flux:input value="{{ $trainer->created_at?->format('M d, Y H:i') }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Last Updated') }}</flux:label>
                <flux:input value="{{ $trainer->updated_at?->format('M d, Y H:i') }}" disabled />
            </flux:field>
        </div>
    </div>
</div>
