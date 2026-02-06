<?php

use App\Models\Deployment;
use App\Models\EvaluationTask;
use Carbon\Carbon;

test('creates evaluation tasks for deployment intervals', function () {
    Carbon::setTestNow('2025-01-01');

    $deployment = Deployment::factory()->create([
        'deployment_date' => '2025-01-01',
    ]);

    EvaluationTask::createForDeployment($deployment);

    expect(EvaluationTask::where('deployment_id', $deployment->id)->count())->toBe(3);

    $this->assertDatabaseHas('evaluation_tasks', [
        'deployment_id' => $deployment->id,
        'interval_months' => 3,
        'due_date' => '2025-04-01',
    ]);

    $this->assertDatabaseHas('evaluation_tasks', [
        'deployment_id' => $deployment->id,
        'interval_months' => 6,
        'due_date' => '2025-07-01',
    ]);

    $this->assertDatabaseHas('evaluation_tasks', [
        'deployment_id' => $deployment->id,
        'interval_months' => 12,
        'due_date' => '2026-01-01',
    ]);
});
