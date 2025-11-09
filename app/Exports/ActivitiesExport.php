<?php

namespace App\Exports;

use App\Models\CRM\Activity;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\ShouldQueue; // disabled to simplify testing

class ActivitiesExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    public string $fileName = 'activities_export.xlsx';

    public function __construct(
        protected ?array $filters = null
    ) {}

    public function query()
    {
        $q = Activity::query()->with(['assignedTo', 'owner']);

        if ($this->filters) {
            if (!empty($this->filters['status'])) {
                $q->where('status', $this->filters['status']);
            }
            if (!empty($this->filters['assigned_to'])) {
                $q->where('assigned_to', $this->filters['assigned_to']);
            }
            if (!empty($this->filters['from']) && !empty($this->filters['to'])) {
                $q->whereBetween('due_date', [$this->filters['from'], $this->filters['to']]);
            }
        }

        return $q;
    }

    public function headings(): array
    {
        return [
            'ID', 'Type', 'Subject', 'Description', 'Status', 'Priority', 'Outcome', 'Due Date', 'Completed At',
            'Assigned To', 'Owner', 'Related Type', 'Related ID', 'Created At', 'Updated At'
        ];
    }

    public function map($a): array
    {
        return [
            $a->id,
            $a->type,
            $a->subject,
            $a->description,
            $a->status,
            $a->priority,
            $a->outcome,
            optional($a->due_date)?->toDateTimeString(),
            optional($a->completed_at)?->toDateTimeString(),
            $a->assignedTo?->name,
            $a->owner?->name,
            $a->related_type,
            $a->related_id,
            optional($a->created_at)?->toDateTimeString(),
            optional($a->updated_at)?->toDateTimeString(),
        ];
    }
}
