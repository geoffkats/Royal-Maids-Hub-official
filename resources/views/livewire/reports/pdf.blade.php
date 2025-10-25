<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 4px solid #2563eb;
        }
        
        .header h1 {
            font-size: 26px;
            color: #1e40af;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .header .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 5px;
        }
        
        .period-info {
            background-color: #eff6ff;
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #2563eb;
            font-size: 11px;
        }
        
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 25px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: #f9fafb;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .stat-value {
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 6px;
        }
        
        .stat-sub {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9px;
        }
        
        table thead {
            background-color: #1e40af;
            color: white;
        }
        
        table th {
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 8px;
            text-transform: uppercase;
        }
        
        table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        table td {
            padding: 7px 6px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 8px;
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
        
        .summary-box {
            background-color: #f0fdf4;
            border: 2px solid #22c55e;
            padding: 15px;
            margin: 15px 0;
            border-radius: 6px;
        }
        
        .summary-box h3 {
            font-size: 13px;
            color: #15803d;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #15803d;
        }
        
        .summary-item .label {
            font-size: 8px;
            color: #4b5563;
            margin-top: 2px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .two-col {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Royal Maids') }}</h1>
        <div class="subtitle">Comprehensive Management Report</div>
    </div>

    <div class="period-info">
        <strong>Report Period:</strong> 
        {{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('F d, Y') }}
        <br>
        <strong>Generated:</strong> {{ now()->format('F d, Y - h:i A') }}
        <br>
        <strong>Report Type:</strong> {{ ucfirst($reportType) }}
    </div>

    {{-- EXECUTIVE SUMMARY --}}
    <div class="summary-box">
        <h3>Executive Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="value">{{ number_format($totalUsers) }}</div>
                <div class="label">Total Users</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format($totalMaids) }}</div>
                <div class="label">Total Maids</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format($totalBookings) }}</div>
                <div class="label">Total Bookings</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format($totalEvaluations) }}</div>
                <div class="label">Evaluations</div>
            </div>
        </div>
    </div>

    {{-- KEY METRICS --}}
    <h2 class="section-title">Key Performance Metrics</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Clients</div>
            <div class="stat-value">{{ number_format($totalClients) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Training Programs</div>
            <div class="stat-value">{{ number_format($totalPrograms) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Average Rating</div>
            <div class="stat-value">{{ number_format($averageRating ?? 0, 2) }}/5.0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">UGX {{ number_format($totalRevenue) }}</div>
            <div class="stat-sub">Selected period</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Trainers</div>
            <div class="stat-value">{{ number_format($activeTrainers) }}</div>
            <div class="stat-sub">of {{ number_format($totalTrainers) }} total</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved Evals</div>
            <div class="stat-value">{{ number_format($evaluationsByStatus['approved'] ?? 0) }}</div>
        </div>
    </div>

    {{-- EVALUATION ANALYTICS --}}
    <h2 class="section-title">Evaluation Analytics</h2>
    
    <div class="two-col">
        <div>
            <h4 style="font-size: 11px; margin-bottom: 8px;">By Status</h4>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th style="text-align: right;">Count</th>
                        <th style="text-align: right;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $evalTotal = array_sum($evaluationsByStatus);
                    @endphp
                    @foreach ($evaluationsByStatus as $status => $count)
                        <tr>
                            <td>{{ ucfirst($status) }}</td>
                            <td style="text-align: right;">{{ number_format($count) }}</td>
                            <td style="text-align: right;">{{ $evalTotal > 0 ? number_format(($count / $evalTotal) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div>
            <h4 style="font-size: 11px; margin-bottom: 8px;">By Rating</h4>
            <table>
                <thead>
                    <tr>
                        <th>Rating</th>
                        <th style="text-align: right;">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evaluationsByRating as $item)
                        <tr>
                            <td>{{ $item->rating }} Stars</td>
                            <td style="text-align: right;">{{ number_format($item->count) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; padding: 15px;">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    {{-- TOP PERFORMERS --}}
    <h2 class="section-title">Top Rated Maids</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Maid Name</th>
                <th style="text-align: center;">Evaluations</th>
                <th style="text-align: center;">Average Rating</th>
                <th style="text-align: center;">Performance</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topRatedMaids as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->maid?->first_name }} {{ $item->maid?->last_name }}</td>
                    <td style="text-align: center;">{{ $item->eval_count }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($item->avg_rating, 2) }}/5.0</td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $item->avg_rating >= 4.5 ? 'green' : ($item->avg_rating >= 4.0 ? 'blue' : 'yellow') }}">
                            {{ $item->avg_rating >= 4.5 ? 'Excellent' : ($item->avg_rating >= 4.0 ? 'Good' : 'Average') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No evaluation data available for the selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- MAID STATUS BREAKDOWN --}}
    <h2 class="section-title">Maid Distribution</h2>
    
    <div class="two-col">
        <div>
            <h4 style="font-size: 11px; margin-bottom: 8px;">By Status</h4>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th style="text-align: right;">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maidsByStatus as $status => $count)
                        <tr>
                            <td>{{ ucfirst($status) }}</td>
                            <td style="text-align: right;">{{ number_format($count) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div>
            <h4 style="font-size: 11px; margin-bottom: 8px;">By Role</h4>
            <table>
                <thead>
                    <tr>
                        <th>Role</th>
                        <th style="text-align: right;">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maidsByRole as $role => $count)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $role)) }}</td>
                            <td style="text-align: right;">{{ number_format($count) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- BOOKING BREAKDOWN --}}
    <h2 class="section-title">Booking Analytics</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th style="text-align: right;">Count</th>
                <th style="text-align: right;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @php
                $bookingTotal = array_sum($bookingsByStatus);
            @endphp
            @foreach ($bookingsByStatus as $status => $count)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td style="text-align: right;">{{ number_format($count) }}</td>
                    <td style="text-align: right;">{{ $bookingTotal > 0 ? number_format(($count / $bookingTotal) * 100, 1) : 0 }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- RECENT EVALUATIONS --}}
    <h2 class="section-title">Recent Evaluations</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Maid</th>
                <th>Trainer</th>
                <th style="text-align: center;">Rating</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentEvaluations->take(15) as $evaluation)
                <tr>
                    <td>{{ $evaluation->evaluation_date?->format('M d, Y') }}</td>
                    <td>{{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}</td>
                    <td>{{ $evaluation->trainer?->user?->name }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($evaluation->overall_rating ?? 0, 1) }}/5.0</td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $evaluation->getStatusBadgeColor() }}">
                            {{ $evaluation->status_label }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No recent evaluations.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>{{ config('app.name', 'Royal Maids') }}</strong> - {{ config('app.url') }}</p>
        <p>This is a computer-generated report. Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>Confidential - For internal use only</p>
    </div>
</body>
</html>

