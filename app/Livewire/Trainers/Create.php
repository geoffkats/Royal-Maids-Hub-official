<?php

namespace App\Livewire\Trainers;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Create extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public ?string $specialization = null;
    public int $experience_years = 0;
    public ?string $bio = null;
    public string $status = 'active';
    public $photo = null; // \Livewire\Features\SupportFileUploads\TemporaryUploadedFile

    public function mount(): void
    {
        $this->authorize('create', Trainer::class);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => ['required','confirmed','min:8'],
            'specialization' => ['nullable','string','max:255'],
            'experience_years' => ['required','integer','min:0','max:80'],
            'bio' => ['nullable','string'],
            'status' => ['required', Rule::in(['active','inactive'])],
            'photo' => ['nullable','image','max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => User::ROLE_TRAINER,
            'email_verified_at' => now(),
        ]);

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('trainer-photos', 'public');
        }

        Trainer::create([
            'user_id' => $user->id,
            'specialization' => $this->specialization,
            'experience_years' => $this->experience_years,
            'bio' => $this->bio,
            'photo_path' => $photoPath,
            'status' => $this->status,
        ]);

        session()->flash('success', __('Trainer created successfully.'));
        $this->redirectRoute((auth()->user()->role === 'trainer' ? 'trainer.' : '') . 'trainers.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.trainers.create', [
            'title' => __('New Trainer'),
        ]);
    }
}
