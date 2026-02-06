# Release Notes v3.2.27 — Client Evaluations + Contract Documents

**Release Date:** February 6, 2026

## Executive Summary

v3.2.27 adds a complete client evaluation workflow (question bank, admin views, and public feedback form) and introduces contract document attachments. It also aligns contract forms with the Royal purple dashboard palette and fixes admin access gaps in client evaluation policies.

---

## Key Highlights

### Client Evaluations (NEW)
- Admin client evaluations module with create, list, and detail views.
- Public, signed feedback form for clients with dynamic questions and ratings.
- Client evaluation question bank UI with search, ordering, and active/required toggles.
- Admin feedback responses index and detail views.
- Email template for client evaluation links.

### Contract Document Attachments
- Upload multiple contract documents during create/edit.
- Document list shown on contract edit and show pages.
- Storage on public disk with per-contract folder organization.

### UI / Branding Updates
- Contract create/edit forms updated to the Royal purple dashboard palette.

### Permissions and Policy Fixes
- Super admins and admins allowed to access client evaluation creation and viewing.
- Client show page feedback permission check uses policy gate correctly.

---

## Data Model Updates

### New Tables
- client_evaluations
- evaluation_tasks
- client_evaluation_questions
- client_evaluation_links
- client_evaluation_responses

### Schema Updates
- maid_contracts: add contract_documents (json)

---

## Routes

- Public feedback form (signed): `/client-feedback/{token}`
- Admin feedback list: `/client-feedback`
- Admin question bank: `/client-evaluation-questions`

---

## Tests

- Contract suite: `php artisan test --filter=Contract`
- Client feedback index: `php artisan test tests/Feature/ClientEvaluations/FeedbackIndexTest.php`

---

## Upgrade Notes

1) Run migrations:
```
php artisan migrate
```

2) If document links 404 in local dev:
```
php artisan storage:link
```

---

## Notes

If the UI changes do not appear immediately, rebuild frontend assets with `npm run dev` or `npm run build`.
