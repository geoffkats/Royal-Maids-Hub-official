<?php

namespace App\Livewire\Trainers;

use App\Models\Trainer;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public Trainer $trainer;

    public ?string $specialization = null;
    public int $experience_years = 0;
    public ?string $bio = null;
    public string $status = 'active';
    public $photo = null; // Temporary upload

    public function mount(Trainer $trainer): void
    {
        $this->trainer = $trainer->load('user');
        $this->authorize('update', $this->trainer);

        $this->specialization = $trainer->specialization;
        $this->experience_years = (int) $trainer->experience_years;
        $this->bio = $trainer->bio;
        $this->status = $trainer->status;
    }

    protected function rules(): array
    {
        return [
            'specialization' => ['nullable','string','max:255'],
            'experience_years' => ['required','integer','min:0','max:80'],
            'bio' => ['nullable','string'],
            'status' => ['required', Rule::in(['active','inactive'])],
            'photo' => ['nullable','image','max:2048'],
        ];
    }

    public function update(): void
    {
        $this->validate();

        // Handle photo upload (replace existing if provided)
        if ($this->photo) {
            // Delete old photo if exists
            if ($this->trainer->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($this->trainer->photo_path);
            }
            $newPath = $this->photo->store('trainer-photos', 'public');
            $this->trainer->photo_path = $newPath;
        }

        $this->trainer->update([
            'specialization' => $this->specialization,
            'experience_years' => $this->experience_years,
            'bio' => $this->bio,
            'status' => $this->status,
            'photo_path' => $this->trainer->photo_path,
        ]);

        session()->flash('success', __('Trainer updated successfully.'));
        $this->redirectRoute((auth()->user()->role === 'trainer' ? 'trainer.' : '') . 'trainers.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.trainers.edit', [
            'title' => __('Edit Trainer'),
        ]);
    }
}
