<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPI Dashboard Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #6b7280;
            margin: 5px 0 0 0;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #1e40af;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .metric-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .metric-label {
            color: #6b7280;
            font-size: 14px;
        }
        .package-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .package-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .package-card.silver {
            border-color: #6b7280;
            background: #f9fafb;
        }
        .package-card.gold {
            border-color: #f59e0b;
            background: #fffbeb;
        }
        .package-card.platinum {
            border-color: #8b5cf6;
            background: #faf5ff;
        }
        .package-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .package-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }
        .stat {
            text-align: center;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background: #f8fafc;
            font-weight: bold;
            color: #374151;
        }
        .table tr:nth-child(even) {
            background: #f9fafb;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .chart-placeholder {
            background: #f8fafc;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            color: #6b7280;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Royal Maids Hub - KPI Dashboard Report</h1>
        <p>Generated on {{ $generatedAt->format('F j, Y \a\t g:i A') }}</p>
        <p>Report Period: {{ ucfirst($period) }} ({{ $dateRange }} days)</p>
    </div>

    <!-- Key Metrics Section -->
    <div class="section">
        <h2>Key Performance Indicators</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">{{ number_format($kpis['totalBookings']) }}</div>
                <div class="metric-label">Total Bookings</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($kpis['totalRevenue']) }} UGX</div>
                <div class="metric-label">Total Revenue</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $kpis['bookingCompletionRate'] }}%</div>
                <div class="metric-label">Booking Completion Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $kpis['trainingCompletionRate'] }}%</div>
                <div class="metric-label">Training Completion Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $kpis['maidDeploymentRate'] }}%</div>
                <div class="metric-label">Maid Deployment Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $kpis['averageKpiScore'] }}%</div>
                <div class="metric-label">Average KPI Score</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $kpis['averageFamilySize'] }}</div>
                <div class="metric-label">Average Family Size</div>
            </div>
        </div>
    </div>

    <!-- Package Performance Section -->
    <div class="section">
        <h2>Package Performance Analysis</h2>
        <div class="package-grid">
            @foreach($packageMetrics as $packageName => $metrics)
                <div class="package-card {{ strtolower($packageName) }}">
                    <div class="package-name">{{ $packageName }} Package</div>
                    <div class="package-stats">
                        <div class="stat">
                            <div class="stat-value">{{ $metrics['bookings'] }}</div>
                            <div class="stat-label">Bookings</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">{{ number_format($metrics['revenue']) }}</div>
                            <div class="stat-label">Revenue (UGX)</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">{{ number_format($metrics['avg_revenue']) }}</div>
                            <div class="stat-label">Avg Revenue</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">{{ $metrics['bookings'] > 0 ? round(($metrics['bookings'] / array_sum(array_column($packageMetrics, 'bookings'))) * 100, 1) : 0 }}%</div>
                            <div class="stat-label">Market Share</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top Performers Section -->
    <div class="section">
        <h2>Top Performers</h2>
        
        <h3>Top Trainers</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Trainer Name</th>
                    <th>Evaluations Conducted</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topPerformers['trainers'] as $index => $trainer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $trainer->name }}</td>
                        <td>{{ $trainer->evaluations_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Top Clients</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Client Name</th>
                    <th>Total Bookings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topPerformers['clients'] as $index => $client)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $client->contact_person }}</td>
                        <td>{{ $client->bookings_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Activity Section -->
    <div class="section">
        <h2>Recent Activity Summary</h2>
        
        <h3>Recent Bookings</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivity['bookings'] as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>{{ $booking->client?->contact_person ?? 'Unknown' }}</td>
                        <td>{{ ucfirst($booking->status) }}</td>
                        <td>{{ $booking->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Recent Evaluations</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Maid</th>
                    <th>Trainer</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivity['evaluations'] as $evaluation)
                    <tr>
                        <td>{{ $evaluation->maid?->full_name ?? 'Unknown' }}</td>
                        <td>{{ $evaluation->trainer?->name ?? 'Unknown' }}</td>
                        <td>{{ ucfirst($evaluation->status) }}</td>
                        <td>{{ $evaluation->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Chart Data Section -->
    <div class="section">
        <h2>Performance Trends</h2>
        <div class="chart-placeholder">
            <h3>Monthly Performance Data</h3>
            <p>Chart data available for the last 12 months:</p>
            <ul style="text-align: left; display: inline-block;">
                <li>Bookings: {{ implode(', ', $chartData['bookings']) }}</li>
                <li>Revenue: {{ implode(', ', array_map(fn($r) => number_format($r), $chartData['revenue'])) }}</li>
                <li>Training: {{ implode(', ', $chartData['training']) }}</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>This report was generated automatically by the Royal Maids Hub KPI Dashboard</p>
        <p>For questions or support, contact the system administrator</p>
    </div>
</body>
</html>
