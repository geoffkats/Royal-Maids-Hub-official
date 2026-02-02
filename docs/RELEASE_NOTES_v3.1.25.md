# Release v3.1.25 — Trainer permissions & Packages access

Release date: 2026-02-02

Summary
- Adds admin-managed per-trainer sidebar permissions and route-level enforcement.
- Grants trainers granular access to sidebar items (28 items across 7 sections).
- Adds `Packages` visibility and route access for trainers (permission: `packages`).
- Includes migrations, seeders, Livewire admin UI, middleware, and feature tests.

Highlights
- DB: New migration `create_trainer_sidebar_permissions_table` to store per-trainer grants.
- Model: `TrainerSidebarPermission` registry (28 items) and `Trainer::hasAccessTo()` helper.
- Seeder: `TrainerSidebarPermissionSeeder` seeds default trainer permissions.
- Admin UI: Livewire component `Admin\Trainers\ManagePermissions` for assigning permissions (section grouped).
- Middleware: `TrainerSidebarAccess` (`trainer-sidebar:{key}`) enforces route-level access and bypasses admin users.
- Sidebar: Updated `resources/views/components/layouts/app/sidebar.blade.php` to conditionally render trainer sections/items.
- Routes: `routes/web.php` updated — `packages` moved to trainer group and wrapped with `trainer-sidebar:packages`.
- Policy: `PackagePolicy` updated to allow trainers with `packages` permission to `viewAny` and `view`.
- Tests: `tests/Feature/Admin/TrainerPermissionsTest.php` (11 passing tests) validates UI and route enforcement.

Upgrade / Deployment Notes
1. Run migrations and seeders:

   ```bash
   php artisan migrate
   php artisan db:seed --class=TrainerSidebarPermissionSeeder
   ```

2. If using compiled frontend assets, rebuild:

   ```bash
   npm install
   npm run build
   ```

3. Ensure `bootstrap/app.php` registers the `trainer-sidebar` middleware alias (already added).

4. Verify any trainers who should see `Packages` have the `packages` permission assigned from the admin UI: `/trainer-permissions`.

Developer Notes
- Tests pass locally for the feature: `php artisan test tests/Feature/Admin/TrainerPermissionsTest.php`.
- `gh` CLI was not available in this environment; releases need to be drafted in the GitHub UI or via `gh` from your machine.

Suggested GitHub Release Notes Body (copy/paste to GitHub Releases):

Title: v3.1.25 — Trainer sidebar permissions + Packages access

Body:
- Adds admin-managed trainer sidebar permissions with 28 configurable items.
- Route-level enforcement via `trainer-sidebar:{key}` middleware ensures trainers cannot access pages they don't have permissions for.
- Trainers may now be granted access to `Packages` (view/edit depending on policy); ensure `packages` permission is assigned.
- Includes migration, seeder, Livewire admin UI, middleware, route updates, and tests (11 passing).

Upgrade steps: run migrations, seed `TrainerSidebarPermissionSeeder`, rebuild assets if needed.

---

If you'd like, I can also draft a shorter changelog entry for the repository `CHANGELOG.md`. Would you like that?