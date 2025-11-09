<?php

namespace App\Livewire\Trainers;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ResetPassword extends Component
{
    public Trainer $trainer;
    public $newPassword = '';
    public $confirmPassword = '';
    public $showModal = false;

    protected $rules = [
        'newPassword' => 'required|min:8',
        'confirmPassword' => 'required|same:newPassword'
    ];

    protected $messages = [
        'newPassword.required' => 'New password is required.',
        'newPassword.min' => 'Password must be at least 8 characters.',
        'confirmPassword.required' => 'Please confirm the password.',
        'confirmPassword.same' => 'Passwords do not match.'
    ];

    public function mount(Trainer $trainer)
    {
        $this->trainer = $trainer;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->reset(['newPassword', 'confirmPassword']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['newPassword', 'confirmPassword']);
    }

    public function resetPassword()
    {
        $this->validate();

        // Update the trainer's password
        $this->trainer->user->update([
            'password' => Hash::make($this->newPassword)
        ]);

        // Close modal and show success message
        $this->closeModal();
        session()->flash('message', 'Password has been reset successfully for ' . $this->trainer->user->name);
        
        // Emit event to refresh the trainers list and show message
        $this->dispatch('password-reset');
        
        // Redirect to refresh the page and show the message
        return redirect()->route('trainers.index');
    }

    public function render()
    {
        return view('livewire.trainers.reset-password');
    }
}
