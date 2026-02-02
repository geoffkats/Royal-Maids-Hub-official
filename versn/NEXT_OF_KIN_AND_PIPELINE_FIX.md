# ✅ Fixed: Pipeline Drag & Next of Kin Fields

## Issues Fixed

### 1. ✅ Pipeline Drag Error - Missing `title` Field
**Error**: `Field 'title' doesn't have a default value`

**Cause**: When converting a lead to opportunity, the `title` field was not being set.

**Solution**: Added `title` field to opportunity creation in pipeline board.

### 2. ✅ Next of Kin Fields Added to Client Forms
**Request**: Add Next of Kin information to client forms.

**Solution**: Added 3 new fields to clients table and all client forms.

---

## Changes Made

### 1. Pipeline Board Fix

**File**: `app/Livewire/CRM/Pipeline/Board.php`

**Before**:
```php
$opportunity = Opportunity::create([
    'name' => $lead->full_name . ' - Opportunity',
    'description' => 'Converted from lead: ' . $lead->full_name,
    // ... other fields
]);
```

**After**:
```php
$opportunity = Opportunity::create([
    'title' => $lead->full_name . ' - Opportunity',
    'name' => $lead->full_name . ' - Opportunity',
    'description' => 'Converted from lead: ' . $lead->full_name,
    // ... other fields
]);
```

---

### 2. Next of Kin Fields

#### Database Migration
**File**: `database/migrations/2025_11_08_234256_add_next_of_kin_to_clients_table.php`

**Added 3 fields**:
- `next_of_kin_name` (string, nullable)
- `next_of_kin_phone` (string, nullable)
- `next_of_kin_relationship` (string, nullable)

#### Client Model
**File**: `app/Models/Client.php`

Added to fillable array:
```php
'next_of_kin_name',
'next_of_kin_phone',
'next_of_kin_relationship',
```

#### Client Create Component
**File**: `app/Livewire/Clients/Create.php`

- Added 3 properties
- Added validation rules
- Added to client creation

#### Client Create View
**File**: `resources/views/livewire/clients/create.blade.php`

Added new section:
```blade
<div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
    <div class="flex items-center gap-2 mb-4">
        <flux:icon.user-group class="size-5 text-[#F5B301]" />
        <flux:heading size="md" class="text-white">Next of Kin</flux:heading>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <flux:input wire:model.defer="next_of_kin_name" :label="'Next of Kin Name'" />
        <flux:input wire:model.defer="next_of_kin_phone" :label="'Next of Kin Phone'" />
        <flux:input wire:model.defer="next_of_kin_relationship" :label="'Relationship'" 
                    placeholder="e.g., Spouse, Parent, Sibling" />
    </div>
</div>
```

#### Client Edit Component & View
**Files**: 
- `app/Livewire/Clients/Edit.php`
- `resources/views/livewire/clients/edit.blade.php`

Same changes as Create (properties, validation, form fields).

---

## Client Form Structure Now

### Create/Edit Client Form Sections

```
1. User Account
   ├─ Name
   ├─ Email
   └─ Password

2. Contact Information
   ├─ Contact Person
   ├─ Company Name
   ├─ Phone
   └─ Secondary Phone

3. Next of Kin (NEW!)
   ├─ Next of Kin Name
   ├─ Next of Kin Phone
   └─ Relationship

4. Address & Subscription
   ├─ Address
   ├─ City
   ├─ District
   ├─ Package
   └─ Status
```

---

## Files Updated

### Pipeline Fix
✅ `app/Livewire/CRM/Pipeline/Board.php`

### Next of Kin
✅ `database/migrations/2025_11_08_234256_add_next_of_kin_to_clients_table.php`  
✅ `app/Models/Client.php`  
✅ `app/Livewire/Clients/Create.php`  
✅ `resources/views/livewire/clients/create.blade.php`  
✅ `app/Livewire/Clients/Edit.php`  
✅ `resources/views/livewire/clients/edit.blade.php`  

---

## Testing

### Test Pipeline Drag
1. Go to: http://127.0.0.1:8000/crm/pipeline
2. Drag a qualified lead to any stage
3. ✅ Should create opportunity successfully
4. ✅ No more "title field" error

### Test Next of Kin Fields

#### Create Client
1. Go to: http://127.0.0.1:8000/clients/create
2. Fill in all fields including Next of Kin section
3. Submit
4. ✅ Client created with next of kin info

#### Edit Client
1. Go to: http://127.0.0.1:8000/clients/{id}/edit
2. See Next of Kin section
3. Update fields
4. Submit
5. ✅ Client updated with new next of kin info

---

## Database Schema

### Clients Table (Updated)

```sql
clients
├─ id
├─ user_id
├─ contact_person
├─ phone
├─ secondary_phone
├─ next_of_kin_name          (NEW)
├─ next_of_kin_phone         (NEW)
├─ next_of_kin_relationship  (NEW)
├─ address
├─ city
├─ district
├─ package_id
├─ subscription_tier
├─ subscription_status
└─ ...
```

---

## Summary

**Problem 1**: Pipeline drag failed with "title field" error  
**Solution**: Added `title` field to opportunity creation  

**Problem 2**: No Next of Kin fields in client forms  
**Solution**: Added 3 fields to database, model, and all client forms  

**Status**: ✅ Both issues fixed  
**Migration**: ✅ Run successfully  

---

**Both the pipeline drag and Next of Kin fields are now working!** 🎉
