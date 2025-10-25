<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('packages.index') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200">
                ← Back
            </a>
        </div>
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Create New Package</h2>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Add a new subscription package with pricing and features</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{-- Basic Information --}}
        <div class="form-section">
            <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">
                        Package Name *
                    </label>
                    <input type="text" wire:model="name" 
                           class="form-input"
                           placeholder="e.g., Silver, Gold, Platinum">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Tier *
                    </label>
                    <input type="text" wire:model="tier" 
                           class="form-input"
                           placeholder="e.g., Standard, Premium, Elite">
                    @error('tier') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Sort Order *
                    </label>
                    <input type="number" wire:model="sort_order" 
                           class="form-input"
                           min="0">
                    @error('sort_order') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="checkbox">
                        <span class="text-sm font-medium text-[color:var(--text-secondary)]">Active Package</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="form-section">
            <h3 class="text-lg font-semibold text-white mb-4">Pricing Structure</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">
                        Base Price (UGX) *
                    </label>
                    <input type="number" wire:model="base_price" 
                           class="form-input"
                           placeholder="300000" min="0" step="1000">
                    @error('base_price') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Base Family Size *
                    </label>
                    <input type="number" wire:model="base_family_size" 
                           class="form-input"
                           min="1">
                    @error('base_family_size') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Additional Member Cost (UGX) *
                    </label>
                    <input type="number" wire:model="additional_member_cost" 
                           class="form-input"
                           placeholder="35000" min="0" step="1000">
                    @error('additional_member_cost') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Training --}}
        <div class="form-section">
            <h3 class="text-lg font-semibold text-white mb-4">Training Program</h3>
            
            <div class="mb-4">
                <label class="form-label">
                    Training Duration (Weeks) *
                </label>
                <input type="number" wire:model="training_weeks" 
                       class="form-input"
                       min="1" placeholder="4">
                @error('training_weeks') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="form-label">
                    Training Includes
                </label>
                <div class="flex gap-2 mb-2">
                    <input type="text" wire:model="newTraining" wire:keydown.enter.prevent="addTraining"
                           class="form-input flex-1"
                           placeholder="e.g., Advanced Childcare">
                    <button type="button" wire:click="addTraining" 
                            class="btn btn-primary">
                        Add
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($training_includes as $index => $training)
                        <div class="flex items-center gap-2 p-2 rounded" style="background: rgba(245, 179, 1, 0.08)">
                            <span class="flex-1 text-sm text-white">{{ $training }}</span>
                            <button type="button" wire:click="removeTraining({{ $index }})" 
                                    class="text-[color:var(--color-brand-error)] hover:opacity-80 text-sm">Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Support & Benefits --}}
        <div class="form-section">
            <h3 class="text-lg font-semibold text-white mb-4">Support & Benefits</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">
                        Backup Days per Year *
                    </label>
                    <input type="number" wire:model="backup_days_per_year" 
                           class="form-input"
                           min="0" placeholder="21">
                    @error('backup_days_per_year') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Free Replacements *
                    </label>
                    <input type="number" wire:model="free_replacements" 
                           class="form-input"
                           min="0" placeholder="2">
                    @error('free_replacements') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Evaluations per Year *
                    </label>
                    <input type="number" wire:model="evaluations_per_year" 
                           class="form-input"
                           min="0" placeholder="3">
                    @error('evaluations_per_year') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Features --}}
        <div class="form-section">
            <h3 class="text-lg font-semibold text-white mb-4">Package Features</h3>
            
            <div class="flex gap-2 mb-2">
                <input type="text" wire:model="newFeature" wire:keydown.enter.prevent="addFeature"
                       class="form-input flex-1"
                       placeholder="e.g., 24/7 customer support">
                <button type="button" wire:click="addFeature" 
                        class="btn btn-primary">
                    Add Feature
                </button>
            </div>
            <div class="space-y-2">
                @foreach($features as $index => $feature)
                    <div class="flex items-center gap-2 p-2 rounded" style="background: rgba(245, 179, 1, 0.08)">
                        <span class="flex-1 text-sm text-white">✓ {{ $feature }}</span>
                        <button type="button" wire:click="removeFeature({{ $index }})" 
                                class="text-[color:var(--color-brand-error)] hover:opacity-80 text-sm">Remove</button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit" 
                    class="btn btn-primary">
                Create Package
            </button>
            <a href="{{ route('packages.index') }}" 
               class="btn btn-outline">
                Cancel
            </a>
        </div>
    </form>
</div>
