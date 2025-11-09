<?php

namespace App\Exports;

use App\Models\CRM\Lead;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\ShouldQueue; // disabled to simplify testing

class LeadsExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    public string $fileName = 'leads_export.xlsx';

    public function __construct(
        protected ?array $filters = null
    ) {}

    public function query()
    {
        $q = Lead::query()->with(['owner', 'source', 'client']);

        if ($this->filters) {
            if (!empty($this->filters['status'])) {
                $q->where('status', $this->filters['status']);
            }
            if (!empty($this->filters['owner_id'])) {
                $q->where('owner_id', $this->filters['owner_id']);
            }
            if (!empty($this->filters['source_id'])) {
                $q->where('source_id', $this->filters['source_id']);
            }
            if (!empty($this->filters['search'])) {
                $s = $this->filters['search'];
                $q->where(function ($w) use ($s) {
                    $w->where('first_name', 'like', "%$s%")
                      ->orWhere('last_name', 'like', "%$s%")
                      ->orWhere('email', 'like', "%$s%")
                      ->orWhere('company', 'like', "%$s%");
                });
            }
        }

        return $q;
    }

    public function headings(): array
    {
        return [
            'ID', 'First Name', 'Last Name', 'Full Name', 'Email', 'Phone', 'Company', 'Job Title',
            'Industry', 'City', 'Address', 'Source', 'Owner', 'Status', 'Score', 'Interested Package',
            'Notes', 'Client', 'Converted At', 'Disqualified At', 'Disqualified Reason', 'Last Contacted At',
            'Created At', 'Updated At',
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->first_name,
            $lead->last_name,
            $lead->full_name,
            $lead->email,
            $lead->phone,
            $lead->company,
            $lead->job_title,
            $lead->industry,
            $lead->city,
            $lead->address,
            $lead->source?->name,
            $lead->owner?->name,
            $lead->status,
            $lead->score,
            $lead->interestedPackage?->name,
            $lead->notes,
            $lead->client?->name,
            optional($lead->converted_at)?->toDateTimeString(),
            optional($lead->disqualified_at)?->toDateTimeString(),
            $lead->disqualified_reason,
            optional($lead->last_contacted_at)?->toDateTimeString(),
            optional($lead->created_at)?->toDateTimeString(),
            optional($lead->updated_at)?->toDateTimeString(),
        ];
    }
}
