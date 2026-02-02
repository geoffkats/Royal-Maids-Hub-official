# Changelog — v3.1.25

**Release Date:** February 2, 2026

## Overview
v3.1.25 introduces **admin-managed trainer sidebar permissions** — a comprehensive system for controlling which sidebar items trainers can see and access on individual pages. This release includes granular per-trainer permission grants, route-level enforcement via middleware, an admin UI for managing permissions, and full test coverage.

---

## Features Added

### 1. Trainer Sidebar Permissions System
- **28 sidebar items** across 7 sections (Management, Training & Development, Analytics & Reports, Support & Tickets, CRM, System, Business) are now individually grantable to trainers.
- Admins can assign/revoke permissions per trainer via a dedicated admin page.
- Trainers only see and can access sidebar items they have explicit permission for.
- Dashboard is always visible (no permission required).

### 2. Route-Level Enforcement
- All trainer-accessible routes are wrapped with `trainer-sidebar:{item_key}` middleware.
- Unauthorized trainers receive a 403 Unauthorized response when accessing protected routes.
- Admin users bypass all permission checks.

### 3. Admin UI for Permission Management
- New admin page: `/admin/trainer-permissions` (requires admin role).
- Displays all trainers with searchable dropdown.
- Shows all 28 sidebar items grouped by section.
- Checkboxes indicate which items each trainer can access.
- Save button persists permission changes to database.

### 4. Packages Access for Trainers
- `Packages` item added to the Business section of trainer sidebars (when permission granted).
- `packages` routes moved from admin-only group to trainer-accessible group with `trainer-sidebar:packages` middleware.
- `PackagePolicy` updated to allow trainers with `packages` permission to view/edit packages.

---

## Files Added

### Migrations
- **`database/migrations/2026_02_02_100000_create_trainer_sidebar_permissions_table.php`**
  - Creates `trainer_sidebar_permissions` table with columns:
    - `id` (primary key)
    - `trainer_id` (foreign key to `trainers` table)
    - `sidebar_item` (string, the permission key)
    - `granted_at` (timestamp, when permission was granted)
    - `created_at`, `updated_at`
    - Unique index on `(trainer_id, sidebar_item)` to prevent duplicates
  - Establishes relationship between trainers and their sidebar permissions.

### Models & Registry
- **`app/Models/TrainerSidebarPermission.php`**
  - Eloquent model for `trainer_sidebar_permissions` table.
  - Defines 28 item constants (e.g., `ITEM_MAIDS`, `ITEM_TRAINERS`, `ITEM_PACKAGES`, etc.).
  - `getAllItems()` static method returns registry of all 28 items with labels and descriptions, organized by section.
  - `trainer()` BelongsTo relationship.

- **`app/Models/Trainer.php` (Updated)**
  - Added `sidebarPermissions()` HasMany relationship.
  - Added `hasAccessTo(string $item): bool` helper method to check if a trainer has permission for an item.

### Seeders
- **`database/seeders/TrainerSidebarPermissionSeeder.php`**
  - Seeds default permissions for all existing trainers.
  - Assigns 15 trainer-accessible items by default (excludes admin-only items).
  - Default items: `my_programs`, `my_evaluations`, `deployments`, `weekly_board`, `reports`, `kpi_dashboard`, `tickets`, `tickets_inbox`, `contact_inquiries`, `crm_pipeline`, `crm_leads`, `crm_opportunities`, `crm_activities`, `crm_reports`, `bookings`, `schedule`.

### Middleware
- **`app/Http/Middleware/TrainerSidebarAccess.php`**
  - Validates trainer permissions at route level.
  - Middleware signature: `trainer-sidebar:item_key`.
  - Logic:
    - If no item key provided → passes.
    - If user role is `admin` → passes (explicit bypass).
    - If user role is not `trainer` → passes.
    - If trainer and item key provided → checks `Trainer::hasAccessTo(item_key)`.
    - If permission check fails → aborts with 403 Forbidden.

### Livewire Component
- **`app/Livewire/Admin/Trainers/ManagePermissions.php`**
  - Livewire component for admin permission management UI.
  - Handles trainer search, permission loading, permission saving.
  - `mount()` — user authorization check via policy.
  - `loadPermissions(Trainer $trainer)` — retrieves current permissions for selected trainer.
  - `savePermissions()` — persists permission grants/revokes to database.
  - `getTrainers()` — computed property returns searchable trainer list.
  - `groupItemsBySection()` — organizes 28 items into 7 sections for UI display.

### Views
- **`resources/views/livewire/admin/trainers/manage-permissions.blade.php`**
  - Admin UI for permission management.
  - Displays trainer search box, 7 section groups, checkboxes for each item.
  - Shows item labels and descriptions.
  - Save button with wire:click="savePermissions".
  - Success/error message flash display.
  - Info box stating "Dashboard is always visible".

### Tests
- **`tests/Feature/Admin/TrainerPermissionsTest.php`**
  - 11 comprehensive feature tests covering:
    1. Admin can access trainer permissions management page.
    2. Trainer cannot access trainer permissions management page.
    3. Client cannot access trainer permissions management page.
    4. Admin can grant permission to trainer.
    5. Admin can revoke permission from trainer.
    6. Trainer with permission can access sidebar item route.
    7. Trainer without permission cannot access sidebar item route (403).
    8. `hasAccessTo()` returns correct boolean.
    9. Admin can access all sidebar items regardless of permissions.
    10. Admin can search and filter trainers.
    11. Sidebar items are displayed correctly for trainers with permissions.
  - Uses `RefreshDatabase` trait for test isolation.
  - Tests run against isolated `royalmaidshub_test` database.

### Configuration
- **`.env.testing`** (New)
  - Test environment configuration.
  - Points to separate test database `royalmaidshub_test`.
  - Prevents test runs from overwriting development database.

- **`phpunit.xml` (Updated)**
  - Updated `DB_DATABASE` environment variable to `royalmaidshub_test`.
  - Ensures tests run in isolated environment.

---

## Files Modified

### Bootstrap
- **`bootstrap/app.php`**
  - Added middleware alias registration:
    ```php
    'trainer-sidebar' => \App\Http\Middleware\TrainerSidebarAccess::class,
    ```

### Routes
- **`routes/web.php`**
  - Moved `packages` routes from admin-only group to trainer-accessible group.
  - Wrapped with `trainer-sidebar:packages` middleware:
    ```php
    Route::prefix('packages')
        ->middleware(['trainer-sidebar:packages'])
        ->group(function () {
            Route::get('/', \App\Livewire\Packages\Index::class)->name('packages.index');
            Route::get('create', \App\Livewire\Packages\Create::class)->name('packages.create');
            Route::get('{package}/edit', \App\Livewire\Packages\Edit::class)->name('packages.edit');
        });
    ```
  - All trainer-accessible route groups now wrapped with appropriate `trainer-sidebar:{item_key}` middleware.
  - Added admin route for permission management:
    ```php
    Route::get('/trainer-permissions', \App\Livewire\Admin\Trainers\ManagePermissions::class)->name('admin.trainer-permissions');
    ```

### Views
- **`resources/views/components/layouts/app/sidebar.blade.php`**
  - Added conditional rendering for trainer sidebar sections based on `hasAccessTo()` checks.
  - Trainer Management section now conditional on items: `maids`, `trainers`, `clients`, `bookings`.
  - Analytics & Reports, Support & Tickets, CRM sections conditional based on permissions.
  - Business section (with Packages) now conditional on `packages` permission.
  - Each item inside conditional sections also has individual permission checks.
  - Admin sidebar unchanged (shows all items).

### Models
- **`app/Models/Trainer.php`**
  - Added `sidebarPermissions()` HasMany relationship.
  - Added `hasAccessTo(string $item): bool` public method.

### Policies
- **`app/Policies/PackagePolicy.php`**
  - Updated `viewAny(User $user)` — now allows trainers with `packages` permission.
  - Updated `view(User $user, Package $package)` — now allows trainers with `packages` permission.
  - Update/delete/create still restricted to admins only.

### Composer & Dependencies
- **`composer.json` & `composer.lock`**
  - No new dependencies added (uses existing Laravel packages).
  - (Lock file updated with any resolved version conflicts during local development.)

---

## Database Changes

### New Table: `trainer_sidebar_permissions`
```sql
CREATE TABLE trainer_sidebar_permissions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    trainer_id BIGINT UNSIGNED NOT NULL,
    sidebar_item VARCHAR(255) NOT NULL,
    granted_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_trainer_item (trainer_id, sidebar_item)
);
```

### Seeded Data
- `TrainerSidebarPermissionSeeder` creates 75 rows (5 trainers × 15 default items each) on first run.

---

## Authorization & Access Control

### Sidebar Visibility
- Trainers see items in sidebar only if they have explicit permission via `Trainer::hasAccessTo()`.
- Each section in the trainer sidebar is conditionally rendered based on at least one item's permission.

### Route Access
- Trainer-accessible routes wrapped with `trainer-sidebar:{item_key}` middleware.
- Middleware aborts with 403 if trainer lacks permission.
- Admin users bypass all permission checks.
- Unauthorized trainers cannot be bypassed even with direct URL access.

### Policy-Level Authorization
- `PackagePolicy::viewAny()` and `view()` updated to check trainer permissions.
- Component `mount()` calls `$this->authorize()` using policy.

---

## Upgrade Instructions

### 1. Pull Latest Code
```bash
git pull origin main
```

### 2. Run Migrations
```bash
php artisan migrate --force
```

### 3. Seed Default Permissions (Optional)
```bash
php artisan db:seed --class=TrainerSidebarPermissionSeeder
```
Or manually insert rows into `trainer_sidebar_permissions` table.

### 4. Rebuild Frontend Assets
```bash
npm install
npm run build
```

### 5. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:cache  # If using route caching
```

### 6. Log in & Verify
- Visit `/admin/trainer-permissions` as admin to assign permissions.
- Log in as trainer and verify sidebar items are visible and accessible.

---

## Testing

### Run Tests Locally
```bash
php artisan test tests/Feature/Admin/TrainerPermissionsTest.php
```

### Expected Result
```
PASS  Tests\Feature\Admin\TrainerPermissionsTest
✓ 11 passed (20 assertions)
Duration: ~30s
```

---

## Known Limitations & Future Enhancements

### Current Behavior
- Permissions are per-trainer (no role-based groups yet).
- Dashboard always visible (hardcoded).
- No audit logging for permission changes.
- No permission caching (each check queries database).

### Potential Future Enhancements
1. Cache `hasAccessTo()` results for performance.
2. Add audit trail for permission grants/revokes.
3. Implement role-based permission groups (e.g., "Trainer Lead", "Junior Trainer").
4. Add bulk permission assignment UI.
5. Extend permission system to other roles (client-specific features, etc.).

---

## Rollback Instructions

If issues arise in production:

### Rollback Code
```bash
git revert HEAD~1  # Revert the v3.1.25 commit
git push origin main
```

### Rollback Database
```bash
php artisan migrate:rollback --step=1
```

This removes the `trainer_sidebar_permissions` table. All data is safe; existing trainers will have unrestricted access after rollback.

---

## Summary Statistics

| Category | Count |
|----------|-------|
| Files Added | 8 |
| Files Modified | 8 |
| Migrations | 1 |
| Models | 2 (created/updated) |
| Livewire Components | 1 |
| Views | 2 (created/updated) |
| Middleware | 1 |
| Tests | 11 (all passing) |
| Sidebar Items | 28 |
| Default Permissions per Trainer | 15 |

---

## Contributors & Timeline

- **Start Date:** Early February 2026
- **Development Duration:** Multi-phase iterative work
- **Key Phases:**
  1. Initial implementation (DB, model, seeder, middleware)
  2. Admin UI & sidebar visibility
  3. Route authorization & packages access
  4. Testing & refinement
  5. Tag & release (v3.0 baseline, v3.1.25 with new features)

---

## Support & Questions

For issues or questions about v3.1.25:
1. Check `/docs/RELEASE_NOTES_v3.1.25.md` for deployment steps.
2. Review `tests/Feature/Admin/TrainerPermissionsTest.php` for usage examples.
3. Inspect `app/Livewire/Admin/Trainers/ManagePermissions.php` for admin UI logic.
4. Check `app/Http/Middleware/TrainerSidebarAccess.php` for route enforcement behavior.
