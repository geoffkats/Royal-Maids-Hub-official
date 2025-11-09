<div>
    <!-- Reset Password Button -->
    <button wire:click="openModal" 
            class="inline-flex items-center px-3 py-2 rounded-lg bg-[#F5B301] hover:bg-[#F5B301]/80 text-[#3B0A45] text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
            title="Reset Password">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
        Reset
    </button>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" wire:click.self="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-30" wire:click="closeModal"></div>

            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-[#512B58] border border-[#F5B301]/30 shadow-xl rounded-xl z-[10000]">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#F5B301] rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#3B0A45]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Reset Password</h3>
                            <p class="text-sm text-[#D1C4E9]">for {{ $trainer->user->name }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-[#D1C4E9] hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="resetPassword" class="space-y-6">
                    <!-- New Password -->
                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-[#D1C4E9] mb-2">
                            New Password
                        </label>
                        <input type="password" 
                               id="newPassword"
                               wire:model="newPassword" 
                               class="w-full px-4 py-3 bg-[#3B0A45] border border-[#F5B301]/30 rounded-lg text-white placeholder-[#D1C4E9]/50 focus:border-[#F5B301] focus:ring-1 focus:ring-[#F5B301] transition-colors"
                               placeholder="Enter new password"
                               required>
                        @error('newPassword')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-[#D1C4E9] mb-2">
                            Confirm Password
                        </label>
                        <input type="password" 
                               id="confirmPassword"
                               wire:model="confirmPassword" 
                               class="w-full px-4 py-3 bg-[#3B0A45] border border-[#F5B301]/30 rounded-lg text-white placeholder-[#D1C4E9]/50 focus:border-[#F5B301] focus:ring-1 focus:ring-[#F5B301] transition-colors"
                               placeholder="Confirm new password"
                               required>
                        @error('confirmPassword')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-[#3B0A45]/50 rounded-lg p-4 border border-[#F5B301]/20">
                        <h4 class="text-sm font-medium text-[#D1C4E9] mb-2">Password Requirements:</h4>
                        <ul class="text-xs text-[#D1C4E9]/70 space-y-1">
                            <li>• At least 8 characters long</li>
                            <li>• Mix of letters and numbers recommended</li>
                            <li>• Avoid common passwords</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F5B301]/20">
                        <button type="button" 
                                wire:click="closeModal"
                                class="px-4 py-2 text-[#D1C4E9] hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-[#F5B301] hover:bg-[#F5B301]/80 text-[#3B0A45] font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
