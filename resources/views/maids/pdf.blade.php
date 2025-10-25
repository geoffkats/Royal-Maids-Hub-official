<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maids List</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 20px; margin: 0 0 10px 0; }
        .muted { color: #6B7280; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #E5E7EB; padding: 6px 8px; }
        th { background: #F3F4F6; text-align: left; font-weight: 700; font-size: 12px; }
        tr:nth-child(even) td { background: #FAFAFA; }
        .small { font-size: 11px; }
    </style>
    </head>
<body>
    <h1>Royal Maids — All Maids</h1>
    <div class="muted">Generated: {{ $generatedAt->format('Y-m-d H:i') }}</div>

    <table>
        <thead>
        <tr>
            <th style="width: 12%">Code</th>
            <th style="width: 24%">Name</th>
            <th style="width: 18%">Phone</th>
            <th style="width: 18%">Status</th>
            <th style="width: 14%">Role</th>
            <th style="width: 14%">Arrived</th>
        </tr>
        </thead>
        <tbody>
        @forelse($maids as $maid)
            <tr>
                <td class="small">{{ $maid->maid_code ?: '—' }}</td>
                <td>{{ $maid->full_name }}</td>
                <td class="small">{{ $maid->phone }}</td>
                <td class="small">{{ ucfirst(str_replace('-', ' ', $maid->status)) }}</td>
                <td class="small">{{ ucfirst(str_replace('_', ' ', $maid->role)) }}</td>
                <td class="small">{{ optional($maid->date_of_arrival)->format('Y-m-d') ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 12px;">No maids found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
