# 🏠 Royal Maids Hub — Complete System Documentation (Version 3.0)

Version: 3.0 (Laravel 12 + Livewire)
Last Updated: October 22, 2025
Maintained by: Synthilogic Enterprises

---

## 🏢 About Royal Maids Hub

Royal Maids Hub (RMH) is a premium domestic services platform for homes and businesses, available at https://royalmaidshub.com. RMH connects clients with vetted domestic workers and provides tools for managing training, bookings, deployments, and performance—backed by role-based dashboards for administrators, trainers, and clients.

Service portfolio:
- Maid/Housekeeper services: laundry, ironing, cleaning, grocery shopping, cooking, and pet care
- Home managers: full residential property management
- Care services: bedside nursing, elderly caretakers, nanny services (0–5 years)
- Temporary staffing: temporary and short-term maids

Stakeholders and primary dashboards:
- Admin: manages the RMH platform, users, maids, bookings, and reports
- Trainer: trains and evaluates maids, manages sessions and progress
- Client: books maids, tracks services and subscription packages

## 📋 Table of Contents

1. System Overview
2. Architecture & Technology Stack
3. Environments & Configuration
4. Project Structure (Laravel 12)
5. Authentication & Authorization
6. User Roles & Access Control
7. Core Modules
   7.1 Maid Management
   7.2 Client Management
   7.3 Trainer & Training
   7.4 Booking & Deployment
   7.5 Evaluation & Assessment
   7.6 Dashboards (Admin, Trainer, Client)
   7.7 Notifications & Communication
8. Database Design (Eloquent)
9. Routing & API Surface
10. Frontend & UI (Blade, Livewire, Tailwind)
11. Background Jobs & Scheduling
12. Files & Storage
13. Security & Compliance
14. Testing & QA
15. Deployment Guide
16. Cost Optimization Strategy
17. Migration Guide (v2.0 → v3.0)
18. Monitoring & Observability
19. Versioning & Change Management
20. Appendix: Commands & Snippets

---

## 🎯 1. System Overview

Royal Maids Hub (v3.0) is a modernized rewrite of the v2.0 plain-PHP platform, built on Laravel 12 and Livewire for rapid development, robust security, and maintainability. It manages domestic workers (maids), clients, trainers, bookings, training and evaluations with role-based experiences and real-time dashboards.

Key objectives:
- Migrate core features to Laravel 12 with Eloquent ORM
- Build reactive UIs via Livewire (minimal custom JS)
- Improve security (Fortify, verified email, optional 2FA)
- Keep hosting costs low with database queues + file cache
- Add automated tests and CI readiness

---

## 🏗️ 2. Architecture & Technology Stack

Backend
- Laravel Framework: 12.x (PHP 8.2+)
- Auth: Laravel Fortify (email verification, 2FA optional)
- ORM: Eloquent
- Queues: Database (default), Redis optional later
- Cache & Sessions: File (local), Redis optional later

Frontend
- Blade + Livewire v3 (Volt/Flux where useful)
- Tailwind CSS (via Vite)
- Alpine.js (optional, kept minimal)

Tooling
- Vite (build), Laravel plugin
- Pest (testing)
- Pint (code style)
- Laravel Sail (optional local dev), Composer scripts for DX

Data
- MySQL 8.x (MariaDB 10.6+ compatible)
- Migrations + Seeders + Factories

---

## ⚙️ 3. Environments & Configuration

Environment variables (.env):
- APP_ENV, APP_KEY, APP_URL
- DB_CONNECTION=mysql, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- QUEUE_CONNECTION=database
- CACHE_STORE=file, SESSION_DRIVER=file
- MAIL_MAILER=smtp (SES/Mailgun/MailerSend supported), MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS, MAIL_FROM_NAME
- LOG_CHANNEL=daily

Windows/PowerShell setup (local):
```powershell
composer install
Copy-Item .env.example .env -Force
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

Optional concurrent dev:
```powershell
composer dev
```

---

## 🧱 4. Project Structure (Laravel 12)

Relevant paths in this repository:
- app/
  - Models/ (User.php, plus domain models)
  - Livewire/
    - Dashboard/
      - AdminDashboard.php
      - TrainerDashboard.php
      - ClientDashboard.php
- resources/views/
  - dashboard.blade.php (generic shell)
  - livewire/dashboard/
    - admin-dashboard.blade.php
    - trainer-dashboard.blade.php
    - client-dashboard.blade.php
  - settings/* (Volt routes)
- routes/
  - web.php (routes including Fortify/Volt settings)
- database/
  - migrations/ (users, jobs, 2FA columns, domain tables)
  - seeders/ (initial data)
- public/
  - build/ (Vite output)

---

## 🔐 5. Authentication & Authorization

- Fortify manages registration, login, password reset, email verification, 2FA (optional and recommended for admins)
- Users table extended with a simple `role` column initially: admin | trainer | client (migrate later to full RBAC with spatie/laravel-permission if needed)
- Authorization handled via Gates/Policies; routes for admin/trainer/client guarded via middleware

Role-based dashboard routing pattern:
```php
// routes/web.php
use App\Livewire\Dashboard\{AdminDashboard, TrainerDashboard, ClientDashboard};

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return match ($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'trainer' => redirect()->route('dashboard.trainer'),
            default => redirect()->route('dashboard.client'),
        };
    })->name('dashboard');

    Route::get('/admin', AdminDashboard::class)->name('dashboard.admin');
    Route::get('/trainer', TrainerDashboard::class)->name('dashboard.trainer');
    Route::get('/client', ClientDashboard::class)->name('dashboard.client');
});
```

---

## 👥 6. User Roles & Access Control

Access is role-based (admin | trainer | client) via `users.role`. Routes are protected with middleware and Policies.

1) System Administrator — Full System Control
- Permissions:
  - Full access to all features and settings
  - User management (create, edit, delete users, assign roles)
  - System configuration, backups, environment toggles
  - Access to all reports/analytics and audit logs
  - Security settings management (2FA policies, password rules)
- Primary Tasks:
  - System maintenance and performance tuning
  - Role assignment and access governance
  - Security monitoring and incident response
  - Backup/restore validation

2) Agency Manager / Admin — Operational Management
- Permissions:
  - Maid management (add/edit/delete, status updates)
  - Client management and booking oversight
  - Trainer oversight and program visibility
  - Deployment scheduling
  - Financial/KPI dashboards and PDF exports
- Primary Tasks:
  - Daily operations and staff coordination
  - Client relationship management
  - Booking pipeline and deployments
  - Performance monitoring

3) Trainer — Training Operations
- Permissions:
  - View assigned maids and training programs
  - Submit evaluations/assessments
  - Update training status and progress
  - Access training reports
- Primary Tasks:
  - Conduct training sessions
  - Evaluate performance and submit assessments
  - Track trainee progress and flag issues

4) Client (Public User) — Client Portal
- Permissions:
  - Browse available maids and view profiles
  - Make booking requests and manage own bookings
  - Provide feedback and update profile
- Primary Tasks:
  - Search and shortlist maids
  - Submit booking requests and track status
  - Communicate preferences

---

## 🔧 7. Core Modules

### 7.1 Maid Management System

Purpose: Complete lifecycle management of domestic workers

Features
- Registration System
  - Comprehensive registration form (50+ fields)
  - Personal information capture with validation
  - Document upload (IDs, certificates)
  - Medical status tracking
  - Profile image upload with preview
- Profile Management
  - Full profile editing
  - Dual status management (primary + work status)
  - Document management (view/replace/remove)
  - Performance tracking and training history
- Listing & Search
  - Paginated listing with advanced filters (status, role, experience)
  - Keyword search (name, code, phone)
  - Bulk operations (export, status updates)
  - Export capabilities (CSV/PDF – queued jobs)

Maid Information Fields
- Personal Information: First/Last Name, Phones (primary/secondary), DOB, NIN, Nationality, Marital Status, Number of Children
- Location Details: Tribe, Village, District, LC1 Chairperson details
- Family Information: Mother’s Name+Phone, Father’s Name+Phone
- Education & Experience: Education Level (P.7, S.4, Certificate, Diploma), Years of Experience, Previous Work, Mother Tongue, English Proficiency (1–10)
- Professional: Role (Housekeeper, House Manager, Nanny, Chef, Elderly Caretaker, Nakawere Caretaker)
- Status Management: Primary Status, Secondary Context (see 7.2), Work Status (employment type)
- Medical Information: Hepatitis B, HIV, Urine HCG (Result+Date), Notes
- Media & Documents: Profile Image, Additional Docs, ID Scans

Storage
- Store media in `storage/app/public` with `php artisan storage:link`
- Sensitive docs can be stored privately and served via signed routes

### 7.2 Status Management System

Purpose: Track complex maid states with dual-status architecture

Primary Status (Main Operational State)
- Available (🟢) — Ready for booking
- In Training (🟡) — Enrolled in program
- Booked (🔵) — Reserved, not yet deployed
- Deployed (🔴) — Currently working
- Absconded (🟠) — Left without notice
- Terminated (⚫) — Contract ended/dismissed
- On Leave (🟣) — Temporary absence

Secondary Context (Additional Information)
- Booked, Available, Deployed, On Leave, Absconded, Terminated

Work Status (Employment Type)
- Brokerage (🟡) — Short-term/Emergency
- Long-term (🟢) — 6+ months
- Part-time (🔵) — Part-time only
- Full-time (🔵) — Full-time only

Examples
- In Training + Booked → In program and reserved
- Available + Brokerage → Ready for short-term work
- Deployed + Full-time → Active full-time assignment
- On Leave + Part-time → Temporarily away, part-time worker

### 7.3 Client Management System

Purpose: Manage client profiles and booking requirements

Client Registration — Captures
- Contact: Full Name, Phone (WhatsApp), Email, National ID/Passport file
- Location: Country, City, Division, Parish, Address
- Home Environment: Home Type, Bedrooms, Bathrooms, Outdoor duties
- Household: Size, Children count/ages, Elderly or Special Needs, Pets
- Service Requirements: Cleaning, Laundry/Ironing, Cooking, Childcare, Elderly Care, Errands & Shopping
- Preferences: Language, Work Schedule, Live‑in/Live‑out, Additional requirements

Client Management
- View all clients and details
- Export client data (CSV/PDF)
- Delete/archive records
- Generate shareable client form link (public intake)

### 7.4 Booking System

Purpose: Manage maid bookings and deployments

Booking Process Flow
1. Client submits booking form
2. Booking request created
3. Admin reviews request
4. Maid matched to requirements
5. Booking confirmed
6. Maid status → Booked
7. Deployment scheduled
8. Maid status → Deployed
9. Contract management
10. Payment tracking

Booking Management
- All bookings list with filters (status, date, client)
- Booking details view
- Status updates and notes
- Payment tracking and PDF export

Booking Fields
- Basic: Booking ID, Client ID, Maid ID, Booking Date, Start, End, Status
- Service: Service Type, Work Schedule, Contract Type (Live‑in/Live‑out), Duration, Special Requirements
- Financial: Service Fee, Payment Method, Payment Status, Commission, Total Amount

### 7.5 Trainer Management System & Training

Trainer Profiles
- Name, Phone, Email, Specialization, Experience (years), Status, Working Hours, Profile Picture

Features
- List/add/edit/delete trainers
- Performance tracking and assignments
- Training dashboard: assigned maids, progress, evaluations, metrics, calendar

### 7.6 Evaluation & Assessment System

Categories
- Skill Proficiency: cleaning techniques, cooking, childcare, specialization
- Work Attitude: punctuality, reliability, initiative, professionalism
- Communication: language skills, instruction comprehension, feedback, client interaction
- Reliability: consistency, trustworthiness, honesty, dependability

Submission & Reporting
- Submit evaluations, view history, track KPI scores
- Generate performance reports (PDF export queued)

Scoring System
- Score: 0–100
- Ratings: 90–100 Excellent; 80–89 Very Good; 70–79 Good; 60–69 Satisfactory; <60 Needs Improvement

### 7.7 Dashboards & Analytics

Purpose: Provide real-time insights

KPI Widgets
- Total/Available/In Training/Deployed Maids
- Total Clients, Active Bookings
- Revenue This Month, Pending Evaluations

Quick Navigation
- Reports, KPI Dashboard, Maids, Trainers, Clients, Bookings

Charts & Visualizations
- Status distribution (pie), Training over time (line), Booking trends (bar), Revenue (area)

Advanced Analytics (KPI Dashboard)
- Training completion, deployment rates, satisfaction metrics, revenue trends, benchmarks
- Interactivity: date filters, drill-down, exports

Implementation (v3)
- Livewire dashboards scaffolded: AdminDashboard, TrainerDashboard, ClientDashboard
- Ready to bind to Eloquent metrics and queries

### 7.8 Notifications & Communication
- Mail notifications (queued)
- In-app notifications (database)
- Real-time chat (deferred in v3): previous Ratchet-based chat to be replaced with Laravel WebSockets/Pusher in a future minor release

### 7.7 Notifications & Communication
- Mail notifications via queue
- In-app notifications (database)
- Real-time chat postponed; consider Pusher or Laravel WebSockets later

---

## 🗄️ 8. Database Design (Eloquent)

Database Details
- Engine: MySQL 8.0+ (MariaDB 10.6+ compatible)
- Charset/Collation: utf8mb4 / utf8mb4_unicode_ci

Key tables and models (v3.0 mapping):
- users (User) → add `role` column, 2FA columns (Fortify), email_verified_at
- maids (Maid)
- clients (Client)
- trainers (Trainer)
- bookings (Booking)
- training_assignments (TrainingAssignment)
- evaluation_submissions (EvaluationSubmission)
- evaluation_scores (EvaluationScore)
- maid_evaluations (MaidEvaluation)

Indexes: status, work_status, role, dates for performant filtering

Migrations follow Laravel conventions (timestamps, foreignId, indexes). Use enums via string+constraints or native enum depending on DB policy.

Schema Highlights
- maids: maid_code (unique), phones, DOB, arrival date, NIN (unique), location (tribe/village/district), education, experience, mother tongue, english_proficiency, role, previous_work, status/work_status, medical_status (JSON), profile_image, notes, indexes on status/work_status/role/dates
- clients: contact, location, household, home details, service requirements, preferences, start_date, date_registered
- bookings: client_id, maid_id, booking_date, start/end dates, status, service_type, schedule, contract_type, fees, payment fields, commissions
- trainers: user_id (optional), profile fields, specialization, status, hours
- training_assignments: maid_id, trainer_id, program_id, status, start/end dates, completion %, notes
- evaluation_*: submissions (scores, status, comments) and scores per category; maid_evaluations summary table

---

## 🔌 9. Routing & API Surface

- Web routes in `routes/web.php` (Blade/Livewire)
- API (optional, future): `routes/api.php` for mobile/app integrations; Sanctum tokens if needed
- Livewire components expose actions for CRUD and UI interactions

---

## 🎨 10. Frontend & UI (Blade, Livewire, Tailwind)

- Tailwind via Vite; dark mode supported in components
- Livewire for reactivity without heavy JS
- Blade layouts with slots/components for consistency
- Asset build commands:
```powershell
npm run dev   # HMR during development
npm run build # Production build
```

---

## 🧵 11. Background Jobs & Scheduling

- Queue driver: database
- Typical jobs: emails, report generation, data imports
- Scheduler: run `php artisan schedule:run` every minute (cron/Task Scheduler)

---

## 🗂️ 12. Files & Storage

- Public uploads stored in `storage/app/public`, exposed via `php artisan storage:link`
- Sensitive documents kept outside `public/` and downloaded via signed routes

---

## 🔒 13. Security & Compliance

- Fortify: email verification, 2FA (TOTP) for admins
- CSRF protection, signed URLs, encryption by default
- Input validation via Form Requests/Livewire rules
- Authorization via Gates/Policies
- Audit trails via model events/logging (future enhancement)

---

## ✅ 14. Testing & QA

- Pest for unit/feature tests
- Minimal smoke tests:
  - Auth flow (login, email verification)
  - Role-based dashboard redirects
  - CRUD for key entities (maids, bookings)
- Example:
```php
it('redirects users to role dashboard', function () {
    $admin = \App\Models\User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    actingAs($admin)->get('/dashboard')->assertRedirect(route('dashboard.admin'));
});
```

Run tests:
```powershell
php artisan test
```

---

## 🚀 15. Deployment Guide

Target: low-cost VPS/shared hosting supporting PHP 8.2 + MySQL

Steps:
```powershell
# 1) Pull code
# 2) Dependencies
composer install --no-dev --optimize-autoloader
# 3) Env & key
# (ensure .env is set correctly and APP_KEY present)
# 4) Migrate
php artisan migrate --force
# 5) Cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache
# 6) Build assets
npm ci
npm run build
# 7) Restart queue workers if any
```

Web server:
- Nginx/Apache pointing to `public/`
- PHP-FPM configured for PHP 8.2

---

## 💸 16. Cost Optimization Strategy

- Use database queues instead of Redis initially
- File cache/session in early phases; upgrade to Redis if needed
- Local disk storage + periodic backups; upgrade to S3/R2 later
- Choose one transactional email provider per environment (free tier)
- Avoid heavyweight JS frameworks; leverage Livewire/Blade

---

## 🔄 17. Migration Guide (v2.0 → v3.0)

Approach: incremental migration with import commands

1) Prepare legacy DB dump (mysqldump)
2) Add a secondary DB connection in `config/database.php` for legacy
3) Create Artisan commands to import entities in chunks (maids, clients, bookings, etc.)
4) Verify in staging with test coverage
5) Schedule a maintenance window to run imports in production

Example signature:
```php
// app/Console/Commands/ImportLegacyMaids.php
protected $signature = 'rmh:import-maids {--chunk=500}';
```

Data mapping:
- Normalize enums/status fields
- Map IDs and maintain referential integrity
- Store legacy codes where needed (e.g., maid_code)

---

## 📈 18. Monitoring & Observability

- Logging: daily files (storage/logs)
- Error tracking (optional): Sentry/Logtail free tiers
- Laravel Pail for nicer local logs during development

---

## ♻️ 19. Versioning & Change Management

- Semantic versioning for releases
- Changelog updates per release
- Feature flags for risky features

---

## 📚 20. Appendix: Commands & Snippets

PowerShell (Windows):
```powershell
# Start dev server
php artisan serve

# Queue listener (dev)
php artisan queue:listen --tries=1

# Scheduler (run once; use Task Scheduler for minutely)
php artisan schedule:run

# Storage symlink
php artisan storage:link

# Code style
vendor/bin/pint
```

NPM scripts:
```powershell
npm run dev
npm run build
```

Composer scripts (see composer.json):
```powershell
composer dev
composer test
```

---

## 🧭 Upgrade Changelog: Version 2.0 → 3.0

What changed, what’s replaced, and what’s new in this Laravel 12 + Livewire release.

Replaced
- Plain PHP + PDO → Laravel 12 + Eloquent ORM (repositories optional)
- Custom auth/session pages → Laravel Fortify (login, register, email verification, 2FA)
- Mixed Bootstrap/Tailwind → Tailwind-first via Vite (Bootstrap usage removed/optional)
- Webpack/Babel pipeline → Vite build tool
- Ad‑hoc pages and includes → Blade layouts + Livewire components
- Manual routing and scattered endpoints → Organized routes in `routes/web.php` (and `routes/api.php` later)

Enhanced
- Role-based dashboards (Admin, Trainer, Client) via Livewire components
- Consistent dark mode and responsive UI with Tailwind
- Config/route/view caching for performance
- Queued emails and background tasks (database driver)
- Stronger security posture (CSRF, encryption, 2FA, email verification)
- Testing baseline with Pest; factories/seeders for reproducible data
- Standardized status fields and indexes for faster queries

Deferred/Changed Behavior
- Real-time chat via Ratchet is deferred; consider Laravel WebSockets/Pusher when needed
- Some legacy standalone PHP pages are fully replaced by Blade/Livewire flows
- Reporting/exports redesigned to use queued jobs (PDF/Excel added later)

Data Model Highlights
- Users now include email verification and optional 2FA columns
- Status fields normalized (primary/work status) with indexes
- Timestamps and foreign keys standardized per Laravel conventions

Developer Experience
- Composer scripts for dev/test; Pint for code style
- Local-first config with .env; separate per-environment overrides

Impact to Operators
- Requires PHP 8.2 and MySQL 8.x (or MariaDB 10.6+)
- Queue worker should be running for emails/long jobs

---

## 🧪 Deployed Tests Summary

Scope
- Authentication flows (login, registration, email verification basics)
- Role-based dashboard redirect behavior
- CRUD smoke tests for core entities (to be expanded as features land)

How to run locally
```powershell
php artisan test           # full suite
php artisan test --filter=DashboardTest
```

CI readiness
- The suite runs with `php artisan test`. You can wire this into GitHub Actions or any CI.

Test data
- Use model factories and seeders to generate fixtures for predictable runs.

---

## 🐛 Known Issues & Debugging Guide

Common Failures and Fixes

1) Blank page or 500 error after cloning
- Cause: Missing APP_KEY, misconfigured .env, or cache artifacts
- Fix:
```powershell
php artisan key:generate
php artisan config:clear; php artisan cache:clear; php artisan view:clear; php artisan route:clear
```

2) 404 on routes after deploy
- Cause: Stale route cache or wrong APP_URL
- Fix:
```powershell
php artisan route:clear
php artisan route:list | Select-String dashboard
```

3) CSS/JS not updating
- Cause: Vite dev server not running or production build missing
- Fix:
```powershell
npm run dev    # during development
npm run build  # for production assets
```

4) Storage or image access issues
- Cause: Missing storage symlink or filesystem permissions
- Fix:
```powershell
php artisan storage:link
# Ensure write access to storage/ and bootstrap/cache/
```

5) Emails not sending
- Cause: Incorrect MAIL_* settings
- Fix: Verify MAIL_HOST/PORT/USER/PASS and from address; test with a mailable:
```powershell
php artisan tinker
```
Then in Tinker, send a test using your mailable or `Mail::raw('test', fn($m)=>$m->to('you@example.com'));`

6) Queued jobs stuck or not processed
- Cause: Queue worker not running or failing jobs
- Fix:
```powershell
php artisan queue:work
php artisan queue:failed
php artisan queue:retry all
```

7) Database migration failures
- Cause: Wrong DB credentials or incompatible schema
- Fix:
```powershell
php artisan migrate --pretend   # preview SQL
php artisan migrate             # run migrations
```

8) Email verification/2FA issues
- Cause: Fortify config or URL misconfig
- Fix: Ensure APP_URL is correct; check `config/fortify.php`; confirm routes enabled for verification and 2FA.

Where to Look for Errors
- Application logs: `storage/logs/laravel.log`
- Tail in PowerShell:
```powershell
Get-Content -Path .\storage\logs\laravel.log -Tail 200 -Wait
```
- Web server/PHP-FPM logs (hosting specific)

Observability Options (Optional, free tiers)
- Sentry or Logtail for error tracking
- Laravel Pail for improved local log viewing

Escalation Checklist
- Confirm .env values (DB_*, APP_URL, MAIL_*)
- Clear and rebuild caches (config/route/view)
- Check queue workers and failed jobs
- Inspect logs for stack traces and copy exact error lines when reporting

---

End of Version 3.0 Documentation.
