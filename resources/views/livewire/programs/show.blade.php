<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">
                {{ $program->program_type }}
                @if($program->archived)
                    <flux:badge color="zinc" size="sm" class="ml-2">{{ __('Archived') }}</flux:badge>
                @endif
            </flux:heading>
            <flux:subheading class="mt-1">{{ __('Training Program Details') }}</flux:subheading>
        </div>

        <div class="flex gap-2">
            @can('update', $program)
                @unless($program->archived)
                    <flux:button as="a" :href="route('programs.edit', $program)" variant="primary" icon="pencil">
                        {{ __('Edit') }}
                    </flux:button>
                @endunless

                <flux:button 
                    wire:click="toggleArchive" 
                    variant="ghost" 
                    :icon="$program->archived ? 'arrow-uturn-left' : 'archive-box'"
                >
                    {{ $program->archived ? __('Unarchive') : __('Archive') }}
                </flux:button>
            @endcan

            @can('delete', $program)
                <flux:button wire:click="confirmDelete" variant="ghost" icon="trash" class="text-red-600 hover:text-red-700">
                    {{ __('Delete') }}
                </flux:button>
            @endcan

            <flux:button as="a" :href="route('programs.index')" variant="ghost" icon="arrow-left">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="!border-green-200 !bg-green-50 dark:!border-green-900 dark:!bg-green-950">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Program Information') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Program Type') }}</flux:label>
                <flux:input value="{{ $program->program_type }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Status') }}</flux:label>
                <flux:badge 
                    :color="match($program->status) {
                        'scheduled' => 'blue',
                        'in-progress' => 'yellow',
                        'completed' => 'green',
                        'cancelled' => 'red',
                        default => 'zinc'
                    }"
                >
                    {{ ucfirst($program->status) }}
                </flux:badge>
            </flux:field>
        </div>
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Participants') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Trainer') }}</flux:label>
                <div class="text-sm">
                    <a href="{{ route('trainers.show', $program->trainer) }}" wire:navigate class="font-medium text-neutral-900 hover:underline dark:text-white">
                        {{ $program->trainer?->user?->name }}
                    </a>
                    <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $program->trainer?->specialization }}</div>
                </div>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Maid') }}</flux:label>
                <div class="text-sm">
                    <a href="{{ route('maids.show', $program->maid) }}" wire:navigate class="font-medium text-neutral-900 hover:underline dark:text-white">
                        {{ $program->maid?->first_name }} {{ $program->maid?->last_name }}
                    </a>
                    <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ ucfirst($program->maid?->status) }}</div>
                </div>
            </flux:field>
        </div>
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Schedule & Progress') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Start Date') }}</flux:label>
                <flux:input value="{{ $program->start_date?->format('M d, Y') }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('End Date') }}</flux:label>
                <flux:input value="{{ $program->end_date?->format('M d, Y') ?? 'Ongoing' }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Hours Completed') }}</flux:label>
                <flux:input value="{{ $program->hours_completed }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Hours Required') }}</flux:label>
                <flux:input value="{{ $program->hours_required }}" disabled />
            </flux:field>
        </div>

        @php
            $progress = $program->hours_required > 0 ? round(($program->hours_completed / $program->hours_required) * 100) : 0;
        @endphp
        <div class="mt-4">
            <flux:label>{{ __('Progress') }} ({{ $progress }}%)</flux:label>
            <div class="mt-2 h-2 rounded-full bg-neutral-200 dark:bg-neutral-700">
                <div class="h-2 rounded-full bg-green-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        @if($program->notes)
            <div class="mt-4">
                <flux:label>{{ __('Notes') }}</flux:label>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $program->notes }}</p>
            </div>
        @endif
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Metadata') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Created') }}</flux:label>
                <flux:input value="{{ $program->created_at?->format('M d, Y H:i') }}" disabled />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Last Updated') }}</flux:label>
                <flux:input value="{{ $program->updated_at?->format('M d, Y H:i') }}" disabled />
            </flux:field>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <flux:modal name="delete-program" wire:model="showDeleteModal" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Delete Training Program') }}</flux:heading>
            <flux:subheading class="mt-2">
                {{ __('Are you sure you want to delete this training program? This action cannot be undone.') }}
            </flux:subheading>
        </div>

        <div class="flex gap-2 justify-end">
            <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button wire:click="delete" variant="danger">
                {{ __('Delete Program') }}
            </flux:button>
        </div>
    </flux:modal>
</div>
