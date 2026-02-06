<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contract Summary</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f5fb; font-family: Arial, sans-serif; color: #1f2937;">
    <div style="max-width: 640px; margin: 0 auto; padding: 24px;">
        <div style="background-color: #512B58; border-radius: 16px 16px 0 0; padding: 24px; color: #ffffff;">
            <p style="margin: 0; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; color: #D1C4E9;">Royal Maids Hub</p>
            <h1 style="margin: 8px 0 0; font-size: 24px;">Contract Summary</h1>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e7e1f0; border-top: none; padding: 24px;">
            <p style="margin: 0 0 12px;">Hello {{ $client?->contact_person ?? 'Client' }},</p>
            <p style="margin: 0 0 20px; color: #4b5563;">Here is a summary of the contract for {{ $maid?->full_name ?? 'your maid' }}.</p>

            <div style="border: 1px solid #F5B301; border-radius: 12px; padding: 16px; background-color: #faf7ff;">
                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 13px;">Contract Type</td>
                        <td style="padding: 6px 0; text-align: right; font-weight: 600; color: #111827;">{{ $contract->contract_type ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 13px;">Start Date</td>
                        <td style="padding: 6px 0; text-align: right; color: #111827;">{{ $contract->contract_start_date?->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 13px;">End Date</td>
                        <td style="padding: 6px 0; text-align: right; color: #111827;">{{ $contract->contract_end_date?->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 13px;">Status</td>
                        <td style="padding: 6px 0; text-align: right;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 999px; background-color: #F5B301; color: #3B0A45; font-size: 12px; font-weight: 700;">
                                {{ ucfirst($contract->contract_status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 13px;">Days Worked</td>
                        <td style="padding: 6px 0; text-align: right; color: #111827;">{{ $contract->worked_days ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 13px;">Days Pending</td>
                        <td style="padding: 6px 0; text-align: right; color: #111827;">{{ $contract->pending_days ?? 0 }}</td>
                    </tr>
                </table>
            </div>

            <p style="margin: 20px 0 0; color: #4b5563;">If you need any clarification, please contact Royal Maids Hub support.</p>

            <p style="margin: 16px 0 0;">Thank you,<br />Royal Maids Hub Team</p>
        </div>

        <div style="background-color: #3B0A45; color: #D1C4E9; padding: 16px 24px; border-radius: 0 0 16px 16px; font-size: 12px;">
            <p style="margin: 0 0 6px;">Mpelerwe Mugalu Zone, Kampala, Uganda</p>
            <p style="margin: 0 0 6px;">info@royalmaidshub.com | +256 703 173206</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} Royal Maids Hub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
