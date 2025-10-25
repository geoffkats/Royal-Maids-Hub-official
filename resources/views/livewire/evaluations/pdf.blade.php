<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluations Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
        }
        
        .header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 12px;
            color: #6b7280;
        }
        
        .meta-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        
        .meta-info table {
            width: 100%;
        }
        
        .meta-info td {
            padding: 3px 5px;
        }
        
        .meta-info .label {
            font-weight: bold;
            width: 150px;
            color: #4b5563;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table.data-table thead {
            background-color: #1e40af;
            color: white;
        }
        
        table.data-table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        table.data-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        table.data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        table.data-table td {
            padding: 8px;
            font-size: 10px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-yellow {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-gray {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
        
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
        }
        
        .summary h3 {
            font-size: 13px;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            display: block;
        }
        
        .summary-item .label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Royal Maids') }}</h1>
        <div class="subtitle">Training Evaluations Report</div>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Report Generated:</td>
                <td>{{ now()->format('F d, Y - h:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Total Evaluations:</td>
                <td>{{ $evaluations->count() }}</td>
            </tr>
            @if($filters['search'] ?? false)
            <tr>
                <td class="label">Search Filter:</td>
                <td>{{ $filters['search'] }}</td>
            </tr>
            @endif
            @if(($filters['status'] ?? 'all') !== 'all')
            <tr>
                <td class="label">Status Filter:</td>
                <td style="text-transform: capitalize;">{{ $filters['status'] }}</td>
            </tr>
            @endif
            @if($filters['archived'] ?? false)
            <tr>
                <td class="label">View:</td>
                <td>Archived Evaluations</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Summary Statistics --}}
    @if($evaluations->isNotEmpty())
    <div class="summary">
        <h3>Summary Statistics</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="value">{{ number_format($evaluations->avg('overall_rating'), 1) }}</span>
                <span class="label">Average Rating</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ $evaluations->where('status', 'approved')->count() }}</span>
                <span class="label">Approved</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ $evaluations->where('status', 'pending')->count() }}</span>
                <span class="label">Pending</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Evaluations Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Maid</th>
                <th style="width: 15%;">Trainer</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Rating</th>
                <th style="width: 13%;">Program</th>
                <th style="width: 25%;">Comments</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evaluations as $evaluation)
            <tr>
                <td>
                    {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}
                    @if($evaluation->archived)
                        <span class="badge badge-gray" style="margin-left: 5px;">Archived</span>
                    @endif
                </td>
                <td>{{ $evaluation->trainer?->user?->name }}</td>
                <td>{{ $evaluation->evaluation_date?->format('M d, Y') }}</td>
                <td>
                    <span class="badge badge-{{ $evaluation->getStatusBadgeColor() }}">
                        {{ $evaluation->status_label }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $evaluation->getScoreBadgeColor($evaluation->overall_rating ?? 0) }}">
                        {{ number_format($evaluation->overall_rating ?? 0, 1) }}/5.0
                    </span>
                </td>
                <td>
                    @if($evaluation->program)
                        {{ $evaluation->program->program_type }}
                    @else
                        —
                    @endif
                </td>
                <td style="font-size: 9px;">
                    {{ Str::limit($evaluation->general_comments, 80) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">
                    No evaluations found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated by {{ config('app.name', 'Royal Maids') }} - {{ config('app.url') }}</p>
        <p>This is an automatically generated report. Page {{ $evaluations->currentPage() }} of {{ $evaluations->lastPage() }}</p>
    </div>
</body>
</html>

