<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contract Summary</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <h2>Royal Maids Hub - Contract Summary</h2>

    <p>Hello {{ $client?->contact_person ?? 'Client' }},</p>

    <p>Here is a summary of the contract for {{ $maid?->full_name ?? 'your maid' }}:</p>

    <ul>
        <li><strong>Contract Type:</strong> {{ $contract->contract_type ?? 'N/A' }}</li>
        <li><strong>Start Date:</strong> {{ $contract->contract_start_date?->format('M d, Y') ?? 'N/A' }}</li>
        <li><strong>End Date:</strong> {{ $contract->contract_end_date?->format('M d, Y') ?? 'N/A' }}</li>
        <li><strong>Status:</strong> {{ ucfirst($contract->contract_status ?? 'N/A') }}</li>
        <li><strong>Days Worked:</strong> {{ $contract->worked_days ?? 0 }}</li>
        <li><strong>Days Pending:</strong> {{ $contract->pending_days ?? 0 }}</li>
    </ul>

    <p>If you need any clarification, please contact Royal Maids Hub support.</p>

    <p>Thank you,<br />Royal Maids Hub Team</p>
</body>
</html>
