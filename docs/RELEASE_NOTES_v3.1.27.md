# Release Notes v3.1.27 — Enterprise RBAC + User Management

**Release Date:** February 6, 2026

## Executive Summary

v3.1.27 delivers enterprise-grade role-based access control (RBAC) with strict field-level protection for identity and financial data, plus a full-featured User Management console for super admins. Legacy admins are promoted to full-access super admins to keep existing environments stable while the system transitions to the new role model.

---

## Key Highlights

### ✅ Enterprise RBAC Core
- New roles added: Super Admin, Operations Manager, Trainer, Finance Officer, Customer Support.
- Centralized role constants and helpers on the User model.
- Super Admin is the highest-privilege role with full access across the system.
- Legacy Admins are treated as Super Admins for backwards compatibility.

### 🔒 Sensitive Field Protection (Strict)
- Identity fields (NIN, Passport, ID uploads) restricted to Super Admin only.
- Financial fields (maid salary, client payments, company profit) restricted to Super Admin + Finance Officer only.
- Enforcement at multiple layers:
  - Policies and gates
  - Model serialization (hidden fields)
  - Save guards that reject unauthorized writes
  - View-level gating for visibility

### 👥 User Management Console
- New advanced UI for user management with search, filters, and role assignment.
- Actions included:
  - Create user
  - Edit user (role, verification, password)
  - Reset password
  - Activate/deactivate user
  - Soft delete user
- Access restricted to Super Admin only.

### 🔐 Authentication Enforcement
- Deactivated users are blocked from logging in.

### 💰 Financial Overview Update
- Dashboard retained fee now uses client payment - maid salary + service fee.
- Service Paid terminology standardized to Service Fee in financial summaries.

---

## Roles & Access Control

### Roles
- Super Admin
- Operations Manager
- Trainer
- Finance Officer
- Customer Support
- Client

### Access Rules
- Super Admin: full system access.
- Finance Officer: access to financial fields only (not identity fields).
- Trainers, Customer Support, Clients: never see or edit identity/financial fields.
- Legacy Admin: treated as Super Admin for full access.

---

## Data Model Updates

### Users
- Added `is_active` for deactivate/activate support.
- Added soft deletes to safely archive users.

---

## Upgrade Notes

1) Run migrations
```
php artisan migrate
```

2) Convert legacy admins to super admins (migration included)
- This release includes a migration that converts all `admin` roles to `super_admin`.

---

## Tests

- User management access + actions:
  - `php artisan test tests/Feature/UserManagementTest.php`

---

## Files Touched (Highlights)

- Policies and gates for sensitive field access
- User model roles and helpers
- New User Management Livewire component and UI
- Authentication guard for deactivated users

---

## Backwards Compatibility

- Legacy `admin` users retain full access (treated as Super Admin).
- Existing role-based route middleware continues to work without breaking changes.

---

## Notes

This release focuses on roles, permissions, and field-level data protection, plus a minor dashboard finance summary update for retained fee and service fee terminology.
