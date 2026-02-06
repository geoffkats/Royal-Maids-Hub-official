<?php

use App\Models\Booking;
use App\Models\ClientEvaluationLink;
use App\Models\ClientEvaluationResponse;
use App\Models\User;
use Illuminate\Support\Str;

it('admin can view client feedback list', function () {
    $admin = User::factory()->admin()->create();
    $booking = Booking::factory()->create();

    $link = ClientEvaluationLink::create([
        'booking_id' => $booking->id,
        'token' => Str::random(40),
        'status' => 'completed',
    ]);

    $response = ClientEvaluationResponse::create([
        'client_evaluation_link_id' => $link->id,
        'booking_id' => $booking->id,
        'client_id' => $booking->client_id,
        'maid_id' => $booking->maid_id,
        'package_id' => $booking->package_id,
        'respondent_name' => 'Client Tester',
        'respondent_email' => 'client@example.com',
        'answers' => ['1' => 5],
        'overall_rating' => 5.0,
        'submitted_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('client-feedback.index'))
        ->assertOk()
        ->assertSee('Client Tester')
        ->assertSee((string) $response->overall_rating);
});
