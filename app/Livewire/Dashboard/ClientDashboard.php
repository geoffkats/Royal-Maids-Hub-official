<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class ClientDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard.client-dashboard')
            ->layout('components.layouts.app', ['title' => __('Client Dashboard')]);
    }
}
