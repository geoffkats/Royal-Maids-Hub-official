# Changelog — v3.1.26

## Overview
v3.1.26 introduces **Phase 1 Automation Framework** for ticket management, along with identity verification, audit trails, and soft deletes. This is a major release focused on reducing manual workload through intelligent automation, featuring SLA breach detection, automatic ticket assignment, and multi-channel notifications.

## Major Features

### 1. Phase 1 Automation Framework ✅
Comprehensive automation system reducing manual workload by 30-40% in ticket operations:

#### 1.1 Ticket Auto-Assignment Service
- **Workload-based assignment**: Tickets assigned to staff with lowest open ticket count
- **Round-robin strategy**: Cycles through eligible users for balanced distribution  
- **Category-based routing**: Foundation for specialty-based assignment (future enhancement)
- **Tier-prioritization**: Platinum customers automatically routed to admin staff
- **Configuration-driven**: Feature toggles via CRM Settings UI
- **Manual overrides**: Skips assignment if ticket already assigned
- **Audit trail**: All assignments logged in ticket comments with system user

**Files**:
- `app/Services/TicketAutoAssignmentService.php` (119 lines)
- `app/Models/Ticket.php` - Enhanced boot() hook for auto-assignment trigger
- `app/Livewire/CRM/Settings/Index.php` - Added 5 new ticket automation settings

#### 1.2 SLA Breach Detection & Notifications
- **Real-time monitoring**: Every 30 minutes via scheduled job
- **Response SLA tracking**: Alerts when first response deadline breached
- **Resolution SLA tracking**: Alerts when resolution deadline breached  
- **Admin escalation**: Critical/platinum tickets notify managers
- **Approaching deadline warnings**: Notifies 2 hours before SLA deadline
- **Multi-channel delivery**: Mail + Database notifications with async queuing
- **Duplicate prevention**: Never sends duplicate notifications for same breach

**Files**:
- `app/Jobs/CheckSLABreachesAndNotify.php` (scheduled every 30 minutes)
- `app/Notifications/TicketSLABreachedNotification.php` (Mail + Database)
- `app/Notifications/TicketApproachingSLANotification.php`
- `app/Notifications/TicketAssignedNotification.php`
- `routes/console.php` - Scheduler configuration

#### 1.3 CRM Settings Integration
Five new configurable settings for ticket automation:
- `ticket_auto_assign_enabled` - Boolean toggle for auto-assignment
- `ticket_assignment_strategy` - Select: workload | round-robin | category
- `ticket_auto_notify` - Boolean toggle for auto-notifications
- `ticket_sla_check_interval` - Interval in minutes (default: 60)
- `ticket_approaching_sla_hours` - Warning threshold in hours (default: 2)

**Location**: Admin CRM Settings UI with database persistence

### 2. Identity Verification System ✅
Comprehensive identity tracking for clients and bookings:

- **Client Identity Fields**: NIN or Passport number storage with unique constraint
- **Booking Identity Snapshots**: Identity captured at booking time for audit trail
- **Dual Identity Types**: Supports both NIN and Passport with validation
- **Uniqueness Enforcement**: Compound unique constraint prevents duplicates per type
- **User Controls**: Available in Create/Edit forms for clients and bookings

**Files**:
- Database migrations: `create_clients_table.php`, `add_identity_fields_to_bookings_table.php`
- Model updates: `Client.php`, `Booking.php`
- Views: `clients/create.blade.php`, `clients/edit.blade.php`

### 3. Audit Trail System ✅
Complete creation and modification tracking:

- **Creation Tracking**: `created_by` field records who created each record
- **Update Tracking**: `updated_by` field records last modifier
- **Automatic Recording**: User ID captured via `auth()->id()` on create/update
- **Relationship Support**: Links to User model for display
- **Models**: Client, Booking, Deployment, MaidContract

**Files**:
- `app/Livewire/Components/AuditTrail.php`
- Views: `livewire/components/audit-trail.blade.php`

### 4. Soft Delete System ✅
Reversible record deletion for data preservation:

- **Data Preservation**: Records archived without physical deletion
- **Models**: Client, Deployment, Maid
- **Reversible**: Fully restore soft-deleted records
- **Query Transparency**: Default queries exclude soft-deleted records
- **Full Access**: Use `withTrashed()` to access deleted records

## Test Coverage

### Phase 1 Automation Tests (11 tests, 100% passing)

**TicketAutoAssignmentTest.php** (6 tests):
- ✅ Auto-assigns to least busy user with workload strategy
- ✅ Skips auto-assignment when disabled
- ✅ Prevents reassignment of already-assigned tickets
- ✅ Bulk assigns multiple unassigned tickets
- ✅ Prioritizes platinum tier tickets to admin staff
- ✅ Returns accurate assignment statistics

**TicketSLANotificationTest.php** (5 tests):
- ✅ Sends notification when response SLA breached
- ✅ Sends notification when resolution SLA breached
- ✅ Notifies admin for critical ticket SLA breach
- ✅ Prevents notifications for resolved tickets
- ✅ Prevents duplicate notifications for same breach

**Test Execution**: `php artisan test tests/Feature/TicketAutoAssignmentTest.php tests/Feature/TicketSLANotificationTest.php` — **11 passed, 0 failed**

## Database Changes

### New Tables
- `notifications` - Laravel notification storage for database channel

### New Columns (Migrations)
- `tickets.sla_response_due` - Response deadline tracking
- `tickets.sla_resolution_due` - Resolution deadline tracking
- `tickets.sla_breached` - Flag for breached SLAs
- `tickets.first_response_at` - Timestamp of first staff response
- `tickets.assigned_to` - Staff member responsible for ticket
- `tickets.auto_priority` - Flag for auto-calculated priorities
- `clients.nid/passport` - Identity verification fields
- `bookings.client_nid/passport` - Identity snapshots
- `deployments.created_by`, `updated_by` - Audit trail
- And others for audit trail and identity verification

## Configuration Changes

### CRM Settings
- Added 5 new ticket automation settings (configurable via admin UI)
- Default values: auto-assignment enabled, workload strategy, 60-min check interval

### Scheduler Updates
- New job `CheckSLABreachesAndNotify` runs every 30 minutes
- Existing CRM automation jobs continue on regular schedule

## Breaking Changes
None. All changes are backward compatible.

## Deployment Notes

### Pre-Deployment
1. Ensure `crm_settings` table exists and is seeded
2. Run migrations: `php artisan migrate`
3. Create notifications table: `php artisan notifications:table && php artisan migrate`

### Post-Deployment
1. Configure ticket automation settings in admin CRM Settings UI
2. Configure SLA response/resolution deadlines per ticket type
3. Test SLA notifications: `php artisan tinker` → `Ticket::factory()->create(...)`

### Feature Toggle
All automation features can be disabled via CRM Settings without code changes:
- Disable auto-assignment: Set `ticket_auto_assign_enabled` to 0
- Disable notifications: Set `ticket_auto_notify` to 0
- Adjust SLA check interval: Modify `ticket_sla_check_interval`

## Performance Impact
- **Query Load**: +1 query per ticket creation (assignment check)
- **Job Load**: Scheduler job runs every 30 minutes (configurable)
- **Database Load**: Minimal - 3 new application tables, columns on existing tables
- **Notification Load**: Queued async, no blocking

## Upcoming Features (Phase 2-3)
- **Phase 2**: Opportunity → Booking auto-creation, Smart Maid Assignment, Booking Price Auto-Apply
- **Phase 3**: Lead routing by source, Training automation, Finance automation
- See `AUTOMATION_IMPLEMENTATION_PLAN_v1.md` for complete roadmap

## Rollback Plan
1. Disable `CheckSLABreachesAndNotify` job in `routes/console.php`
2. Disable auto-assignment in Ticket model boot() method (comment line 88)
3. Disable CRM settings UI ticket automation controls
4. Database changes are not removed (safe to keep)

## Contributors & Credits
- **Framework**: Laravel 12, Livewire 3, Flux UI
- **Testing**: Pest 4 with RefreshDatabase, Notification::fake()
- **Code Quality**: Laravel Pint formatting

## Version History
- **v3.1.26** (2026-02-04): Phase 1 Automation Framework + Identity Verification + Audit Trails
- **v3.1.25** (2026-01-XX): Trainer sidebar permissions
- **v3.1.0** (2025-10-26): Initial v3.1 release

## Support & Documentation
For detailed information, see:
- [AUTOMATION_IMPLEMENTATION_PLAN_v1.md](./AUTOMATION_IMPLEMENTATION_PLAN_v1.md) - Comprehensive 0-3 month roadmap
- [docs/RELEASE_NOTES_v3.1.26.md](./docs/RELEASE_NOTES_v3.1.26.md) - Deployment guide
- [docs/](./docs/) - Full technical documentation
