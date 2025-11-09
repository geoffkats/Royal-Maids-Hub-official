# Integrations

- Laravel Excel (maatwebsite/excel):
  - Exports: LeadsExport, OpportunitiesExport, ActivitiesExport (responsable downloads as xlsx)
  - Imports: LeadsImport, OpportunitiesImport (ToModel, etc.) triggered by DataTransferController
- Livewire & Volt: For reactive UI and settings pages
- Fortify: Auth and two-factor routes via Volt
- Blade Currency Directives: @currency, @currencySymbol backed by App\Helpers\CurrencyHelper
