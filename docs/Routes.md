# Routes Reference

See routes/web.php for authoritative definitions. Key groups:

- Public
  - GET / (home)
  - GET /packages/public

- Auth + Verified
  - GET /dashboard -> redirects by role
  - AdminDashboard, TrainerDashboard, ClientDashboard

- Admin only
  - Maids CRUD (+ export PDF)
  - Trainers CRUD
  - Clients CRUD
  - Packages CRUD

- Admin + Trainer
  - Tickets: index, create, analytics, inbox, show, edit
  - Reports: index, KPI dashboard, trainer reports
  - Deployments: index, show
  - Programs: index, create, show, edit
  - Evaluations: index, create, show, edit
  - Schedule: index
  - CRM Reports: funnel, sales-performance, activity-metrics, revenue-forecasting

- CRM (Admin only)
  - Pipeline board
  - Leads: index, create, show, edit, export (xlsx), import (POST)
  - Opportunities: index, create, show, edit, export (xlsx), import (POST)
  - Activities: index, create, show, edit, export (xlsx)
  - Tags: index, create, edit
  - Settings

- Client role
  - Views: my-bookings, browse-maids, subscriptions, favorites, support
