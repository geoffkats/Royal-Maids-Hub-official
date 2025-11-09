<?php

namespace App\Livewire;

use App\Models\ContactSubmission;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ContactForm extends Component
{
    public $name = '';
    public $phone = '';
    public $email = '';
    public $service = '';
    public $familySize = '';
    public $message = '';
    public $privacy = false;
    
    public $isSubmitting = false;
    public $showSuccessMessage = false;
    public $showErrorMessage = false;
    public $errorMessage = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'service' => 'required|string',
        'familySize' => 'nullable|string',
        'message' => 'nullable|string|max:1000',
        'privacy' => 'required|accepted',
    ];

    protected $messages = [
        'name.required' => 'Full name is required.',
        'phone.required' => 'Phone number is required.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'service.required' => 'Please select a service.',
        'privacy.required' => 'You must agree to the privacy policy.',
        'privacy.accepted' => 'You must agree to the privacy policy.',
    ];

    public function submit()
    {
        $this->isSubmitting = true;
        $this->showSuccessMessage = false;
        $this->showErrorMessage = false;
        $this->errorMessage = '';

        try {
            // Validate the form
            $this->validate();

            // Save to database
            ContactSubmission::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'service' => $this->service,
                'family_size' => $this->familySize,
                'message' => $this->message,
                'privacy_accepted' => $this->privacy,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'status' => 'new',
            ]);

            // Log the contact form submission
            Log::info('Contact form submission', [
                'name' => $this->name,
                'email' => $this->email,
                'service' => $this->service,
                'submitted_at' => now()->format('Y-m-d H:i:s'),
            ]);

            // Send email notification (you can uncomment this when email is configured)
            /*
            Mail::send('emails.contact-form', $contactData, function ($message) use ($contactData) {
                $message->to('info@royalmaidshub.com')
                        ->subject('New Contact Form Submission - ' . $contactData['name']);
            });
            */

            // Reset form
            $this->reset(['name', 'phone', 'email', 'service', 'familySize', 'message', 'privacy']);
            
            $this->showSuccessMessage = true;
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = 'Please check the form and try again.';
            $this->showErrorMessage = true;
        } catch (\Exception $e) {
            Log::error('Contact form submission error: ' . $e->getMessage());
            $this->errorMessage = 'Sorry, there was an error submitting your request. Please try again or contact us directly.';
            $this->showErrorMessage = true;
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}