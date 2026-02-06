<div class="max-w-4xl mx-auto">
    <div class="bg-[#512B58] rounded-lg shadow border border-[#F5B301]/30">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#F5B301]/30">
            <h2 class="text-2xl font-bold text-white">
                {{ __('Edit Contract') }}
            </h2>
            <p class="text-sm text-[#D1C4E9] mt-1">
                {{ __('Update contract information for this maid.') }}
            </p>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            <form wire:submit="update" class="space-y-6">
                <!-- Maid Selection -->
                <div>
                    <label for="maid_id" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                        {{ __('Maid') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="maid_id" id="maid_id" class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white">
                        <option value="">{{ __('Select a maid') }}</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}">{{ $maid->full_name }} ({{ $maid->maid_code }})</option>
                        @endforeach
                    </select>
                    @error('maid_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contract Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contract_start_date" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                            {{ __('Contract Start Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="contract_start_date" id="contract_start_date" class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white">
                        @error('contract_start_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_end_date" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                            {{ __('Contract End Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="contract_end_date" id="contract_end_date" class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white">
                        @error('contract_end_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contract_status" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                            {{ __('Contract Status') }} <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="contract_status" id="contract_status" class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white">
                            <option value="">{{ __('Select status') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="active">{{ __('Active') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="terminated">{{ __('Terminated') }}</option>
                        </select>
                        @error('contract_status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_type" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                            {{ __('Contract Type') }}
                        </label>
                        <input type="text" wire:model="contract_type" id="contract_type" placeholder="{{ __('e.g., Full-time, Part-time') }}" class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white">
                        @error('contract_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                        {{ __('Notes') }}
                    </label>
                    <textarea wire:model="notes" id="notes" rows="4" placeholder="{{ __('Additional contract notes...') }}" class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white"></textarea>
                    @error('notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contract Documents -->
                <div>
                    <label for="contract_documents" class="block text-sm font-semibold text-[#D1C4E9] mb-2">
                        {{ __('Contract Documents') }}
                    </label>
                    <input
                        type="file"
                        wire:model="contract_documents"
                        id="contract_documents"
                        multiple
                        class="w-full px-4 py-3 border border-[#F5B301]/30 rounded-lg shadow-sm focus:ring-2 focus:ring-[#F5B301] focus:border-[#F5B301] bg-[#3B0A45] text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F5B301]/20 file:text-[#F5B301] hover:file:bg-[#F5B301]/30"
                    />
                    @error('contract_documents.*')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-[#D1C4E9] mt-1">
                        {{ __('PDF, DOC, DOCX, JPG, PNG up to 5MB each.') }}
                    </p>

                    @if (!empty($contract->contract_documents))
                        <div class="mt-3 space-y-2">
                            <p class="text-sm font-semibold text-[#D1C4E9]">
                                {{ __('Existing Documents') }}
                            </p>
                            <ul class="space-y-1">
                                @foreach ($contract->contract_documents as $document)
                                    <li>
                                        <a
                                            href="{{ \Storage::url($document) }}"
                                            target="_blank"
                                            class="text-sm text-[#F5B301] hover:text-[#F5B301]/90"
                                        >
                                            {{ basename($document) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4 border-t border-[#F5B301]/30">
                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 bg-[#F5B301] hover:bg-[#F5B301]/90 disabled:bg-[#F5B301]/60 text-[#3B0A45] font-semibold rounded-lg transition">
                        <span wire:loading.remove>{{ __('Update Contract') }}</span>
                        <span wire:loading>{{ __('Updating...') }}</span>
                    </button>
                    <a href="{{ route('contracts.show', $contract) }}" class="px-6 py-3 border border-[#F5B301]/40 text-[#F5B301] hover:bg-[#F5B301]/10 font-semibold rounded-lg transition">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
