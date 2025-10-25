<?php

namespace App\Livewire\Trainers;

use App\Models\Trainer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Show extends Component
{
    use AuthorizesRequests;

    public Trainer $trainer;

    public function mount(Trainer $trainer): void
    {
        $this->trainer = $trainer->load('user');
        $this->authorize('view', $this->trainer);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.trainers.show', [
            'title' => __('Trainer Profile'),
        ]);
    }
}
