# Testing Strategy

Framework: Pest + PHPUnit. Livewire testing helpers used for components.

Key Feature Tests
- tests/Feature/CRM/ConversionTest.php
  - Converts a qualified lead into a new client
  - Converts a qualified lead by attaching to an existing client
  - Prevents converting a non-convertible lead (flashes error)

- tests/Feature/CRM/ImportExportTest.php
  - Exports leads/opportunities/activities as xlsx
  - Imports leads/opportunities from minimal CSV; success message asserted

Conventions
- Users in tests assigned role and marked verified (email_verified_at) to pass middleware
- For imports, DataTransferController sets counts to zero-safe values to satisfy NOT NULL columns
- Export classes avoid ShouldQueue in test context to reduce infra setup

Running
- php artisan test
- Focus: php artisan test --filter=ConversionTest
