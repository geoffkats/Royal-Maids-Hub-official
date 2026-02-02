# UI Modernization & Brand Update — TODO Track

Last updated: 2025-10-24

Purpose
-------
This document describes the plan to update Royal Maids Hub UI to the new brand palette and UI rules. Use this file as both a checklist and a developer guide for the changes.

Brand Colors (Primary)
----------------------
- Primary (Background) — Royal Purple: #3B0A45
- Accent / Highlight — Gold: #F5B301
- Secondary (Panels / Cards) — Deep Violet: #512B58
- Text (Primary) — White: #FFFFFF
- Text (Secondary) — Light Gray: #D1C4E9

Supporting Colors
-----------------
- Success / Confirmation — Emerald Green: #4CAF50
- Warning — Amber: #FFC107
- Error / Alert — Soft Red: #E53935
- Info / Links — Sky Blue: #64B5F6

High-level Plan
---------------
1. Audit: identify all Blade views, Livewire components, Tailwind utilities, and CSS that reference colors.
2. Tokens: create CSS variables and a Tailwind theme mapping.
3. Components: update shared components (buttons, badges, cards, table headings) to use tokens.
4. Pages: apply components to prioritized pages (Dashboard, Reports, Packages, Bookings, Maids).
5. Charts: update Chart.js palettes to use tokens and ensure dark-mode compatibility.
6. Tests: add visual snapshots and automated checks for contrast.
7. Docs: keep README and this tracker updated with migration steps.

Quick Implementation Notes
-------------------------
- Tailwind: update `tailwind.config.js` with new colors under `theme.extend.colors.brand` and create semantic tokens (primary, accent, surface, text-primary).
- CSS Variables: create `resources/css/_tokens.css` and import early in the build.
- Gradients: provide `--brand-gradient` variable: linear-gradient(180deg, #3B0A45 0%, #512B58 100%);
- Chart.js: move palette to a JS module `resources/js/chartPalette.js` that reads CSS variables or imports a JS color map.

Acceptance Criteria
-------------------
- Sidebar and topbar use Royal Purple with Gold active/hover accents.
- Buttons use Gold for primary, with accessible contrast on hover/focus.
- Cards use Deep Violet backgrounds; text readable (white primary text).
- All charts use brand palette and display correctly in dark mode.
- No remaining references to old hardcoded color hex in UI files (audit pass).
- Visual snapshots for Dashboard and Reports are updated and approved.

Developer Checklist (step-by-step)
----------------------------------
- [ ] Run `npm run dev` to verify existing build baseline.
- [ ] Create `resources/css/_tokens.css` with CSS variables.
- [ ] Update `tailwind.config.js` colors and recompile.
- [ ] Update `resources/views/components` Blade partials to use new tokens.
- [ ] Update Livewire components to use updated components/partials.
- [ ] Update chart palettes & re-run UI to verify.
- [ ] Run visual tests and unit tests.
- [ ] Create PR with screenshots and QA notes.

Local Commands
--------------
Use the following to build/check locally:

```powershell
npm install
npm run dev
php artisan view:clear; php artisan config:clear; php artisan cache:clear
php artisan test
```

Notes & Risks
-------------
- Some components may rely on third-party class names; plan a mapping or wrapper approach.
- Visual regressions are likely; use a staging rollout and a feature branch.

If you want, I can begin by updating `tailwind.config.js` and adding the token CSS file.

