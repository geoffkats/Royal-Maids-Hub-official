<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'logo',
        'logo_dark',
        'favicon',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_author',
        'meta_robots',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_site',
        'twitter_creator',
        'google_analytics_id',
        'google_tag_manager_id',
        'facebook_pixel_id',
        'head_scripts',
        'body_scripts',
        'footer_scripts',
        'google_site_verification',
        'google_search_console',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'support_email',
        'support_phone',
        'business_hours',
        'maintenance_mode',
        'maintenance_message',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Get the singleton instance of company settings
     */
    public static function current(): self
    {
        return Cache::remember('company_settings', 3600, function () {
            return self::firstOrCreate([], [
                'company_name' => 'Royal Maids Hub',
                'meta_robots' => 'index, follow',
                'og_type' => 'website',
                'twitter_card' => 'summary_large_image',
            ]);
        });
    }

    /**
     * Clear the settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('company_settings');
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    /**
     * Get dark logo URL
     */
    public function getLogoDarkUrlAttribute(): ?string
    {
        return $this->logo_dark ? Storage::url($this->logo_dark) : null;
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon ? Storage::url($this->favicon) : null;
    }

    /**
     * Get OG image URL
     */
    public function getOgImageUrlAttribute(): ?string
    {
        return $this->og_image ? Storage::url($this->og_image) : null;
    }

    /**
     * Boot method to clear cache on save
     */
    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
