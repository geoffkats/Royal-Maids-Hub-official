<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Edit Booking #') . $booking->id }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Update comprehensive booking details') }}</flux:subheading>
        </div>
        <flux:button as="a" :href="route('bookings.show', $booking)" variant="ghost" icon="arrow-left">
            {{ __('Back') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit="update" class="space-y-6">
        {{-- Section 1: Contact Information --}}
        <div class="rounded-lg border border-indigo-200 bg-white p-6 shadow-sm dark:border-indigo-900/50 dark:bg-neutral-800">
            <div class="mb-4 flex items-center gap-2 border-b border-indigo-200 pb-3 dark:border-indigo-900/50">
                <flux:icon.user class="size-5 text-indigo-600 dark:text-indigo-400" />
                <flux:heading size="lg" class="text-indigo-900 dark:text-indigo-300">{{ __('Contact Information') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <flux:input wire:model="full_name" :label="__('Full Name')" :required="true" />
                    @error('full_name') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="phone" :label="__('Phone Number')" type="tel" :required="true" />
                    @error('phone') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="email" :label="__('Email')" type="email" :required="true" />
                    @error('email') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="country" :label="__('Country')" :required="true" />
                    @error('country') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="city" :label="__('City')" :required="true" />
                    @error('city') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="division" :label="__('Division')" :required="true" />
                    @error('division') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:input wire:model="parish" :label="__('Parish')" />
                    @error('parish') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('National ID / Passport') }}</flux:label>
                    @if($existing_national_id_path)
                        <div class="mt-2 mb-3 flex items-center gap-3 rounded-lg border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900">
                            <flux:icon.document-text class="size-5 text-neutral-500" />
                            <span class="text-sm text-neutral-700 dark:text-neutral-300">{{ __('Current document on file') }}</span>
                            <a href="{{ \Storage::url($existing_national_id_path) }}" target="_blank" class="ml-auto text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ __('View') }}
                            </a>
                        </div>
                    @endif
                    <flux:input wire:model="national_id" type="file" accept=".pdf,.jpg,.jpeg,.png" />
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Upload new file to replace existing (PDF, JPG, PNG - Max 2MB)') }}</p>
                    @error('national_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2: Home & Environment --}}
        <div class="rounded-lg border border-green-200 bg-white p-6 shadow-sm dark:border-green-900/50 dark:bg-neutral-800">
            <div class="mb-4 flex items-center gap-2 border-b border-green-200 pb-3 dark:border-green-900/50">
                <flux:icon.home class="size-5 text-green-600 dark:text-green-400" />
                <flux:heading size="lg" class="text-green-900 dark:text-green-300">{{ __('Home & Environment') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <flux:select wire:model="home_type" :label="__('Home Type')" :required="true">
                        <option value="">{{ __('Select home type') }}</option>
                        <option value="house">{{ __('House') }}</option>
                        <option value="apartment">{{ __('Apartment') }}</option>
                        <option value="villa">{{ __('Villa') }}</option>
                        <option value="mansion">{{ __('Mansion') }}</option>
                        <option value="bungalow">{{ __('Bungalow') }}</option>
                    </flux:select>
                    @error('home_type') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="bedrooms" :label="__('Number of Bedrooms')" type="number" min="1" :required="true" />
                    @error('bedrooms') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="bathrooms" :label="__('Number of Bathrooms')" type="number" min="1" :required="true" />
                    @error('bathrooms') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('Outdoor Responsibilities') }}</flux:label>
                    <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <flux:checkbox wire:model="outdoor_responsibilities" value="garden">{{ __('Garden') }}</flux:checkbox>
                        <flux:checkbox wire:model="outdoor_responsibilities" value="lawn">{{ __('Lawn') }}</flux:checkbox>
                        <flux:checkbox wire:model="outdoor_responsibilities" value="pool">{{ __('Pool') }}</flux:checkbox>
                        <flux:checkbox wire:model="outdoor_responsibilities" value="pets">{{ __('Pets') }}</flux:checkbox>
                    </div>
                    @error('outdoor_responsibilities') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('Appliances in Home') }}</flux:label>
                    <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <flux:checkbox wire:model="appliances" value="washing_machine">{{ __('Washing Machine') }}</flux:checkbox>
                        <flux:checkbox wire:model="appliances" value="dryer">{{ __('Dryer') }}</flux:checkbox>
                        <flux:checkbox wire:model="appliances" value="dishwasher">{{ __('Dishwasher') }}</flux:checkbox>
                        <flux:checkbox wire:model="appliances" value="microwave">{{ __('Microwave') }}</flux:checkbox>
                        <flux:checkbox wire:model="appliances" value="oven">{{ __('Oven') }}</flux:checkbox>
                        <flux:checkbox wire:model="appliances" value="vacuum">{{ __('Vacuum Cleaner') }}</flux:checkbox>
                    </div>
                    @error('appliances') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>
        </div>

        {{-- Section 3: Household Composition --}}
        <div class="rounded-lg border border-purple-200 bg-white p-6 shadow-sm dark:border-purple-900/50 dark:bg-neutral-800">
            <div class="mb-4 flex items-center gap-2 border-b border-purple-200 pb-3 dark:border-purple-900/50">
                <flux:icon.user-group class="size-5 text-purple-600 dark:text-purple-400" />
                <flux:heading size="lg" class="text-purple-900 dark:text-purple-300">{{ __('Household Composition') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <flux:input wire:model="adults" :label="__('Number of Adults')" type="number" min="1" :required="true" />
                    @error('adults') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:label>{{ __('Do you have children?') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 flex gap-4">
                        <flux:radio wire:model.live="has_children" value="Yes">{{ __('Yes') }}</flux:radio>
                        <flux:radio wire:model.live="has_children" value="No">{{ __('No') }}</flux:radio>
                    </div>
                    @error('has_children') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                @if($has_children === 'Yes')
                <div class="md:col-span-2">
                    <flux:input wire:model="children_ages" :label="__('Ages of Children')" placeholder="e.g., 3, 7, 12" />
                    @error('children_ages') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                @endif

                <div>
                    <flux:label>{{ __('Elderly persons in home?') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 flex gap-4">
                        <flux:radio wire:model.live="has_elderly" value="Yes">{{ __('Yes') }}</flux:radio>
                        <flux:radio wire:model.live="has_elderly" value="No">{{ __('No') }}</flux:radio>
                    </div>
                    @error('has_elderly') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:label>{{ __('Do you have pets?') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 flex gap-4">
                        <flux:radio wire:model.live="pets" value="Yes">{{ __('Yes') }}</flux:radio>
                        <flux:radio wire:model.live="pets" value="No">{{ __('No') }}</flux:radio>
                    </div>
                    @error('pets') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                @if($pets === 'Yes')
                <div class="md:col-span-2">
                    <flux:input wire:model="pet_kind" :label="__('What kind of pets?')" placeholder="e.g., Dog, Cat" />
                    @error('pet_kind') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                @endif

                <div>
                    <flux:select wire:model="language" :label="__('Preferred Language')" :required="true">
                        <option value="">{{ __('Select language') }}</option>
                        <option value="english">{{ __('English') }}</option>
                        <option value="luganda">{{ __('Luganda') }}</option>
                        <option value="swahili">{{ __('Swahili') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </flux:select>
                    @error('language') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                @if($language === 'other')
                <div>
                    <flux:input wire:model="language_other" :label="__('Specify Language')" />
                    @error('language_other') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                @endif
            </div>
        </div>

        {{-- Section 4: Job Expectations --}}
        <div class="rounded-lg border border-amber-200 bg-white p-6 shadow-sm dark:border-amber-900/50 dark:bg-neutral-800">
            <div class="mb-4 flex items-center gap-2 border-b border-amber-200 pb-3 dark:border-amber-900/50">
                <flux:icon.clipboard-document-list class="size-5 text-amber-600 dark:text-amber-400" />
                <flux:heading size="lg" class="text-amber-900 dark:text-amber-300">{{ __('Job Role & Expectations') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <flux:label>{{ __('Service Tier') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 space-y-2">
                        <flux:radio wire:model="service_tier" value="silver">{{ __('Silver - Basic service') }}</flux:radio>
                        <flux:radio wire:model="service_tier" value="gold">{{ __('Gold - Standard service') }}</flux:radio>
                        <flux:radio wire:model="service_tier" value="platinum">{{ __('Platinum - Premium service') }}</flux:radio>
                    </div>
                    @error('service_tier') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:label>{{ __('Service Mode') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 space-y-2">
                        <flux:radio wire:model="service_mode" value="live-in">{{ __('Live-in') }}</flux:radio>
                        <flux:radio wire:model="service_mode" value="live-out">{{ __('Live-out') }}</flux:radio>
                    </div>
                    @error('service_mode') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('Work Days') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <flux:checkbox wire:model="work_days" value="monday">{{ __('Monday') }}</flux:checkbox>
                        <flux:checkbox wire:model="work_days" value="tuesday">{{ __('Tuesday') }}</flux:checkbox>
                        <flux:checkbox wire:model="work_days" value="wednesday">{{ __('Wednesday') }}</flux:checkbox>
                        <flux:checkbox wire:model="work_days" value="thursday">{{ __('Thursday') }}</flux:checkbox>
                        <flux:checkbox wire:model="work_days" value="friday">{{ __('Friday') }}</flux:checkbox>
                        <flux:checkbox wire:model="work_days" value="saturday">{{ __('Saturday') }}</flux:checkbox>
                        <flux:checkbox wire:model="work_days" value="sunday">{{ __('Sunday') }}</flux:checkbox>
                    </div>
                    @error('work_days') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:input wire:model="working_hours" :label="__('Working Hours')" placeholder="e.g., 8:00 AM - 5:00 PM" />
                    @error('working_hours') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('Responsibilities') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <flux:checkbox wire:model="responsibilities" value="cleaning">{{ __('Cleaning') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="cooking">{{ __('Cooking') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="laundry">{{ __('Laundry') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="childcare">{{ __('Childcare') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="elderly_care">{{ __('Elderly Care') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="pet_care">{{ __('Pet Care') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="shopping">{{ __('Shopping') }}</flux:checkbox>
                        <flux:checkbox wire:model="responsibilities" value="driving">{{ __('Driving') }}</flux:checkbox>
                    </div>
                    @error('responsibilities') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="cuisine_type" :label="__('Cuisine Type')" placeholder="e.g., Local, International" />
                    @error('cuisine_type') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="atmosphere" :label="__('Preferred Atmosphere')" :required="true">
                        <option value="">{{ __('Select atmosphere') }}</option>
                        <option value="formal">{{ __('Formal') }}</option>
                        <option value="casual">{{ __('Casual') }}</option>
                        <option value="flexible">{{ __('Flexible') }}</option>
                    </flux:select>
                    @error('atmosphere') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('Can the maid manage their own tasks?') }} <span class="text-red-500">*</span></flux:label>
                    <div class="mt-2 flex gap-4">
                        <flux:radio wire:model="manage_tasks" value="Yes">{{ __('Yes') }}</flux:radio>
                        <flux:radio wire:model="manage_tasks" value="No">{{ __('No') }}</flux:radio>
                    </div>
                    @error('manage_tasks') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:textarea wire:model="unspoken_rules" :label="__('Unspoken Rules or Special Instructions')" rows="3" placeholder="{{ __('Any house rules or special requirements...') }}" />
                    @error('unspoken_rules') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:textarea wire:model="anything_else" :label="__('Anything Else We Should Know?')" rows="3" placeholder="{{ __('Additional information or requirements...') }}" />
                    @error('anything_else') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>
        </div>

        {{-- Section 5: Booking Management (Admin) --}}
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="mb-4 flex items-center gap-2">
                <flux:icon.cog-6-tooth class="size-5 text-neutral-600 dark:text-neutral-400" />
                <flux:heading size="lg">{{ __('Booking Management') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <flux:select wire:model="maid_id" :label="__('Assigned Maid')">
                        <option value="">{{ __('No maid assigned') }}</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}">
                                {{ $maid->full_name }} - {{ ucfirst($maid->status) }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('maid_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="status" :label="__('Status')" :required="true">
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="approved">{{ __('Approved') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                        <option value="confirmed">{{ __('Confirmed') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="completed">{{ __('Completed') }}</option>
                        <option value="cancelled">{{ __('Cancelled') }}</option>
                    </flux:select>
                    @error('status') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="start_date" :label="__('Start Date')" type="date" :required="true" />
                    @error('start_date') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input wire:model="end_date" :label="__('End Date')" type="date" />
                    @error('end_date') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div class="md:col-span-2">
                    <flux:textarea wire:model="notes" :label="__('Admin Notes')" rows="3" placeholder="{{ __('Internal notes...') }}" />
                    @error('notes') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3">
            <flux:button as="a" :href="route('bookings.show', $booking)" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="update">{{ __('Update Booking') }}</span>
                <span wire:loading wire:target="update" class="flex items-center gap-2">
                    <flux:icon.arrow-path class="size-4 animate-spin" />
                    {{ __('Updating...') }}
                </span>
            </flux:button>
        </div>
    </form>
</div>
