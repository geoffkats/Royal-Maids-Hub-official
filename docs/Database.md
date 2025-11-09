# Database & Models

Migrations under database/migrations define tables for Users, Clients, Bookings, Tickets, and CRM entities.

Key CRM tables
- crm_sources, crm_pipelines, crm_stages
- crm_leads, crm_opportunities, crm_activities
- crm_tags, crm_taggables (polymorphic via morphs)
- crm_attachments (polymorphic attachable)
- crm_lead_tag, crm_opportunity_tag (pivot)
- opportunity_stage_history, lead_status_history
- crm_data_transfers (import/export logging)

Morph Map
Configured in App\Providers\AppServiceProvider::boot via Relation::morphMap:
- user, admin, trainer -> App\Models\User
- client -> App\Models\Client
- maid -> App\Models\Maid
- lead -> App\Models\CRM\Lead

Selected Models
- App\Models\CRM\Lead: relationships (owner, source, interestedPackage, client, opportunities, activities, attachments, tags, statusHistory) and helpers (canBeConverted, markAsConverted,...)
- App\Models\CRM\Opportunity: stageHistory
- App\Models\CRM\Activity: related morph (related_type, related_id)
- App\Models\Client: user, bookings, activeBooking, subscription helpers

Data Transfers
- App\Models\CRM\DataTransfer: logs imports/exports with type, entity, format, user_id, counts, and errors[] JSON
