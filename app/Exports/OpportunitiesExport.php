<?php

namespace App\Exports;

use App\Models\CRM\Opportunity;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\ShouldQueue; // disabled to simplify testing

class OpportunitiesExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    public string $fileName = 'opportunities_export.xlsx';

    public function __construct(
        protected ?array $filters = null
    ) {}

    public function query()
    {
        $q = Opportunity::query()->with(['lead', 'client', 'stage', 'assignedTo', 'package']);

        if ($this->filters) {
            if (!empty($this->filters['stage_id'])) {
                $q->where('stage_id', $this->filters['stage_id']);
            }
            if (!empty($this->filters['assigned_to'])) {
                $q->where('assigned_to', $this->filters['assigned_to']);
            }
            if (!empty($this->filters['from']) && !empty($this->filters['to'])) {
                $q->whereBetween('created_at', [$this->filters['from'], $this->filters['to']]);
            }
        }

        return $q;
    }

    public function headings(): array
    {
        return [
            'ID', 'Title', 'Description', 'Amount', 'Currency', 'Probability', 'Stage', 'Lead', 'Client',
            'Assigned To', 'Package', 'Expected Close', 'Close Date', 'Won At', 'Lost At', 'Loss Reason', 'Loss Notes',
            'Created At', 'Updated At'
        ];
    }

    public function map($opp): array
    {
        return [
            $opp->id,
            $opp->title,
            $opp->description,
            $opp->amount,
            $opp->currency,
            $opp->probability,
            $opp->stage?->name,
            $opp->lead?->full_name,
            $opp->client?->name,
            $opp->assignedTo?->name,
            $opp->package?->name,
            optional($opp->expected_close_date)?->toDateString(),
            optional($opp->close_date)?->toDateString(),
            optional($opp->won_at)?->toDateTimeString(),
            optional($opp->lost_at)?->toDateTimeString(),
            $opp->loss_reason,
            $opp->loss_notes,
            optional($opp->created_at)?->toDateTimeString(),
            optional($opp->updated_at)?->toDateTimeString(),
        ];
    }
}
