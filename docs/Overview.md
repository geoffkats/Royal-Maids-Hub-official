# Overview

RoyalMaidsHub V3.0 is a Laravel + Livewire application for managing cleaning services operations and CRM. It includes:
- CRM (Leads, Opportunities, Activities, Tags, Reports)
- Service Ticketing (Tickets, SLA, Comments, Attachments, Status history)
- Workforce (Maids, Trainers, Programs, Evaluations, Deployments, Schedule)
- Client Management and Subscriptions
- Bookings and Packages
- Dashboards for Admin, Trainer, and Client

Core tech:
- Laravel, Livewire, Volt for settings
- MySQL
- Laravel Excel (maatwebsite/excel) for imports/exports
- Pest + PHPUnit for testing

Authentication: Laravel Fortify. Authorization: Policies + role middleware. Polymorphic relations use a global morph map.
