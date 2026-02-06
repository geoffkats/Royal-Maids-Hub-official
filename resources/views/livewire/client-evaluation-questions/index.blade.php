<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69] py-8">
    <div class="container mx-auto px-4">
        <div class="space-y-6">
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <flux:heading size="xl" class="text-white">{{ __('Client Evaluation Questions') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">
                            {{ __('Manage the question bank used in client feedback forms.') }}
                        </flux:subheading>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <flux:callout variant="success">{{ session('success') }}</flux:callout>
            @endif

            <flux:separator variant="subtle" />

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white">{{ $editingId ? __('Edit Question') : __('Add Question') }}</flux:heading>

                    <div class="mt-4 space-y-4">
                        <flux:input wire:model.defer="question" :label="__('Question')" placeholder="{{ __('How was the service?') }}" />
                        @error('question') <span class="text-sm text-red-300">{{ $message }}</span> @enderror

                        <flux:select wire:model.defer="type" :label="__('Type')">
                            <option value="rating">{{ __('Rating (1-5)') }}</option>
                            <option value="text">{{ __('Text') }}</option>
                            <option value="yes_no">{{ __('Yes / No') }}</option>
                        </flux:select>
                        @error('type') <span class="text-sm text-red-300">{{ $message }}</span> @enderror

                        <flux:input wire:model.defer="sort_order" type="number" min="0" :label="__('Sort Order')" />
                        @error('sort_order') <span class="text-sm text-red-300">{{ $message }}</span> @enderror

                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                                <input type="checkbox" wire:model.defer="is_required" class="rounded border-white/30 bg-white/10" />
                                {{ __('Required') }}
                            </label>
                            <label class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                                <input type="checkbox" wire:model.defer="is_active" class="rounded border-white/30 bg-white/10" />
                                {{ __('Active') }}
                            </label>
                        </div>

                        <div class="flex items-center gap-3">
                            <flux:button wire:click="save" variant="primary" class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58]">
                                {{ $editingId ? __('Update') : __('Add') }}
                            </flux:button>
                            @if ($editingId)
                                <flux:button wire:click="cancelEdit" variant="outline" class="border-white/20 text-white hover:bg-white/10">
                                    {{ __('Cancel') }}
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg lg:col-span-2">
                    <div class="mb-4">
                        <flux:input wire:model.live.debounce.300ms="search" :label="__('Search')" placeholder="{{ __('Search questions...') }}" icon="magnifying-glass" />
                    </div>

                    <div class="overflow-hidden rounded-lg border border-white/10">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#D1C4E9]">{{ __('Question') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#D1C4E9]">{{ __('Type') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#D1C4E9]">{{ __('Required') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#D1C4E9]">{{ __('Active') }}</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @forelse ($questions as $questionRow)
                                    <tr class="transition-colors hover:bg-white/10">
                                        <td class="px-4 py-3 text-sm text-white">{{ $questionRow->question }}</td>
                                        <td class="px-4 py-3 text-sm text-[#D1C4E9]">{{ ucfirst(str_replace('_', ' ', $questionRow->type)) }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <flux:badge size="sm" :color="$questionRow->is_required ? 'green' : 'zinc'">
                                                {{ $questionRow->is_required ? __('Yes') : __('No') }}
                                            </flux:badge>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <flux:badge size="sm" :color="$questionRow->is_active ? 'blue' : 'zinc'">
                                                {{ $questionRow->is_active ? __('Active') : __('Inactive') }}
                                            </flux:badge>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <flux:button wire:click="edit({{ $questionRow->id }})" variant="ghost" size="sm" class="text-white hover:text-[#F5B301]">
                                                {{ __('Edit') }}
                                            </flux:button>
                                            <flux:button wire:click="delete({{ $questionRow->id }})" variant="ghost" size="sm" class="text-red-300 hover:text-red-200">
                                                {{ __('Delete') }}
                                            </flux:button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-[#D1C4E9]">
                                            {{ __('No questions added yet.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
