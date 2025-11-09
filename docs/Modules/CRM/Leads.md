# CRM — Leads

Pages
- Index: list, filters by status/owner/source
- Create: capture lead details
- Show: lead detail, activities, notes, conversion actions
- Edit: update lead

Conversion to Client
- Livewire component: app/Livewire/CRM/Leads/Show.php
- Modal supports:
  - Create new client from lead
  - Attach to existing client (autocomplete search by name/email)
- Service: App/Services/CRM/ConvertLeadToClientService
  - Creates Client + User (role=client) if new
  - Marks lead as converted
  - Re-links Tickets requester from lead -> client
  - Re-parents open Opportunities to client
  - Logs conversion Activity and StatusHistory

Conversion to Opportunity
- Quick action modal creates an Opportunity in default pipeline stage and sets lead status to working if needed

Import/Export
- Export: DataTransferController@exportLeads -> xlsx
- Import: DataTransferController@importLeads -> logs in crm_data_transfers with counts/errors

Model
- App/Models/CRM/Lead: relationships and helpers (canBeConverted, markAsConverted, ...)
