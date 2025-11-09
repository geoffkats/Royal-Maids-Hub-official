# CRM — Import & Export

Controller: App/Http/Controllers/CRM/DataTransferController

Exports (xlsx)
- Leads: exportLeads
- Opportunities: exportOpportunities
- Activities: exportActivities

Imports
- Leads: importLeads
- Opportunities: importOpportunities

Logging
- Every import/export creates crm_data_transfers record with file info and status
- On import completion, counts default to zero-safe values; for CSV/TXT, lines are counted to estimate total_rows

Tests
- See docs/Testing.md and tests/Feature/CRM/ImportExportTest.php
