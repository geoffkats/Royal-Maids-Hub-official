# V3.0 Booking System Migration Plan
**From:** V2.0 Plain PHP Multi-Step Form  
**To:** V3.0 Laravel Livewire Architecture  
**Date:** October 24, 2025  
**Status:** Planning Phase

---

## 🎯 Migration Objective

Preserve the complete V2.0 booking workflow (40+ fields, 4-step process) while modernizing the implementation with Laravel Livewire, maintaining all features and improving UX.

---

## 📊 Current State Analysis

### V2.0 (Working System)
✅ **40+ comprehensive data fields** across 4 sections  
✅ Multi-step wizard with progress tracking  
✅ File upload for National ID/Passport  
✅ Real-time JavaScript validation  
✅ Comprehensive client requirement capture  
✅ Admin approval workflow (pending/approved/rejected)  
✅ Detailed booking view with all information  
✅ Search and filter capabilities  
✅ Activity logging for audit trail  

### V3.0 (Current - TOO SIMPLIFIED)
❌ **Only 8 basic fields** (client_id, maid_id, booking_type, dates, amount, notes)  
❌ **Missing 32+ critical fields** from V2.0  
❌ No multi-step form  
❌ No file upload capability  
❌ No comprehensive client information capture  
❌ Missing home environment details  
❌ Missing household composition data  
❌ Missing job role expectations  

---

## 🔄 What We Need to Restore

### Section 1: Contact Information (8 fields)
- full_name
- phone
- email
- country
- city
- division
- parish
- national_id (file upload)

### Section 2: Home & Environment (5 field groups)
- home_type (Apartment/Bungalow/Maisonette/Other)
- bedrooms (number)
- bathrooms (number)
- outdoor_responsibilities (array: Sweeping/Gardening/None)
- appliances (array: Washing Machine, Microwave, Oven, etc.)

### Section 3: Household Composition (7 fields)
- adults (number)
- has_children (Yes/No)
- children_ages (text - conditional)
- has_elderly (Yes/No)
- pets (Yes with duties/Yes no duties/No)
- pet_kind (text - conditional)
- language (English/Luganda/Mix/Other)
- language_other (text - conditional)

### Section 4: Job Role & Expectations (11 field groups)
- service_type (Maid/House Manager/Nanny/Chef/etc.)
- service_tier (Silver/Gold/Platinum)
- service_mode (Live-in/Live-out)
- work_days (array: Monday-Sunday)
- working_hours (text: e.g., "8 AM - 5 PM")
- responsibilities (array: Cleaning/Laundry/Cooking/etc.)
- cuisine_type (Local/Mixed/Other)
- atmosphere (Quiet-Calm/Busy-Fast-paced)
- manage_tasks (Verbal/Written list/Worker initiative)
- unspoken_rules (textarea)
- anything_else (textarea)

### System Fields (Already exist in V3.0)
- status (pending/confirmed/active/completed/cancelled)
- start_date
- end_date
- amount
- created_at
- updated_at

---

## 🏗️ Implementation Plan

### Phase 1: Database Schema Update ✅ PRIORITY
**Create Migration:** `2025_10_24_000000_expand_bookings_table_for_v2_compatibility.php`

**Add all missing columns:**
```php
// Section 1: Contact Information
$table->string('full_name', 100)->nullable()->after('id');
$table->string('phone', 20)->nullable();
$table->string('email', 100)->nullable();
$table->string('country', 50)->nullable();
$table->string('city', 50)->nullable();
$table->string('division', 50)->nullable();
$table->string('parish', 50)->nullable();
$table->string('national_id_path')->nullable(); // File storage path

// Section 2: Home & Environment
$table->string('home_type', 50)->nullable();
$table->integer('bedrooms')->default(0);
$table->integer('bathrooms')->default(0);
$table->text('outdoor_responsibilities')->nullable(); // JSON or comma-separated
$table->text('appliances')->nullable(); // JSON or comma-separated

// Section 3: Household Composition
$table->integer('adults')->default(1);
$table->enum('has_children', ['Yes', 'No'])->nullable();
$table->text('children_ages')->nullable();
$table->enum('has_elderly', ['Yes', 'No'])->nullable();
$table->string('pets', 100)->nullable();
$table->string('pet_kind', 100)->nullable();
$table->string('language', 50)->nullable();
$table->string('language_other', 100)->nullable();

// Section 4: Job Role & Expectations
$table->string('service_type', 50)->nullable(); // Keep for compatibility
$table->string('service_tier', 20)->nullable(); // Silver/Gold/Platinum
$table->string('service_mode', 20)->nullable(); // Live-in/Live-out
$table->text('work_days')->nullable(); // JSON or comma-separated
$table->string('working_hours', 100)->nullable();
$table->text('responsibilities')->nullable(); // JSON or comma-separated
$table->string('cuisine_type', 50)->nullable();
$table->string('atmosphere', 50)->nullable();
$table->string('manage_tasks', 100)->nullable();
$table->text('unspoken_rules')->nullable();
$table->text('anything_else')->nullable();

// Keep existing V3.0 fields
// client_id, maid_id, booking_type, start_date, end_date, status, amount, notes
```

### Phase 2: Update Booking Model
**File:** `app/Models/Booking.php`

**Add to $fillable:**
- All 40+ new fields
- Keep existing fields

**Add $casts:**
```php
'outdoor_responsibilities' => 'array',
'appliances' => 'array',
'work_days' => 'array',
'responsibilities' => 'array',
'has_children' => 'boolean',
'has_elderly' => 'boolean',
```

**Add helper methods:**
- `getFullContactInfo()`
- `getHomeRequirements()`
- `getHouseholdComposition()`
- `getJobExpectations()`
- `hasFileUploaded()`

### Phase 3: Create Multi-Step Livewire Component
**File:** `app/Livewire/Bookings/CreateWizard.php`

**Component Structure:**
```
CreateWizard Component
├── Step 1: Contact Information
│   ├── Name, Phone, Email
│   ├── Location (Country, City, Division, Parish)
│   └── National ID Upload
├── Step 2: Home & Environment
│   ├── Home Type, Bedrooms, Bathrooms
│   ├── Outdoor Responsibilities (checkboxes)
│   └── Appliances (checkboxes)
├── Step 3: Household Composition
│   ├── Adults, Children (conditional ages)
│   ├── Elderly, Pets (conditional kind)
│   └── Language Preference
└── Step 4: Job Role & Expectations
    ├── Service Type, Tier, Mode
    ├── Work Schedule (days + hours)
    ├── Responsibilities (checkboxes)
    ├── Cuisine, Atmosphere, Task Management
    └── Special Notes
```

**Features:**
- Progress bar showing current step (1/4, 2/4, etc.)
- "Next" button with step validation
- "Back" button to previous step
- "Save Draft" option
- Real-time Livewire validation
- File upload with preview
- Conditional field display (e.g., show pet_kind only if has pets)

### Phase 4: Create Wizard Blade Views
**Views Structure:**
```
resources/views/livewire/bookings/
├── create-wizard.blade.php (main container)
├── wizard-steps/
│   ├── step-1-contact.blade.php
│   ├── step-2-home.blade.php
│   ├── step-3-household.blade.php
│   └── step-4-expectations.blade.php
└── partials/
    ├── progress-bar.blade.php
    └── navigation-buttons.blade.php
```

**UI Design:**
- Flux components for consistency
- Gradient headers per section (like Maids module)
- Icon-based section indicators
- Responsive mobile design
- Dark mode support

### Phase 5: Update Bookings Index & Show
**Enhance Index Page:**
- Display comprehensive booking info in table/cards
- Add filters: status, service_type, service_tier, date range
- Search by: full_name, email, phone, service_type
- Status badges with colors
- Quick actions: View, Approve, Reject, Delete
- Bulk actions checkbox

**Enhance Show/Detail Page:**
- 4 information cards matching form sections
- Display all 40+ fields organized by category
- Show uploaded National ID with preview
- Status management dropdown
- Activity log timeline
- Action buttons: Edit, Approve/Reject, Delete, Contact Client

### Phase 6: Admin Workflow Features
**Status Management:**
- Admin can change status: pending → approved/rejected
- Activity logging on every status change
- Optional reason field for rejection

**Approval Workflow:**
```
Client Submits Form
    ↓
Status: Pending (Orange badge)
    ↓
Admin Reviews
    ↓
├─→ Approve → Status: Confirmed (Blue)
│       ↓
│   Maid Matching Process
│       ↓
│   Assign Maid → Status: Active (Green)
│       ↓
│   Service Completion → Status: Completed (Gray)
│
└─→ Reject → Status: Cancelled (Red)
        ↓
    Client Notified
```

**Automated Maid Matching:**
- Algorithm considers:
  - service_type → maid role
  - language → maid mother_tongue/english_proficiency
  - responsibilities → maid experience
  - service_tier → maid experience_years
  - has_children → childcare experience
  - has_elderly → elderly care experience
- Return scored list of matching maids
- Admin can select from suggestions

### Phase 7: Activity Logging System
**Create ActivityLog Model & Migration:**
```php
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->string('action_type', 50); // booking_created, status_changed, etc.
    $table->foreignId('user_id')->nullable()->constrained();
    $table->morphs('loggable'); // Polymorphic relation
    $table->text('description');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->timestamps();
    
    $table->index(['action_type', 'created_at']);
});
```

**Log Events:**
- `booking_created` - New booking submitted
- `booking_viewed` - Admin viewed details
- `booking_status_changed` - Status updated
- `booking_updated` - Information edited
- `booking_deleted` - Booking removed
- `maid_assigned` - Maid matched to booking
- `maid_unassigned` - Maid removed from booking

### Phase 8: Email Notifications (Optional Enhancement)
**Laravel Notifications:**
- BookingCreatedNotification → Client + Admin
- BookingApprovedNotification → Client
- BookingRejectedNotification → Client
- MaidAssignedNotification → Client + Maid

**Queue Configuration:**
- Use Laravel queues for async sending
- Database queue driver for simplicity
- Email templates with branding

### Phase 9: Reports & Analytics
**Booking Reports Dashboard:**
- Total bookings by period
- Status distribution (pie chart)
- Service type breakdown (bar chart)
- Service tier popularity
- Geographic distribution (city/division)
- Approval/rejection rates
- Average response time
- Household composition trends

**Export Features:**
- Export filtered bookings to PDF
- Export to Excel/CSV
- Include all 40+ fields in export

### Phase 10: Testing & Validation
**Test Cases:**
1. ✅ Multi-step form navigation (Next/Back)
2. ✅ Step validation (can't proceed without required fields)
3. ✅ File upload for National ID
4. ✅ Conditional fields display correctly
5. ✅ Form submission creates booking with all fields
6. ✅ Admin can view all submitted data
7. ✅ Admin can approve/reject bookings
8. ✅ Status changes are logged
9. ✅ Maid matching algorithm returns relevant results
10. ✅ Email notifications sent on status change

---

## 📋 Field Mapping: V2.0 → V3.0

| V2.0 Field Name | V3.0 Column Name | Type | Section |
|-----------------|------------------|------|---------|
| full-name | full_name | string(100) | Contact |
| phone | phone | string(20) | Contact |
| email | email | string(100) | Contact |
| country | country | string(50) | Contact |
| city | city | string(50) | Contact |
| division | division | string(50) | Contact |
| parish | parish | string(50) | Contact |
| national-id | national_id_path | string(255) | Contact |
| home-type | home_type | string(50) | Home |
| bedrooms | bedrooms | integer | Home |
| bathrooms | bathrooms | integer | Home |
| outdoor-responsibilities | outdoor_responsibilities | text/json | Home |
| appliances | appliances | text/json | Home |
| adults | adults | integer | Household |
| has-children | has_children | enum | Household |
| children-ages | children_ages | text | Household |
| has-elderly | has_elderly | enum | Household |
| pets | pets | string(100) | Household |
| pet-kind | pet_kind | string(100) | Household |
| language | language | string(50) | Household |
| language-other | language_other | string(100) | Household |
| service-type | service_type | string(50) | Job |
| service-tier | service_tier | string(20) | Job |
| service-mode | service_mode | string(20) | Job |
| work-days | work_days | text/json | Job |
| working-hours | working_hours | string(100) | Job |
| responsibilities | responsibilities | text/json | Job |
| cuisine-type | cuisine_type | string(50) | Job |
| atmosphere | atmosphere | string(50) | Job |
| manage-tasks | manage_tasks | string(100) | Job |
| unspoken-rules | unspoken_rules | text | Job |
| anything-else | anything_else | text | Job |

---

## 🎨 UI/UX Design Decisions

### Design Philosophy
- Follow existing V3.0 Maids module design patterns
- Use Flux components for consistency
- Gradient section headers with icons
- Color-coded progress indicators
- Mobile-first responsive design
- Dark mode support throughout

### Color Scheme (by section)
- **Step 1 Contact**: Blue gradient (`from-blue-50 to-indigo-50`)
- **Step 2 Home**: Green gradient (`from-green-50 to-emerald-50`)
- **Step 3 Household**: Purple gradient (`from-purple-50 to-pink-50`)
- **Step 4 Job**: Orange gradient (`from-orange-50 to-amber-50`)

### Icons (Flux components)
- Step 1: `<x-flux::icon.user />`
- Step 2: `<x-flux::icon.home />`
- Step 3: `<x-flux::icon.user-group />`
- Step 4: `<x-flux::icon.briefcase />`

---

## ⚠️ Breaking Changes & Migration Notes

### Data Migration
If there are existing V3.0 bookings in production:
1. All existing bookings will have NULL values for new fields
2. Old bookings will still work (nullable columns)
3. New bookings will capture full information
4. Consider adding "legacy" flag to distinguish old vs new bookings

### Route Changes
- Keep existing `/bookings/create` route
- Optionally add `/bookings/create-wizard` for new form
- Or replace Create component entirely with CreateWizard

### Policy Updates
- Update BookingPolicy to handle new fields
- Ensure clients can only see their own bookings
- Admin can see/edit all bookings

---

## 📅 Timeline Estimate

| Phase | Duration | Priority |
|-------|----------|----------|
| Phase 1: Database Schema | 1 hour | 🔴 Critical |
| Phase 2: Model Updates | 1 hour | 🔴 Critical |
| Phase 3: Livewire Wizard | 4-6 hours | 🔴 Critical |
| Phase 4: Blade Views | 3-4 hours | 🔴 Critical |
| Phase 5: Index & Show | 2-3 hours | 🟡 High |
| Phase 6: Admin Workflow | 2-3 hours | 🟡 High |
| Phase 7: Activity Logging | 2 hours | 🟢 Medium |
| Phase 8: Email Notifications | 2-3 hours | 🟢 Medium |
| Phase 9: Reports | 4-5 hours | 🟢 Medium |
| Phase 10: Testing | 2-3 hours | 🟡 High |

**Total Estimated Time:** 23-33 hours

---

## ✅ Success Criteria

1. ✅ All 40+ V2.0 fields captured in V3.0
2. ✅ Multi-step wizard with progress tracking works
3. ✅ File upload for National ID functional
4. ✅ Conditional fields show/hide correctly
5. ✅ Form validation works per step
6. ✅ Booking submission creates complete record
7. ✅ Admin can view all information in organized layout
8. ✅ Status workflow (pending → approved/rejected) works
9. ✅ Activity logging captures all actions
10. ✅ Maid matching algorithm provides suggestions
11. ✅ Responsive design works on mobile/tablet/desktop
12. ✅ Dark mode supported throughout
13. ✅ Performance: form loads quickly, no lag on step changes

---

## 🚀 Next Steps

1. **Review & Approval**: Get user confirmation on this plan
2. **Phase 1 Start**: Create database migration for expanded schema
3. **Sequential Implementation**: Work through phases 1-10 in order
4. **Testing**: Test each phase before moving to next
5. **Documentation Update**: Update BOOKING_AND_REPORTS_DOCUMENTATION.md as we progress

---

**Status:** ✅ **PLAN APPROVED - READY TO IMPLEMENT**  
**Next Action:** Create database migration for expanded bookings table

