<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Index extends Component
{
    public $activeTab = 'general';

    public function render()
    {
        return view('livewire.settings.index');
    }
}
