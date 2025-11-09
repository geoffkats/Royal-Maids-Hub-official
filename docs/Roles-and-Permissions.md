# Roles & Permissions

Roles: admin, trainer, client (stored on users.role)

Middleware
- auth, verified: basic access
- role: custom RoleMiddleware enforces roles in route groups

Policies
- CRM models (Lead, Opportunity, Activity) policies mapped in AppServiceProvider::$policies and registered in boot()

Dashboards
- Admin: /admin (dashboard.admin)
- Trainer: /trainer (dashboard.trainer)
- Client: /client (dashboard.client)
