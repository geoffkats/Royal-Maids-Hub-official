<?php

use App\Livewire\ClientEvaluations\PublicForm;
use App\Models\Booking;
use App\Models\ClientEvaluationLink;
use App\Models\ClientEvaluationQuestion;
use App\Models\ClientEvaluationResponse;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('submits a client evaluation response', function () {
    $booking = Booking::factory()->create([
        'email' => 'client@example.com',
    ]);

    $ratingQuestion = ClientEvaluationQuestion::factory()->create([
        'type' => 'rating',
        'is_required' => true,
        'sort_order' => 1,
    ]);

    $textQuestion = ClientEvaluationQuestion::factory()->create([
        'type' => 'text',
        'is_required' => false,
        'sort_order' => 2,
    ]);

    $link = ClientEvaluationLink::create([
        'booking_id' => $booking->id,
        'token' => Str::random(40),
        'status' => 'active',
        'sent_at' => now(),
        'expires_at' => now()->addDays(7),
    ]);

    Livewire::test(PublicForm::class, ['token' => $link->token])
        ->set('respondent_name', 'Client Name')
        ->set('respondent_email', 'client@example.com')
        ->set('answers.' . $ratingQuestion->id, 4)
        ->set('answers.' . $textQuestion->id, 'Great service')
        ->set('general_comments', 'Thanks')
        ->call('submit');

    expect(ClientEvaluationResponse::where('client_evaluation_link_id', $link->id)->exists())->toBeTrue();
    expect($link->fresh()->status)->toBe('completed');
});
