# Changelog

## Latest Version: v3.1.27 (February 6, 2026)

**Enterprise RBAC + User Management**

### Major Features
- ✅ New roles: Super Admin, Operations Manager, Trainer, Finance Officer, Customer Support
- ✅ Strict field-level protection for identity and financial data
- ✅ User Management UI (create, edit, reset password, activate/deactivate, soft delete)
- ✅ Super Admin access gate and policy enforcement
- ✅ Deactivated users blocked at login
- ✅ Migration converts legacy admin users to super_admin
- ✅ Dashboard financial overview adds retained fee (client payment - maid salary + service fee)
- ✅ Service Paid renamed to Service Fee in financial summaries

**See**: `docs/RELEASE_NOTES_v3.1.27.md` for full details

---

## v3.1.26 (February 4, 2026)

**Phase 1 Automation Framework, Identity Verification, Audit Trails**

### Major Features
- ✅ Ticket Auto-Assignment Service (workload/round-robin/category strategies)
- ✅ SLA Breach Detection & Multi-Channel Notifications
- ✅ 30-Minute Scheduler for Proactive Monitoring
- ✅ Client Identity Verification (NIN/Passport)
- ✅ Booking Identity Snapshots
- ✅ Audit Trail (created_by/updated_by tracking)
- ✅ Soft Delete System (Client, Deployment, Maid)
- ✅ Deployment Financial Tracking (maid_salary, client_payment, profit/loss)
- ✅ 11/11 Comprehensive Tests Passing

**See**: `CHANGELOG_v3.1.26.md` and `docs/RELEASE_NOTES_v3.1.26.md` for full details

---

## v3.1.25 (February 2, 2026)

**Trainer Sidebar Permissions & Packages Access**

- Trainer sidebar permission management system
- 28 sidebar items with granular per-trainer grants
- Route-level enforcement via middleware
- Admin UI for permission management
- Packages access control for trainers

**See**: `CHANGELOG_v3.1.25.md` and `docs/RELEASE_NOTES_v3.1.25.md` for full details

---

## v3.1.0 (October 26, 2025)

Initial v3.1 release with CRM enhancements

---

## V3.0 Highlights

- Livewire CRM modules and conversion workflow
- DataTransferController for imports/exports with safe counters
- Route ordering fixes for export endpoints
- Base Controller traits added for authorization
- Conversion modal enhanced with client search
- Tests added for conversion and import/export
