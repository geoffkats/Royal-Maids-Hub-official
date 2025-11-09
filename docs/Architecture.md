# Architecture

- MVC + Livewire components for pages
- Domain folders:
  - app/Models and app/Models/CRM: Eloquent models, including polymorphic relationships
  - app/Livewire: Feature modules (CRM, Tickets, Maids, etc.)
  - routes/web.php: All web routes, grouped by role and feature
  - app/Http/Controllers/CRM/DataTransferController: Import/Export orchestration
  - resources/views/livewire: Blade views for Livewire components

Key patterns
- Policies registered in AppServiceProvider (Gate::policy)
- Morph map in AppServiceProvider::boot via Relation::morphMap([...])
- Livewire for dynamic UIs (conversion modals, forms, wizards)
- Testing via Pest with Livewire testing helpers
