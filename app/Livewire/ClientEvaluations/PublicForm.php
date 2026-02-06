<?php

namespace App\Livewire\ClientEvaluations;

use App\Models\ClientEvaluationLink;
use App\Models\ClientEvaluationQuestion;
use App\Models\ClientEvaluationResponse;
use App\Models\Booking;
use Illuminate\Support\Arr;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PublicForm extends Component
{
    public string $token = '';
    public ?ClientEvaluationLink $link = null;
    public ?Booking $booking = null;
    public bool $isExpired = false;
    public bool $isSubmitted = false;

    public string $respondent_name = '';
    public string $respondent_email = '';
    public array $answers = [];
    public ?string $general_comments = null;

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->link = ClientEvaluationLink::query()
            ->with(['booking.client.user', 'booking.maid', 'booking.package', 'response'])
            ->where('token', $token)
            ->firstOrFail();

        $this->booking = $this->link->booking;
        $this->isExpired = $this->link->expires_at !== null && now()->greaterThan($this->link->expires_at);
        $this->isSubmitted = $this->link->response !== null || $this->link->status === 'completed';

        if ($this->booking?->full_name) {
            $this->respondent_name = $this->booking->full_name;
        }

        if ($this->booking?->email) {
            $this->respondent_email = $this->booking->email;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'respondent_name' => ['required', 'string', 'max:255'],
            'respondent_email' => ['required', 'email', 'max:255'],
            'general_comments' => ['nullable', 'string', 'max:2000'],
        ];

        $questions = ClientEvaluationQuestion::active()->orderBy('sort_order')->orderBy('id')->get();
        foreach ($questions as $question) {
            $key = 'answers.' . $question->id;
            $ruleSet = [];

            if ($question->is_required) {
                $ruleSet[] = 'required';
            } else {
                $ruleSet[] = 'nullable';
            }

            if ($question->type === 'rating') {
                $ruleSet[] = 'integer';
                $ruleSet[] = 'min:1';
                $ruleSet[] = 'max:5';
            } elseif ($question->type === 'yes_no') {
                $ruleSet[] = 'in:yes,no';
            } else {
                $ruleSet[] = 'string';
                $ruleSet[] = 'max:1000';
            }

            $rules[$key] = $ruleSet;
        }

        return $rules;
    }

    public function submit(): void
    {
        if ($this->isExpired || $this->isSubmitted || !$this->link || !$this->booking) {
            return;
        }

        $this->validate();

        $allowedEmails = array_filter([
            $this->booking->email,
            $this->booking->client?->user?->email,
        ]);
        $normalizedAllowed = array_map(fn ($email) => strtolower((string) $email), $allowedEmails);

        if (!empty($normalizedAllowed) && !in_array(strtolower($this->respondent_email), $normalizedAllowed, true)) {
            $this->addError('respondent_email', __('Email does not match the booking record.'));
            return;
        }

        $questions = ClientEvaluationQuestion::active()->orderBy('sort_order')->orderBy('id')->get();
        $ratingValues = [];

        foreach ($questions as $question) {
            if ($question->type === 'rating') {
                $value = Arr::get($this->answers, $question->id);
                if (is_numeric($value)) {
                    $ratingValues[] = (int) $value;
                }
            }
        }

        $overallRating = null;
        if (count($ratingValues) > 0) {
            $overallRating = round(array_sum($ratingValues) / count($ratingValues), 1);
        }

        ClientEvaluationResponse::create([
            'client_evaluation_link_id' => $this->link->id,
            'booking_id' => $this->booking->id,
            'client_id' => $this->booking->client_id,
            'maid_id' => $this->booking->maid_id,
            'package_id' => $this->booking->package_id,
            'respondent_name' => $this->respondent_name,
            'respondent_email' => $this->respondent_email,
            'answers' => $this->answers,
            'overall_rating' => $overallRating,
            'general_comments' => $this->general_comments,
            'submitted_at' => now(),
        ]);

        $this->link->update(['status' => 'completed']);

        $this->isSubmitted = true;
    }

    #[Layout('components.layouts.simple')]
    public function render()
    {
        $questions = ClientEvaluationQuestion::active()->orderBy('sort_order')->orderBy('id')->get();

        return view('livewire.client-evaluations.public-form', [
            'questions' => $questions,
        ]);
    }
}
