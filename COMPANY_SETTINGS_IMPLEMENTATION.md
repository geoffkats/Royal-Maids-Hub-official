# ✅ Company Settings System - Complete Implementation

## Overview
Comprehensive company settings management system with branding, SEO, analytics, and custom scripts integration across the entire site.

---

## Features Implemented

### 1. **Company Information**
- Company name, email, phone
- Company address
- Support email and phone
- Business hours

### 2. **Branding & Images**
- **Logo (Light Mode)** - For light backgrounds
- **Logo (Dark Mode)** - For dark backgrounds  
- **Favicon** - Browser tab icon
- **OG Image** - Social media share image (1200x630px)

### 3. **SEO & Meta Tags**
- Meta title
- Meta description (150-160 characters)
- Meta keywords
- Meta author
- Meta robots (index/noindex, follow/nofollow)

### 4. **Open Graph (Facebook, LinkedIn)**
- OG Title
- OG Description
- OG Image
- OG Type (website/article/business)

### 5. **Twitter Card**
- Card type (summary/summary_large_image)
- Twitter site handle
- Twitter creator handle

### 6. **Google Services**
- Google Site Verification
- Google Search Console code

### 7. **Analytics & Tracking**
- **Google Analytics ID** (GA4 or Universal Analytics)
- **Google Tag Manager ID**
- **Facebook Pixel ID**

### 8. **Custom Scripts**
- **Head Scripts** - Added in `<head>` section
- **Body Scripts** - Added after `<body>` tag
- **Footer Scripts** - Added before `</body>` tag

### 9. **Social Media Links**
- Facebook URL
- Twitter URL
- Instagram URL
- LinkedIn URL
- YouTube URL

---

## Files Created/Modified

### Database
✅ **Migration**: `database/migrations/2025_11_09_063210_create_company_settings_table.php`
- Comprehensive table with all settings fields
- Nullable fields for flexibility
- Default values for common settings

✅ **Model**: `app/Models/CompanySetting.php`
- Singleton pattern with `current()` method
- Automatic caching (1 hour)
- URL accessors for images
- Auto-clears cache on save

### Livewire Component
✅ **Component**: `app/Livewire/Settings/CompanySettings.php`
- File upload handling (logo, favicon, OG image)
- Tab-based interface
- Validation
- Success messages

✅ **View**: `resources/views/livewire/settings/company-settings.blade.php`
- 5 tabs: General, Branding, SEO, Scripts, Social Media
- Image previews
- Helpful placeholders and hints
- Responsive design

### Integration
✅ **Head Partial**: `resources/views/partials/head.blade.php`
- All SEO meta tags
- Open Graph tags
- Twitter Card tags
- Google Analytics integration
- Google Tag Manager integration
- Facebook Pixel integration
- Custom head scripts
- Dynamic favicon

✅ **Layout**: `resources/views/components/layouts/app/sidebar.blade.php`
- Body scripts after `<body>` tag
- GTM noscript
- Footer scripts before `</body>` tag

✅ **Route**: `routes/web.php`
- `/settings/company` route (admin only)

✅ **Sidebar**: Added "Company Settings" link under "System" section

---

## Usage

### Access Settings Page
**URL**: http://127.0.0.1:8000/settings/company  
**Permission**: Admin only  
**Location**: Sidebar → System → Company Settings

### Tab Structure

#### 1. General Tab
- Company name, email, phone
- Support contact information
- Business hours

#### 2. Branding Tab
- Upload logo (light mode)
- Upload logo (dark mode)
- Upload favicon (32x32px or 64x64px)
- Upload OG image (1200x630px)

#### 3. SEO & Meta Tags Tab
**Basic SEO**:
- Meta title (50-60 characters)
- Meta description (150-160 characters)
- Meta keywords
- Meta author
- Meta robots

**Open Graph**:
- OG title, description, type

**Twitter Card**:
- Card type, site handle, creator

**Google Services**:
- Site verification code
- Search Console code

#### 4. Scripts & Analytics Tab
**Analytics IDs**:
- Google Analytics ID (G-XXXXXXXXXX)
- Google Tag Manager ID (GTM-XXXXXXX)
- Facebook Pixel ID

**Custom Scripts**:
- Head scripts (in `<head>`)
- Body scripts (after `<body>`)
- Footer scripts (before `</body>`)

#### 5. Social Media Tab
- Facebook, Twitter, Instagram, LinkedIn, YouTube URLs

---

## How It Works

### Singleton Pattern
```php
$settings = CompanySetting::current();
```
- Always returns the same record
- Cached for 1 hour
- Auto-creates if doesn't exist

### Automatic Cache Management
- Cache cleared on save
- Cache cleared on delete
- Cached for performance

### Image Handling
```php
// In views
$settings->logo_url        // Full URL to logo
$settings->logo_dark_url   // Full URL to dark logo
$settings->favicon_url     // Full URL to favicon
$settings->og_image_url    // Full URL to OG image
```

### SEO Integration
All pages automatically get:
- Meta tags from settings
- Open Graph tags
- Twitter Card tags
- Dynamic favicon
- Analytics scripts

---

## Examples

### Adding Google Analytics
1. Go to **Settings → Company**
2. Click **Scripts & Analytics** tab
3. Enter your GA4 ID: `G-XXXXXXXXXX`
4. Click **Save Settings**
5. ✅ Analytics now tracking on all pages

### Adding Custom Scripts
1. Go to **Scripts & Analytics** tab
2. Paste your script in appropriate section:
   - **Head Scripts**: For meta tags, CSS
   - **Body Scripts**: For GTM noscript, early JS
   - **Footer Scripts**: For analytics, chat widgets
3. Click **Save Settings**
4. ✅ Scripts now loaded on all pages

### Updating Logo
1. Go to **Branding** tab
2. Click **Choose File** under Logo (Light Mode)
3. Select your PNG file (transparent background recommended)
4. Click **Save Settings**
5. ✅ Logo updated across entire site

### Setting Up SEO
1. Go to **SEO & Meta Tags** tab
2. Fill in:
   - **Meta Title**: "Royal Maids Hub - Professional Maid Services"
   - **Meta Description**: "Professional maid services with trained staff..."
   - **Meta Keywords**: "maid service, cleaning, housekeeping"
3. Click **Save Settings**
4. ✅ SEO tags now on all pages

---

## Script Integration Points

### Head Section (`<head>`)
```html
<!-- SEO Meta Tags -->
<meta name="description" content="...">
<meta name="keywords" content="...">

<!-- Open Graph -->
<meta property="og:title" content="...">
<meta property="og:image" content="...">

<!-- Twitter Card -->
<meta name="twitter:card" content="...">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=..."></script>

<!-- Custom Head Scripts -->
{!! $settings->head_scripts !!}
</head>
```

### Body Section (After `<body>`)
```html
<body>
    <!-- Custom Body Scripts -->
    {!! $settings->body_scripts !!}
    
    <!-- GTM Noscript -->
    <noscript><iframe src="..."></iframe></noscript>
    
    <!-- Page Content -->
    ...
```

### Footer Section (Before `</body>`)
```html
    <!-- Custom Footer Scripts -->
    {!! $settings->footer_scripts !!}
</body>
```

---

## Validation Rules

### Images
- **Logo**: Max 2MB, image format
- **Logo Dark**: Max 2MB, image format
- **Favicon**: Max 1MB, image format
- **OG Image**: Max 2MB, image format

### Text Fields
- **Company Name**: Required, max 255 characters
- **Company Email**: Valid email format
- **URLs**: Valid URL format

---

## Cache Management

### Automatic
- Cache cleared on save
- Cache cleared on delete
- Cache duration: 1 hour

### Manual
```php
// Clear settings cache
CompanySetting::clearCache();

// Or clear all cache
php artisan cache:clear
```

---

## Security

### Script Injection
- Scripts are rendered with `{!! !!}` (unescaped)
- **Only admins** can access settings
- Be careful with scripts from untrusted sources

### File Uploads
- Validated file types (images only)
- Size limits enforced
- Stored in `storage/app/public/company/`

---

## API Reference

### Model Methods
```php
// Get current settings (cached)
$settings = CompanySetting::current();

// Clear cache
CompanySetting::clearCache();

// Access properties
$settings->company_name
$settings->logo_url
$settings->meta_title
$settings->google_analytics_id
```

### URL Accessors
```php
$settings->logo_url        // /storage/company/logos/xxx.png
$settings->logo_dark_url   // /storage/company/logos/xxx.png
$settings->favicon_url     // /storage/company/favicon/xxx.png
$settings->og_image_url    // /storage/company/og-images/xxx.png
```

---

## Troubleshooting

### Images Not Showing
```bash
# Create storage link
php artisan storage:link

# Check permissions
chmod -R 775 storage/app/public
```

### Cache Not Clearing
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Scripts Not Loading
1. Check if scripts are saved in settings
2. Clear browser cache
3. Check browser console for errors
4. Verify script syntax

---

## Best Practices

### SEO
✅ Keep meta title under 60 characters  
✅ Keep meta description 150-160 characters  
✅ Use descriptive, unique titles  
✅ Include target keywords naturally  

### Images
✅ Use PNG with transparent background for logos  
✅ Optimize images before upload  
✅ Use 1200x630px for OG images  
✅ Use 32x32px or 64x64px for favicon  

### Scripts
✅ Test scripts in staging first  
✅ Minimize custom scripts  
✅ Use analytics IDs instead of full scripts when possible  
✅ Place heavy scripts in footer  

### Analytics
✅ Use GA4 (G-XXXXXXXXXX) format  
✅ Test tracking with browser extensions  
✅ Set up goals in Google Analytics  
✅ Monitor page load times  

---

## Future Enhancements

### Potential Additions
- [ ] Multiple logo variants (square, horizontal, vertical)
- [ ] Color scheme settings
- [ ] Email template customization
- [ ] Multi-language support
- [ ] A/B testing integration
- [ ] Advanced SEO tools (schema markup)
- [ ] Social media auto-posting
- [ ] Maintenance mode scheduling

---

## Summary

**Status**: ✅ Fully Implemented  
**Route**: `/settings/company`  
**Permission**: Admin only  
**Features**: 9 major categories, 40+ settings  
**Integration**: Site-wide (head, body, footer)  

**You can now manage all company branding, SEO, and analytics from one central location!** 🎉

---

## Quick Start

1. **Access Settings**: http://127.0.0.1:8000/settings/company
2. **Add Company Info**: Fill in General tab
3. **Upload Logo**: Add in Branding tab
4. **Set SEO**: Configure in SEO tab
5. **Add Analytics**: Enter IDs in Scripts tab
6. **Save**: Click "Save Settings"
7. **Done**: Settings applied site-wide!

---

**All settings are now centralized and easy to manage!** 🚀
