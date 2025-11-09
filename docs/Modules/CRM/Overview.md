# CRM Module — Overview

Pages
- Pipeline Board (Kanban): /crm/pipeline/{pipeline?}
- Leads: index, create, show, edit
- Opportunities: index, create, show, edit
- Activities: index, create, show, edit
- Tags: index, create, edit
- Reports: funnel, sales-performance, activity-metrics, revenue-forecasting
- Settings

Key Concepts
- Lead lifecycle: new -> working -> qualified -> converted/disqualified
- Conversion: Leads can be converted to Clients or Opportunities
- Pipeline & Stages: tracks opportunity progression
- Import/Export: xlsx exports; CSV/XLSX imports with logging to crm_data_transfers

Security
- Admin only access to CRM routes
- Policies for CRUD
