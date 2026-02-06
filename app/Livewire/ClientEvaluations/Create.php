<?php

namespace App\Livewire\ClientEvaluations;

use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientEvaluation;
use App\Models\Trainer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?int $client_id = null;
    public ?int $booking_id = null;
    public ?int $trainer_id = null;
    public string $evaluation_date = '';
    public string $evaluation_type = 'custom';
    public ?float $overall_rating = null;
    public ?string $strengths = null;
    public ?string $areas_for_improvement = null;
    public ?string $comments = null;
    public ?string $next_evaluation_date = null;

    public function mount(): void
    {
        $this->authorize('create', ClientEvaluation::class);

        $this->evaluation_date = now()->format('Y-m-d');
        $this->client_id = request()->integer('client_id') ?: null;
        $this->booking_id = request()->integer('booking_id') ?: null;

        $user = auth()->user();
        if ($user->role === 'trainer') {
            $trainer = Trainer::where('user_id', $user->id)->first();
            $this->trainer_id = $trainer?->id;
        }
    }

    protected function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'trainer_id' => ['nullable', 'exists:trainers,id'],
            'evaluation_date' => ['required', 'date'],
            'evaluation_type' => ['required', Rule::in(['3_months', '6_months', '12_months', 'custom'])],
            'overall_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'strengths' => ['nullable', 'string'],
            'areas_for_improvement' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            'next_evaluation_date' => ['nullable', 'date', 'after_or_equal:evaluation_date'],
        ];
    }

    public function updatedEvaluationType(string $value): void
    {
        if ($value === 'custom') {
            $this->next_evaluation_date = null;
            return;
        }

        $months = match ($value) {
            '3_months' => 3,
            '6_months' => 6,
            '12_months' => 12,
            default => null,
        };

        if ($months) {
            $this->next_evaluation_date = now()
                ->parse($this->evaluation_date)
                ->addMonths($months)
                ->format('Y-m-d');
        }
    }

    public function save(): void
    {
        $this->validate();

        $user = auth()->user();
        if ($user->role === 'trainer') {
            $trainer = Trainer::where('user_id', $user->id)->first();
            $this->trainer_id = $trainer?->id;
        }

        if ($this->evaluation_type !== 'custom' && empty($this->next_evaluation_date)) {
            $this->updatedEvaluationType($this->evaluation_type);
        }

        ClientEvaluation::create([
            'client_id' => $this->client_id,
            'booking_id' => $this->booking_id,
            'trainer_id' => $this->trainer_id,
            'evaluation_date' => $this->evaluation_date,
            'evaluation_type' => $this->evaluation_type,
            'overall_rating' => $this->overall_rating,
            'strengths' => $this->strengths,
            'areas_for_improvement' => $this->areas_for_improvement,
            'comments' => $this->comments,
            'next_evaluation_date' => $this->next_evaluation_date,
        ]);

        session()->flash('success', __('Client evaluation created successfully.'));
        $this->redirectRoute('client-evaluations.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $user = auth()->user();

        $trainers = $user->role === 'trainer'
            ? Trainer::with('user')->where('user_id', $user->id)->get()
            : Trainer::with('user')->get();

        return view('livewire.client-evaluations.create', [
            'clients' => Client::with('user')->get(),
            'bookings' => Booking::with(['client', 'maid'])->latest()->get(),
            'trainers' => $trainers,
        ]);
    }
}
