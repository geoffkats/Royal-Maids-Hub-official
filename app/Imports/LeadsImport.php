<?php

namespace App\Imports;

use App\Models\CRM\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use Importable;

    protected int $createdBy;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function model(array $row)
    {
        return new Lead([
            'first_name' => $row['first_name'] ?? null,
            'last_name' => $row['last_name'] ?? null,
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'company' => $row['company'] ?? null,
            'job_title' => $row['job_title'] ?? null,
            'industry' => $row['industry'] ?? null,
            'city' => $row['city'] ?? null,
            'address' => $row['address'] ?? null,
            'source_id' => $row['source_id'] ?? null,
            'owner_id' => $row['owner_id'] ?? $this->createdBy,
            'status' => $row['status'] ?? 'new',
            'score' => $row['score'] ?? null,
            'notes' => $row['notes'] ?? null,
            'client_id' => $row['client_id'] ?? null,
            'last_contacted_at' => $row['last_contacted_at'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required_without:last_name', 'nullable', 'string', 'max:120'],
            'last_name' => ['required_without:first_name', 'nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'in:new,contacted,qualified,unqualified,converted'],
            'source_id' => ['nullable', 'integer'],
            'owner_id' => ['nullable', 'integer'],
            'client_id' => ['nullable', 'integer'],
            'score' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        // Failures collected; caller can read via $import->failures()
    }

    public function onError(\Throwable $e)
    {
        // Errors collected; caller can read via $import->errors()
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
