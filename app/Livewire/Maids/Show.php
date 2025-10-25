<?php

namespace App\Livewire\Maids;

use App\Models\Maid;
use Livewire\Component;

class Show extends Component
{
    public Maid $maid;

    public function mount(Maid $maid)
    {
        $this->maid = $maid;
    }

    public function render()
    {
        return view('livewire.maids.show');
    }
}