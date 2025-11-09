<?php

namespace App\Livewire\Settings;

use App\Models\CompanySetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CompanySettings extends Component
{
    use WithFileUploads;

    // Company Information
    public $company_name;
    public $company_email;
    public $company_phone;
    public $company_address;

    // Branding
    public $logo;
    public $logo_dark;
    public $favicon;
    public $current_logo;
    public $current_logo_dark;
    public $current_favicon;

    // SEO Settings
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $meta_author;
    public $meta_robots;

    // Open Graph
    public $og_title;
    public $og_description;
    public $og_image;
    public $og_type;
    public $current_og_image;

    // Twitter Card
    public $twitter_card;
    public $twitter_site;
    public $twitter_creator;

    // Analytics & Scripts
    public $google_analytics_id;
    public $google_tag_manager_id;
    public $facebook_pixel_id;
    public $head_scripts;
    public $body_scripts;
    public $footer_scripts;

    // Google Services
    public $google_site_verification;
    public $google_search_console;

    // Social Media
    public $facebook_url;
    public $twitter_url;
    public $instagram_url;
    public $linkedin_url;
    public $youtube_url;

    // Contact
    public $support_email;
    public $support_phone;
    public $business_hours;

    // Maintenance
    public $maintenance_mode;
    public $maintenance_message;

    public $activeTab = 'general';

    public function mount()
    {
        $settings = CompanySetting::current();

        // Load all settings
        $this->company_name = $settings->company_name;
        $this->company_email = $settings->company_email;
        $this->company_phone = $settings->company_phone;
        $this->company_address = $settings->company_address;

        $this->current_logo = $settings->logo;
        $this->current_logo_dark = $settings->logo_dark;
        $this->current_favicon = $settings->favicon;

        $this->meta_title = $settings->meta_title;
        $this->meta_description = $settings->meta_description;
        $this->meta_keywords = $settings->meta_keywords;
        $this->meta_author = $settings->meta_author;
        $this->meta_robots = $settings->meta_robots;

        $this->og_title = $settings->og_title;
        $this->og_description = $settings->og_description;
        $this->current_og_image = $settings->og_image;
        $this->og_type = $settings->og_type;

        $this->twitter_card = $settings->twitter_card;
        $this->twitter_site = $settings->twitter_site;
        $this->twitter_creator = $settings->twitter_creator;

        $this->google_analytics_id = $settings->google_analytics_id;
        $this->google_tag_manager_id = $settings->google_tag_manager_id;
        $this->facebook_pixel_id = $settings->facebook_pixel_id;
        $this->head_scripts = $settings->head_scripts;
        $this->body_scripts = $settings->body_scripts;
        $this->footer_scripts = $settings->footer_scripts;

        $this->google_site_verification = $settings->google_site_verification;
        $this->google_search_console = $settings->google_search_console;

        $this->facebook_url = $settings->facebook_url;
        $this->twitter_url = $settings->twitter_url;
        $this->instagram_url = $settings->instagram_url;
        $this->linkedin_url = $settings->linkedin_url;
        $this->youtube_url = $settings->youtube_url;

        $this->support_email = $settings->support_email;
        $this->support_phone = $settings->support_phone;
        $this->business_hours = $settings->business_hours;

        $this->maintenance_mode = $settings->maintenance_mode;
        $this->maintenance_message = $settings->maintenance_message;
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
            'logo_dark' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'og_image' => 'nullable|image|max:2048',
        ]);

        $settings = CompanySetting::current();

        // Handle logo upload
        if ($this->logo) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $logoPath = $this->logo->store('company/logos', 'public');
            $settings->logo = $logoPath;
        }

        // Handle dark logo upload
        if ($this->logo_dark) {
            if ($settings->logo_dark) {
                Storage::disk('public')->delete($settings->logo_dark);
            }
            $logoDarkPath = $this->logo_dark->store('company/logos', 'public');
            $settings->logo_dark = $logoDarkPath;
        }

        // Handle favicon upload
        if ($this->favicon) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $faviconPath = $this->favicon->store('company/favicon', 'public');
            $settings->favicon = $faviconPath;
        }

        // Handle OG image upload
        if ($this->og_image) {
            if ($settings->og_image) {
                Storage::disk('public')->delete($settings->og_image);
            }
            $ogImagePath = $this->og_image->store('company/og-images', 'public');
            $settings->og_image = $ogImagePath;
        }

        // Update all settings
        $settings->update([
            'company_name' => $this->company_name,
            'company_email' => $this->company_email,
            'company_phone' => $this->company_phone,
            'company_address' => $this->company_address,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'meta_author' => $this->meta_author,
            'meta_robots' => $this->meta_robots,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_type' => $this->og_type,
            'twitter_card' => $this->twitter_card,
            'twitter_site' => $this->twitter_site,
            'twitter_creator' => $this->twitter_creator,
            'google_analytics_id' => $this->google_analytics_id,
            'google_tag_manager_id' => $this->google_tag_manager_id,
            'facebook_pixel_id' => $this->facebook_pixel_id,
            'head_scripts' => $this->head_scripts,
            'body_scripts' => $this->body_scripts,
            'footer_scripts' => $this->footer_scripts,
            'google_site_verification' => $this->google_site_verification,
            'google_search_console' => $this->google_search_console,
            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'instagram_url' => $this->instagram_url,
            'linkedin_url' => $this->linkedin_url,
            'youtube_url' => $this->youtube_url,
            'support_email' => $this->support_email,
            'support_phone' => $this->support_phone,
            'business_hours' => $this->business_hours,
            'maintenance_mode' => (bool) $this->maintenance_mode,
            'maintenance_message' => $this->maintenance_message,
        ]);

        session()->flash('message', 'Company settings updated successfully!');
        
        // Reset file inputs
        $this->reset(['logo', 'logo_dark', 'favicon', 'og_image']);
        
        // Reload current images
        $this->current_logo = $settings->logo;
        $this->current_logo_dark = $settings->logo_dark;
        $this->current_favicon = $settings->favicon;
        $this->current_og_image = $settings->og_image;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.settings.company-settings')
            ->layout('components.layouts.app');
    }
}
