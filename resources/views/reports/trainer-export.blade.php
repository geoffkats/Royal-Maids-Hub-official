<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Performance Report</title>
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
            border-bottom: 3px solid #F5B301;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #512B58;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #6b7280;
            margin: 5px 0 0 0;
        }
        .trainer-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .trainer-info h2 {
            color: #512B58;
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #512B58;
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
            color: #512B58;
            margin-bottom: 5px;
        }
        .metric-label {
            color: #6b7280;
            font-size: 14px;
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
        .score-breakdown {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .score-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .score-card.personality {
            border-color: #3b82f6;
        }
        .score-card.behavior {
            border-color: #10b981;
        }
        .score-card.performance {
            border-color: #8b5cf6;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Royal Maids Hub - Trainer Performance Report</h1>
        <p>Generated on {{ $generatedAt->format('F j, Y \a\t g:i A') }}</p>
        <p>Report Period: {{ ucfirst($period) }}</p>
    </div>

    <!-- Trainer Information -->
    @if($trainer)
    <div class="trainer-info">
        <h2>Trainer Information</h2>
        <p><strong>Name:</strong> {{ $trainer->name ?? 'N/A' }}</p>
        <p><strong>Specialization:</strong> {{ $trainer->specialization ?? 'N/A' }}</p>
        <p><strong>Experience:</strong> {{ $trainer->experience_years ?? 'N/A' }} years</p>
    </div>
    @endif

    <!-- Key Metrics Section -->
    <div class="section">
        <h2>Key Performance Indicators</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">{{ number_format($data['totalEvaluations']) }}</div>
                <div class="metric-label">Total Evaluations</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($data['totalTrainingPrograms']) }}</div>
                <div class="metric-label">Training Programs</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($data['totalTrainees']) }}</div>
                <div class="metric-label">Total Trainees</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($data['totalHours']) }}</div>
                <div class="metric-label">Total Hours Trained</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($data['averageOverallRating'], 1) }}/5</div>
                <div class="metric-label">Average Rating</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $data['evaluationCompletionRate'] }}%</div>
                <div class="metric-label">Evaluation Completion Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $data['programCompletionRate'] }}%</div>
                <div class="metric-label">Program Completion Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $data['approvalRate'] }}%</div>
                <div class="metric-label">Approval Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $data['trainingEfficiency'] }}%</div>
                <div class="metric-label">Training Efficiency</div>
            </div>
        </div>
    </div>

    <!-- Score Breakdown Section -->
    <div class="section">
        <h2>Score Breakdown</h2>
        <div class="score-breakdown">
            <div class="score-card personality">
                <div class="metric-value">{{ number_format($data['personalityAverage'], 1) }}/5</div>
                <div class="metric-label">Personality Average</div>
            </div>
            <div class="score-card behavior">
                <div class="metric-value">{{ number_format($data['behaviorAverage'], 1) }}/5</div>
                <div class="metric-label">Behavior Average</div>
            </div>
            <div class="score-card performance">
                <div class="metric-value">{{ number_format($data['performanceAverage'], 1) }}/5</div>
                <div class="metric-label">Performance Average</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>This report was generated automatically by the Royal Maids Hub Trainer Performance Dashboard</p>
        <p>For questions or support, contact the system administrator</p>
    </div>
</body>
</html>

