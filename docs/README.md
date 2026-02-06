# RoyalMaidsHub v3.1.26 — System Documentation

**Current Version**: v3.1.26 (February 4, 2026)  
**Latest Features**: Phase 1 Automation Framework, Identity Verification, Soft Deletes, Audit Trails

This documentation covers the full system: pages, features, roles, dashboards, data model, routes, integrations, and tests. It is organized by topic and module for easy navigation.

## Quick Links

**Most Important:**
- **Release Notes**: docs/RELEASE_NOTES_v3.1.26.md — Deployment guide and feature overview
- **Automation Plan**: AUTOMATION_IMPLEMENTATION_PLAN_v1.md — 60-page roadmap with architecture and KPIs
- **Changelog**: docs/Changelog.md — Version history

**System Documentation:**
- Overview: docs/Overview.md
- Setup & Environments: docs/Setup.md
- Architecture: docs/Architecture.md
- Roles & Permissions: docs/Roles-and-Permissions.md
- Routes Reference: docs/Routes.md
- Database & Models: docs/Database.md
- Testing Strategy: docs/Testing.md
- Integrations: docs/Integrations.md
- Security: docs/Security.md
- UX & Navigation: docs/UX/Navigation.md
- Glossary: docs/Glossary.md

## Modules

- CRM: docs/Modules/CRM/Overview.md (Leads, Opportunities, Activities, Tags, Settings, Reports)
- Tickets: docs/Modules/Tickets/Overview.md ⭐ *Auto-assignment & SLA monitoring now available*
- Maids: docs/Modules/Maids/Overview.md
- Trainers: docs/Modules/Trainers/Overview.md
- Clients: docs/Modules/Clients/Overview.md ⭐ *Identity verification integrated*
- Bookings: docs/Modules/Bookings/Overview.md ⭐ *Identity snapshots now available*
- Deployments: docs/Modules/Deployments/Overview.md ⭐ *Financial tracking & audit trails*
- Programs: docs/Modules/Programs/Overview.md
- Evaluations: docs/Modules/Evaluations/Overview.md
- Schedule: docs/Modules/Schedule/Overview.md
- Packages: docs/Modules/Packages/Overview.md

## What's New in v3.1.26

### Automation (Phase 1) ✅
- **Ticket Auto-Assignment**: Workload-based, round-robin, and category-based strategies
- **SLA Monitoring**: Real-time breach detection with escalation to admins
- **Smart Notifications**: Multi-channel (mail + database) with async queuing
- **Scheduler Integration**: 30-minute interval checks for proactive monitoring
- **Configuration UI**: 5 new CRM settings for full control

### Data Management ✅
- **Identity Verification**: NIN/Passport storage with uniqueness constraints
- **Audit Trails**: Who created/modified every record with timestamps
- **Soft Deletes**: Archive records without permanent deletion
- **Financial Tracking**: Deployment profit/loss calculations with currency support

### Testing ✅
- **11 Comprehensive Tests**: 100% passing (6 auto-assignment, 5 SLA notification)
- **Pest Framework Integration**: RefreshDatabase, Notification::fake()
- **Production Ready**: All features have feature toggles for safe rollback

---

**Tip**: Use this file as a table of contents and dive into module pages for page-by-page details.

**For Deployment**: See docs/RELEASE_NOTES_v3.1.26.md before upgrading
