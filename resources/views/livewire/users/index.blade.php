<div class="space-y-6">
    @php
        $formatRole = fn (string $value) => ucwords(str_replace('_', ' ', $value));
        $roleColors = [
            'super_admin' => 'amber',
            'admin' => 'violet',
            'operations_manager' => 'blue',
            'trainer' => 'sky',
            'finance_officer' => 'green',
            'customer_support' => 'pink',
            'client' => 'zinc',
        ];
    @endphp

    @if (session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
            <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->has('action'))
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-700 text-amber-800 dark:text-amber-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-600 dark:text-amber-400" />
            <span class="font-medium">{{ $errors->first('action') }}</span>
        </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('User Management') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Create staff users, assign roles, and manage access.') }}</flux:subheading>
        </div>

        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            {{ __('New User') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-xl border border-[#F5B301]/25 bg-[#512B58]/60 p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-[#D1C4E9]">{{ __('Total Staff') }}</p>
                    <p class="mt-2 text-2xl font-semibold text-white">{{ $totalUsers }}</p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-[#F5B301]/20 flex items-center justify-center">
                    <flux:icon.users class="size-5 text-[#F5B301]" />
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-[#F5B301]/25 bg-[#512B58]/60 p-5 shadow-lg">
            <p class="text-xs uppercase tracking-wide text-[#D1C4E9]">{{ __('Super Admins') }}</p>
            <p class="mt-2 text-2xl font-semibold text-white">{{ $roleCounts['super_admin'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-[#D1C4E9]">{{ __('Legacy admins have full access.') }}</p>
        </div>
        <div class="rounded-xl border border-[#F5B301]/25 bg-[#512B58]/60 p-5 shadow-lg">
            <p class="text-xs uppercase tracking-wide text-[#D1C4E9]">{{ __('Finance Officers') }}</p>
            <p class="mt-2 text-2xl font-semibold text-white">{{ $roleCounts['finance_officer'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-[#D1C4E9]">{{ __('Financial access only.') }}</p>
        </div>
        <div class="rounded-xl border border-[#F5B301]/25 bg-[#512B58]/60 p-5 shadow-lg">
            <p class="text-xs uppercase tracking-wide text-[#D1C4E9]">{{ __('Support & Ops') }}</p>
            <p class="mt-2 text-2xl font-semibold text-white">
                {{ ($roleCounts['operations_manager'] ?? 0) + ($roleCounts['customer_support'] ?? 0) }}
            </p>
            <p class="mt-2 text-xs text-[#D1C4E9]">{{ __('Operations + Customer Support') }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Users') }}</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <flux:input
                    wire:model.live.debounce.400ms="search"
                    :label="__('Search Users')"
                    placeholder="{{ __('Name or email...') }}"
                    icon="magnifying-glass"
                />
            </div>

            <div>
                <flux:select wire:model.live="roleFilter" :label="__('Role')">
                    <option value="">{{ __('All Roles') }}</option>
                    @foreach ($roles as $roleOption)
                        <option value="{{ $roleOption }}">{{ $formatRole($roleOption) }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div>
                <flux:select wire:model.live="verificationFilter" :label="__('Verification')">
                    <option value="all">{{ __('All') }}</option>
                    <option value="verified">{{ __('Verified') }}</option>
                    <option value="unverified">{{ __('Unverified') }}</option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <flux:select wire:model.live="perPage" :label="__('Results Per Page')">
                    @foreach ([10, 15, 25, 50] as $n)
                        <option value="{{ $n }}">{{ $n }} {{ __('results') }}</option>
                    @endforeach
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="sortBy" :label="__('Sort By')">
                    <option value="created_at">{{ __('Date Added') }}</option>
                    <option value="name">{{ __('Name') }}</option>
                    <option value="email">{{ __('Email') }}</option>
                    <option value="role">{{ __('Role') }}</option>
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="sortDirection" :label="__('Order')">
                    <option value="desc">{{ __('Newest First') }}</option>
                    <option value="asc">{{ __('Oldest First') }}</option>
                </flux:select>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('User') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Role') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Verification') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Created') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}" class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge :color="$roleColors[$user->role] ?? 'zinc'" size="sm">
                                {{ $formatRole($user->role) }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if ($user->is_active)
                                <flux:badge color="green" size="sm">{{ __('Active') }}</flux:badge>
                            @else
                                <flux:badge color="amber" size="sm">{{ __('Deactivated') }}</flux:badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if ($user->email_verified_at)
                                <span class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-300">
                                    <flux:icon.check-circle class="size-4" />
                                    {{ __('Verified') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 text-amber-600 dark:text-amber-300">
                                    <flux:icon.exclamation-triangle class="size-4" />
                                    {{ __('Unverified') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $user->created_at?->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="pencil-square"
                                    title="{{ __('Edit') }}"
                                    wire:click="openEditModal({{ $user->id }})"
                                ></flux:button>
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="lock-closed"
                                    title="{{ __('Reset Password') }}"
                                    wire:click="openResetModal({{ $user->id }})"
                                ></flux:button>
                                <flux:button
                                    variant="outline"
                                    size="sm"
                                    wire:click="openDeactivateModal({{ $user->id }})"
                                    :disabled="$user->role === 'super_admin'"
                                >
                                    {{ $user->is_active ? __('Deactivate') : __('Activate') }}
                                </flux:button>
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    class="!text-red-600 hover:!bg-red-50 dark:!text-red-400 dark:hover:!bg-red-950"
                                    title="{{ __('Delete') }}"
                                    wire:click="openDeleteModal({{ $user->id }})"
                                    :disabled="$user->role === 'super_admin'"
                                ></flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No users found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>

    <flux:modal name="create-user" class="max-w-2xl" wire:model="showCreateModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create User') }}</flux:heading>
                <flux:subheading>{{ __('Assign a role and provide login credentials.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model.defer="name" :label="__('Full Name')" />
                <flux:input wire:model.defer="email" type="email" :label="__('Email')" />
                <flux:select wire:model.defer="role" :label="__('Role')">
                    @foreach ($roles as $roleOption)
                        <option value="{{ $roleOption }}">{{ $formatRole($roleOption) }}</option>
                    @endforeach
                </flux:select>
                <div class="flex items-end">
                    <flux:switch wire:model.defer="emailVerified" :label="__('Email Verified')" />
                </div>
                <flux:input wire:model.defer="password" type="password" :label="__('Password')" />
                <flux:input wire:model.defer="password_confirmation" type="password" :label="__('Confirm Password')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModal">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" wire:click="createUser" wire:loading.attr="disabled">
                    {{ __('Create User') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="edit-user" class="max-w-2xl" wire:model="showEditModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Edit User') }}</flux:heading>
                <flux:subheading>{{ __('Update role, verification, or password.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model.defer="name" :label="__('Full Name')" />
                <flux:input wire:model.defer="email" type="email" :label="__('Email')" />
                <flux:select wire:model.defer="role" :label="__('Role')">
                    @foreach ($roles as $roleOption)
                        <option value="{{ $roleOption }}">{{ $formatRole($roleOption) }}</option>
                    @endforeach
                </flux:select>
                <div class="flex items-end">
                    <flux:switch wire:model.defer="emailVerified" :label="__('Email Verified')" />
                </div>
                <flux:input wire:model.defer="password" type="password" :label="__('New Password (optional)')" />
                <flux:input wire:model.defer="password_confirmation" type="password" :label="__('Confirm Password')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModal">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" wire:click="updateUser" wire:loading.attr="disabled">
                    {{ __('Save Changes') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="reset-password" class="max-w-xl" wire:model="showResetModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Reset Password') }}</flux:heading>
                <flux:subheading>{{ __('Set a new password for') }} {{ $actionUserName }}.</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model.defer="resetPassword" type="password" :label="__('New Password')" />
                <flux:input wire:model.defer="resetPassword_confirmation" type="password" :label="__('Confirm Password')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModal">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" wire:click="resetUserPassword" wire:loading.attr="disabled">
                    {{ __('Reset Password') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="toggle-user" class="max-w-xl" wire:model="showDeactivateModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ $actionUserActive ? __('Deactivate User') : __('Activate User') }}
                </flux:heading>
                <flux:subheading>
                    {{ $actionUserActive
                        ? __('This will prevent the user from logging in.')
                        : __('This will allow the user to log in again.')
                    }}
                </flux:subheading>
            </div>

            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-700/40 dark:bg-amber-900/20 dark:text-amber-200">
                {{ __('User:') }} <span class="font-semibold">{{ $actionUserName }}</span>
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModal">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" wire:click="toggleActive" wire:loading.attr="disabled">
                    {{ $actionUserActive ? __('Deactivate') : __('Activate') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="delete-user" class="max-w-xl" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete User') }}</flux:heading>
                <flux:subheading>{{ __('This action can be reversed by restoring the user from the database.') }}</flux:subheading>
            </div>

            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-700/40 dark:bg-red-900/20 dark:text-red-200">
                {{ __('User:') }} <span class="font-semibold">{{ $actionUserName }}</span>
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModal">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Delete User') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
