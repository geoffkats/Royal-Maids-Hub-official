<?php

namespace App\Imports;

use App\Models\CRM\Opportunity;
use App\Models\CRM\Stage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;

class OpportunitiesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use Importable;

    protected int $createdBy;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function model(array $row)
    {
        // Allow stage_id directly or lookup by stage_name
        $stageId = $row['stage_id'] ?? null;
        if (!$stageId && !empty($row['stage_name'])) {
            $stage = Stage::where('name', $row['stage_name'])->first();
            $stageId = $stage?->id;
        }

        return new Opportunity([
            'lead_id' => $row['lead_id'] ?? null,
            'client_id' => $row['client_id'] ?? null,
            'stage_id' => $stageId,
            'title' => $row['title'] ?? ($row['name'] ?? 'Untitled Opportunity'),
            'description' => $row['description'] ?? null,
            'amount' => $row['amount'] ?? 0,
            'currency' => $row['currency'] ?? 'USD',
            'probability' => $row['probability'] ?? 25,
            'expected_close_date' => $row['expected_close_date'] ?? null,
            'assigned_to' => $row['assigned_to'] ?? $this->createdBy,
            'package_id' => $row['package_id'] ?? null,
            'created_by' => $this->createdBy,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required_without:name', 'nullable', 'string', 'max:190'],
            'name' => ['nullable', 'string', 'max:190'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'probability' => ['nullable', 'integer', 'between:0,100'],
            'lead_id' => ['nullable', 'integer'],
            'client_id' => ['nullable', 'integer'],
            'stage_id' => ['nullable', 'integer'],
            'assigned_to' => ['nullable', 'integer'],
            'package_id' => ['nullable', 'integer'],
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        // Collect failures
    }

    public function onError(\Throwable $e)
    {
        // Collect errors
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
