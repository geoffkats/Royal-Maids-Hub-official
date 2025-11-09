<?php

namespace App\Http\Controllers\CRM;

use App\Exports\ActivitiesExport;
use App\Exports\LeadsExport;
use App\Exports\OpportunitiesExport;
use App\Http\Controllers\Controller;
use App\Imports\LeadsImport;
use App\Imports\OpportunitiesImport;
use App\Models\CRM\DataTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DataTransferController extends Controller
{
    public function exportLeads(Request $request)
    {
        $this->authorize('viewAny', \App\Models\CRM\Lead::class);
        $filters = $request->only(['status', 'owner_id', 'source_id', 'search']);

        $log = DataTransfer::create([
            'type' => 'export',
            'entity' => 'leads',
            'format' => 'xlsx',
            'user_id' => Auth::id(),
            'status' => 'running',
        ]);

        try {
            $export = new LeadsExport($filters);
            $fileName = 'leads_export_' . now()->format('Ymd_His') . '.xlsx';
            $log->update(['file_name' => $fileName]);
            return Excel::download($export, $fileName);
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'errors' => ['message' => $e->getMessage()]]);
            return back()->withErrors(['export' => 'Failed to export leads: ' . $e->getMessage()]);
        } finally {
            $log->update(['status' => 'completed']);
        }
    }

    public function exportOpportunities(Request $request)
    {
        $this->authorize('viewAny', \App\Models\CRM\Opportunity::class);
        $filters = $request->only(['stage_id', 'assigned_to', 'from', 'to']);

        $log = DataTransfer::create([
            'type' => 'export',
            'entity' => 'opportunities',
            'format' => 'xlsx',
            'user_id' => Auth::id(),
            'status' => 'running',
        ]);

        try {
            $export = new OpportunitiesExport($filters);
            $fileName = 'opportunities_export_' . now()->format('Ymd_His') . '.xlsx';
            $log->update(['file_name' => $fileName]);
            return Excel::download($export, $fileName);
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'errors' => ['message' => $e->getMessage()]]);
            return back()->withErrors(['export' => 'Failed to export opportunities: ' . $e->getMessage()]);
        } finally {
            $log->update(['status' => 'completed']);
        }
    }

    public function exportActivities(Request $request)
    {
        $this->authorize('viewAny', \App\Models\CRM\Activity::class);
        $filters = $request->only(['status', 'assigned_to', 'from', 'to']);

        $log = DataTransfer::create([
            'type' => 'export',
            'entity' => 'activities',
            'format' => 'xlsx',
            'user_id' => Auth::id(),
            'status' => 'running',
        ]);

        try {
            $export = new ActivitiesExport($filters);
            $fileName = 'activities_export_' . now()->format('Ymd_His') . '.xlsx';
            $log->update(['file_name' => $fileName]);
            return Excel::download($export, $fileName);
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'errors' => ['message' => $e->getMessage()]]);
            return back()->withErrors(['export' => 'Failed to export activities: ' . $e->getMessage()]);
        } finally {
            $log->update(['status' => 'completed']);
        }
    }

    public function importLeads(Request $request)
    {
        $this->authorize('create', \App\Models\CRM\Lead::class);
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt',
        ]);

        $path = $request->file('file')->store('imports');

        $log = DataTransfer::create([
            'type' => 'import',
            'entity' => 'leads',
            'format' => $request->file('file')->getClientOriginalExtension(),
            'file_path' => $path,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'user_id' => Auth::id(),
            'status' => 'running',
        ]);

        try {
            $import = new LeadsImport(Auth::id());
            $import->import($path);

            $failures = method_exists($import, 'failures') ? $import->failures() : [];
            // Best-effort row counting for CSV/TXT; fallback to 0 for XLSX
            $totalRows = 0;
            try {
                $fullPath = Storage::path($path);
                if (Str::endsWith(strtolower($fullPath), ['.csv', '.txt']) && is_file($fullPath)) {
                    $lines = @file($fullPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
                    // subtract header if present
                    $totalRows = max(count($lines) - 1, 0);
                }
            } catch (\Throwable $e) {
                // Ignore counting errors; keep defaults
            }
            $failureCount = is_countable($failures) ? count($failures) : 0;
            $successCount = $totalRows > 0 ? max($totalRows - $failureCount, 0) : 0;
            $log->update([
                'status' => 'completed',
                'total_rows' => $totalRows,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'errors' => collect($failures)->map(function ($f) {
                    return [
                        'row' => $f->row(),
                        'attribute' => $f->attribute(),
                        'errors' => $f->errors(),
                        'values' => $f->values(),
                    ];
                })->values()->all(),
            ]);

            return back()->with('message', 'Leads imported successfully.');
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'errors' => ['message' => $e->getMessage()]]);
            return back()->withErrors(['import' => 'Failed to import leads: ' . $e->getMessage()]);
        }
    }

    public function importOpportunities(Request $request)
    {
        $this->authorize('create', \App\Models\CRM\Opportunity::class);
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt',
        ]);

        $path = $request->file('file')->store('imports');

        $log = DataTransfer::create([
            'type' => 'import',
            'entity' => 'opportunities',
            'format' => $request->file('file')->getClientOriginalExtension(),
            'file_path' => $path,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'user_id' => Auth::id(),
            'status' => 'running',
        ]);

        try {
            $import = new OpportunitiesImport(Auth::id());
            $import->import($path);

            $failures = method_exists($import, 'failures') ? $import->failures() : [];
            // Best-effort row counting for CSV/TXT; fallback to 0 for XLSX
            $totalRows = 0;
            try {
                $fullPath = Storage::path($path);
                if (Str::endsWith(strtolower($fullPath), ['.csv', '.txt']) && is_file($fullPath)) {
                    $lines = @file($fullPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
                    // subtract header if present
                    $totalRows = max(count($lines) - 1, 0);
                }
            } catch (\Throwable $e) {
                // Ignore counting errors; keep defaults
            }
            $failureCount = is_countable($failures) ? count($failures) : 0;
            $successCount = $totalRows > 0 ? max($totalRows - $failureCount, 0) : 0;
            $log->update([
                'status' => 'completed',
                'total_rows' => $totalRows,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'errors' => collect($failures)->map(function ($f) {
                    return [
                        'row' => $f->row(),
                        'attribute' => $f->attribute(),
                        'errors' => $f->errors(),
                        'values' => $f->values(),
                    ];
                })->values()->all(),
            ]);

            return back()->with('message', 'Opportunities imported successfully.');
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'errors' => ['message' => $e->getMessage()]]);
            return back()->withErrors(['import' => 'Failed to import opportunities: ' . $e->getMessage()]);
        }
    }
}
