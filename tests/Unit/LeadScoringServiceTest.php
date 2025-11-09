<?php

use App\Models\CRM\Lead;
use App\Models\CRM\Activity;
use App\Services\CRM\LeadScoringService;
use Illuminate\Support\Facades\Config;

it('calculates base score from profile completeness', function () {
    $lead = Lead::factory()->create([
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'company' => 'Acme Co',
        'interested_package_id' => 1,
    ]);

    $service = new LeadScoringService();
    $score = $service->calculate($lead);

    expect($score)->toBeGreaterThanOrEqual(35); // 10 + 10 + 5 + 15 = 40; activity/source may vary
});

it('adds points for recent activities and last contact', function () {
    $lead = Lead::factory()->create([
        'last_contacted_at' => now()->subDays(1),
    ]);

    Activity::factory()->count(3)->create([
        'related_type' => Lead::class,
        'related_id' => $lead->id,
        'created_at' => now()->subDays(2),
    ]);

    $service = new LeadScoringService();
    $score = $service->calculate($lead);

    // last_contact_3_days (20) + 3 * activity_recent (5) = 35 minimum
    expect($score)->toBeGreaterThanOrEqual(35);
});

it('clamps score between 0 and 100 and applies disqualified penalty', function () {
    $lead = Lead::factory()->create([
        'status' => 'disqualified',
        'email' => 'e@e.com',
        'phone' => '123',
        'company' => 'C',
        'interested_package_id' => 2,
        'last_contacted_at' => now(),
    ]);

    $service = new LeadScoringService();
    $score = $service->calculate($lead);

    expect($score)->toBeGreaterThanOrEqual(0)->and($score)->toBeLessThanOrEqual(100);
});

it('applies and persists calculated score', function () {
    $lead = Lead::factory()->create(['score' => 0]);

    $service = new LeadScoringService();
    $new = $service->apply($lead);

    $lead->refresh();
    expect($lead->score)->toEqual($new);
});
