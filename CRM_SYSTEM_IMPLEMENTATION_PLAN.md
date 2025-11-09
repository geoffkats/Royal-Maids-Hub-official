# CRM System: Design and Integration Plan (Royal Maids Hub)

Updated: 2025-10-25

## Purpose

Design a lightweight but robust CRM inside Royal Maids Hub that:

- Centralizes all pre-customer interactions: leads, contacts, pipeline, activities, and opportunities
- Improves relationships with timely follow-ups and a complete timeline of communications
- Increases sales by tracking leads through stages and closing opportunities
- Stays decoupled from core operational tables until a lead is converted, while still integrating with the existing Ticket system and user/staff entities


## Scope and guiding principles

- Separate CRM domain from core ops (Bookings, Deployments, Maids). CRM owns Leads/Pipeline/Activities/Opportunities.
- Integrate through safe bridges:
  - Polymorphic relations for activities/notes/attachments
  - Ticket requester polymorphism (Lead or Client)
  - Conversion workflow that creates a Client and re-links CRM data
- Avoid duplication of Clients: Leads live in CRM; Clients live in core. A lead becomes a client only on conversion.
- Pin minimal, indexed tables; keep UI fast via Livewire and Tailwind (brand palette already used elsewhere).


## Core entities (data model)

Contract (types are illustrative; adapt to current Laravel/MySQL standards):

- crm_leads
  - id (pk)
  - first_name, last_name, full_name (cached)
  - email (unique index nullable), phone (index)
  - company, job_title, industry, city, address
  - source_id (fk -> crm_sources)
  - owner_id (fk -> users.id) sales owner
  - status enum: new, working, qualified, disqualified, converted
  - score int (lead scoring)
  - interested_package_id (fk -> packages.id, nullable)
  - notes (text, nullable)
  - client_id (fk -> clients.id, nullable) — set only upon conversion (audit link)
  - created_at, updated_at, indexes: (email), (phone), (owner_id), (status)

- crm_sources
  - id, name (e.g., Web Form, Referral, Ad Campaign, Walk-in), active boolean

- crm_pipelines
  - id, name (e.g., Default Sales), is_default boolean

- crm_stages
  - id, pipeline_id fk -> crm_pipelines
  - name (e.g., New, Discovery, Proposal, Negotiation, Won, Lost)
  - position smallint
  - sla_first_response_hours int nullable
  - sla_follow_up_hours int nullable

- crm_opportunities (Deals)
  - id (pk)
  - lead_id fk -> crm_leads (nullable: opportunity can live post-conversion)
  - client_id fk -> clients (nullable)
  - name, amount decimal(12,2), currency varchar(3)
  - pipeline_id fk -> crm_pipelines, stage_id fk -> crm_stages
  - probability tinyint (0-100), expected_close_date date
  - owner_id fk -> users.id
  - package_id fk -> packages.id nullable (deal for a specific package)
  - status enum: open, won, lost, on-hold
  - created_at, updated_at, indexes: (owner_id), (stage_id), (lead_id, client_id)

- crm_activities (Unified timeline: calls, emails, meetings, tasks, notes)
  - id (pk)
  - subject, type enum: call, email, meeting, task, note
  - due_at datetime nullable, completed_at datetime nullable, outcome text nullable
  - body text nullable
  - owner_id fk -> users.id (who owns the activity)
  - activityable_type, activityable_id (morph) — Lead, Client, Opportunity, Ticket, Booking
  - created_at, updated_at, indexes: (activityable_type, activityable_id), (owner_id), (due_at)

- crm_tags
  - id, name unique

- crm_taggables (polymorphic pivot)
  - tag_id fk -> crm_tags
  - taggable_type, taggable_id — can tag Lead, Opportunity, Activity

- crm_attachments
  - id, path, filename, mime, size
  - attachable_type, attachable_id (morph)
  - uploaded_by fk -> users.id
  - created_at

Notes:

- Reuse existing TicketAttachment only if we generalize it to a polymorphic table; otherwise keep crm_attachments separate to avoid regression.
- Ticket requester polymorphism in the app already supports morphing by type (requester_type/id). We will allow Lead as a requester.


## Relationships to existing models

- Ticket.requester: morphTo — supports Client today; extend to also support Lead.
- Lead hasMany Activities (morph), hasMany Opportunities, belongsTo Source, belongsTo owner (User).
- Opportunity belongsTo Lead or Client (one side nullable), belongsTo Pipeline/Stage, hasMany Activities (morph).
- Client (existing) can have Activities (morph) and Opportunities.


## Lead lifecycle and conversion

1) Capture lead (form import/API/manual). Owner set (round-robin or manual).
2) Work lead in pipeline using Activities with SLA-driven reminders.
3) Create an Opportunity for qualified leads (optional for very small deals).
4) Convert Lead → Client:
   - Create Client record using lead name/email/phone/address.
   - Update crm_leads.client_id = new client.id and status = converted.
   - Re-link any Tickets where requester is the Lead to the new Client requester.
   - Re-parent open Opportunities to client_id (keep lead_id for audit) and keep history.
   - Keep Activities attached via polymorph; for Lead-specific, retain as-is; for Opportunity, no change; for Tickets, requester morph updated.
   - Optionally auto-create first Booking if the won opportunity references a package.

Edge cases to handle:

- Duplicate leads (same email/phone) — provide merge flow.
- Convert to an existing Client — map to a selected client instead of creating a new one.
- Disqualify with reason — preserve history; opportunity auto-cancel.
- Multiple opportunities per lead — all re-parent to Client on conversion.


## Ticket system integration

- Pre-sales tickets: Allow raising a Ticket by/for a Lead using requester_type = Lead. Category examples: Inquiry, Quote Request, Pre-Sales Support.
- SLA alignment: CRM stages can include sla_first_response_hours to auto-create follow-up Activities; Tickets maintain their own SLA per ticket.
- Assignment:
  - Tickets from Leads auto-assign to Sales team queue; upon conversion, ownership may move to Support.
  - Activities can also be attached to Tickets for a unified timeline inside CRM.
- Conversion behavior:
  - When converting a Lead with open Tickets, update Ticket.requester from Lead → Client, keep all history, maintain ticket number.
- Visibility:
  - Ticket pages show requester badge color-coded (Lead vs Client) and link to the Lead/Client profile.


## Permissions and roles

- Roles: Sales, Marketing, Support, Trainer, Admin.
- Sales & Marketing: Full access to Leads/Opportunities/Activities they own; read-only to Clients except those they own; no access to Maids/Training internals.
- Support: Read Leads (limited), full on Tickets; can comment Activities linked to Tickets.
- Admin: Full.
- Policies: Define per model (LeadPolicy, OpportunityPolicy, ActivityPolicy) mirroring existing policy patterns.


## UI and navigation

- Sidebar section: CRM
  - Leads (index/create/show/edit)
  - Opportunities (index/show)
  - Pipeline (Kanban by stage)
  - Activities (timeline list with filters)
- Livewire components (examples):
  - App\Livewire\CRM\Leads\Index|Create|Show|Edit
  - App\Livewire\CRM\Opportunities\Index|Show
  - App\Livewire\CRM\Pipeline\Board (drag-and-drop via Alpine/Sortable)
  - App\Livewire\CRM\Activities\Index
- Branding: reuse purple/gold palette; compact card lists, clear stage badges, SLA chips.


## Workflows and automations

- Lead capture:
  - Inbound: web form/API/import. Validate duplicates by email/phone.
  - Auto-assign owner by round-robin or source rule.
  - Auto-create a “First response” Activity due within sla_first_response_hours.
- Lead scoring:
  - Score based on source, package interest, engagement (#activities), and quick responses.
  - Surface as badge and for sorting.
- Reminders/SLAs:
  - Overdue Activities highlighted; daily digest to owners.
- Opportunity won:
  - If package_id present: create Invoice/Subscription or initial Booking draft; notify Operations.
- Pre-sales ticket created:
  - Link back to Lead; notify Sales owner and Support; add activity “Ticket raised”.


## API/webhooks (optional phase)

- Endpoints to receive leads from landing pages/ads.
- Webhooks to notify third-party email tools on lead status changes.


## Migrations overview (sketch)

- create_crm_sources_table
- create_crm_pipelines_table
- create_crm_stages_table
- create_crm_leads_table
- create_crm_opportunities_table
- create_crm_activities_table
- create_crm_tags_table + create_crm_taggables_table
- create_crm_attachments_table

Indexes to include: email/phone on leads; (activityable_type, activityable_id); stage ordering; owner foreign keys.


## Conversion service (sketch)

Service class: ConvertLeadToClient

- Input: lead_id, target_client_id (optional)
- Steps:
  1) If target_client_id provided, use it; else create a Client from Lead fields.
  2) Set lead.status = converted; lead.client_id = client.id.
  3) Move Ticket requesters from Lead → Client.
  4) Update Opportunities to set client_id; keep lead_id for history.
  5) Log Activity “Converted to Client”.
  6) Return client reference.


## Reporting and KPIs

- Lead funnel by stage and source
- Time-to-first-response vs SLA by owner/source
- Opportunity velocity and win rate
- Revenue forecast (sum of amount x probability)
- Activities completed per owner per period


## Security, privacy, and data retention

- Personally identifiable info confined to Leads/Clients; restrict via policies.
- Soft delete leads; retain activity history.
- Anonymize disqualified leads after retention window when required.


## Phase rollout

- Phase 1 (MVP): Leads + Activities + Sources + simple Pipeline (stages) + sidebar + permissions.
- Phase 2: Opportunities + Pipeline board (Kanban) + Conversion service + Ticket requester integration for Leads.
- Phase 3: Automations (SLA reminders, round-robin), lead scoring, attachments, tags.
- Phase 4: Reports & dashboards, API/webhooks, advanced dedupe/merge.


## Minimal test plan

- Happy path: create lead → add activity → move stage → create opportunity → convert to client → tickets re-link.
- Edge cases:
  - Duplicate lead email detected.
  - Convert into existing client.
  - Lead with multiple opportunities and open tickets converts.
  - Disqualify lead with reason.


## Integration with current codebase (where to wire)

- Models (app/Models): Lead.php, Opportunity.php, Activity.php, Source.php, Pipeline.php, Stage.php, Tag.php, Attachment.php.
- Policies (app/Policies): LeadPolicy.php, OpportunityPolicy.php, ActivityPolicy.php following existing pattern.
- Livewire (app/Livewire/CRM/...): components listed above.
- Routes (routes/web.php): group under /crm with names crm.leads.*, crm.opportunities.*, crm.pipeline.*
- Sidebar (resources/views/components/layouts/app/sidebar.blade.php): add CRM section links.
- Ticket integration: ensure Ticket.requester morph map includes Lead; update views to badge Lead vs Client.


## Success criteria

- Sales can manage leads end-to-end without touching core Client/Booking tables until conversion.
- Tickets can be raised against Leads; upon conversion, they seamlessly point to the new Client.
- Activities provide a single timeline across Leads/Clients/Opportunities/Tickets.
- Admins have visibility through reports; performance remains snappy under typical workloads.


---

Appendix: Suggested enums

- Lead.status: new, working, qualified, disqualified, converted
- Opportunity.status: open, won, lost, on-hold
- Activity.type: call, email, meeting, task, note
