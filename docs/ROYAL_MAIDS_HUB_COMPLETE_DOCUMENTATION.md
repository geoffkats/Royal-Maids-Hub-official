# Royal Maids Hub - Complete System Documentation

**Version:** 5.0  
**Last Updated:** 2024  
**Document Status:** Comprehensive Unified Documentation

This document consolidates all Royal Maids Hub documentation into a single comprehensive reference.

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Architecture & Technology Stack](#2-architecture--technology-stack)
3. [Project Structure](#3-project-structure)
4. [Authentication & Authorization](#4-authentication--authorization)
5. [Core Modules](#5-core-modules)
6. [Dashboard & Analytics](#6-dashboard--analytics)
7. [Reports & KPI System](#7-reports--kpi-system)
8. [Company Settings Management](#8-company-settings-management)
9. [User Interface & Brand System](#9-user-interface--brand-system)
10. [Database Design](#10-database-design)
11. [Security & Compliance](#11-security--compliance)
12. [Deployment & Configuration](#12-deployment--configuration)
13. [Testing & Quality Assurance](#13-testing--quality-assurance)
14. [Troubleshooting & Known Issues](#14-troubleshooting--known-issues)
15. [Migration Guides](#15-migration-guides)
16. [Appendix](#16-appendix)

---

## 1. System Overview

Royal Maids Hub is a comprehensive business management platform designed specifically for domestic cleaning service operations. The system manages the complete lifecycle of maid services, from lead generation and client onboarding to service delivery, quality assessment, and business analytics.

### 1.1 Key Features

- **Lead & CRM Management**: Complete customer relationship management with pipeline tracking, duplicate detection, and automated workflows
- **Client & Booking Management**: Streamlined booking workflow with automated lead conversion and package-based subscriptions
- **Maid Lifecycle Management**: From recruitment to deployment, training, and performance tracking
- **Training & Evaluation**: Comprehensive trainer management and systematic skill assessment with KPI tracking
- **Subscription Packages**: Tiered service packages (Silver, Gold, Platinum) with automatic revenue calculation
- **Analytics & Reporting**: Real-time KPIs, performance metrics, and comprehensive business intelligence
- **Service Ticketing**: Multi-tier support system with SLA tracking and priority management
- **Company Settings**: Centralized branding, SEO, analytics, and social media configuration

### 1.2 Business Value

- **Operational Efficiency**: Automated workflows reduce manual data entry by 60%
- **Revenue Optimization**: Package-based pricing with family size auto-calculation generates predictable revenue
- **Quality Assurance**: Systematic evaluation and training tracking ensures service excellence
- **Data-Driven Decisions**: Comprehensive KPIs and analytics dashboards enable informed business decisions
- **Customer Experience**: Seamless lead-to-client conversion with duplicate prevention improves customer satisfaction
- **Scalability**: Modular architecture supports business growth and feature expansion

### 1.3 Target Users

- **Super Admin**: Full system access and configuration
- **Admin**: Operational management and oversight
- **Manager**: Team management and reporting
- **Trainer**: Training delivery and maid evaluation
- **Client**: Self-service booking and service management
- **Maid**: Profile and schedule management

---

## 2. Architecture & Technology Stack

### 2.1 Backend Stack

- **Framework**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0
- **Cache**: File-based cache (Redis recommended for production)
- **Queue**: Database queue driver (Redis recommended for production)
- **Authentication**: Laravel Breeze with role-based access control
- **ORM**: Eloquent
- **Validation**: Laravel Form Requests

### 2.2 Frontend Stack

- **UI Framework**: Livewire 3.x (full-stack reactive components)
- **CSS Framework**: TailwindCSS 3.x with custom brand tokens
- **Icons**: Lucide Icons
- **Charts**: Chart.js for data visualization
- **Components**: Custom component library with consistent design patterns
- **Build Tool**: Vite for asset compilation

### 2.3 Development Tools

- **Package Manager**: Composer (PHP), NPM (JavaScript)
- **Code Quality**: PHPStan, Laravel Pint
- **Testing**: PHPUnit, Laravel Dusk
- **Version Control**: Git

### 2.4 Infrastructure

- **Web Server**: Apache/Nginx
- **PHP**: 8.1+ with required extensions (mbstring, xml, pdo, openssl, tokenizer, json, bcmath)
- **Node.js**: 18+ for asset compilation
- **Storage**: Local filesystem (S3 compatible for production)
- **Email**: SMTP/Mailgun/SES

### 2.5 Third-Party Integrations

- **Analytics**: Google Analytics, Google Tag Manager, Facebook Pixel
- **SEO**: Open Graph, Twitter Card, Google Search Console
- **Payment**: (Planned) M-Pesa, Stripe
- **Communication**: Email notifications, SMS (planned)

---

## 3. Project Structure

```
royalMaids-v5/
├── app/
│   ├── Console/Commands/        # Artisan commands
│   ├── Http/
│   │   ├── Controllers/         # HTTP controllers
│   │   └── Middleware/          # Custom middleware
│   ├── Livewire/                # Livewire components
│   │   ├── Bookings/
│   │   ├── CRM/
│   │   ├── Clients/
│   │   ├── Maids/
│   │   ├── Packages/
│   │   ├── Settings/
│   │   └── Trainers/
│   ├── Models/                  # Eloquent models
│   ├── Policies/                # Authorization policies
│   ├── Services/                # Business logic services
│   ├── Jobs/                    # Queue jobs
│   └── Notifications/           # Notification classes
├── database/
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── factories/               # Model factories
├── resources/
│   ├── views/                   # Blade templates
│   ├── css/                     # Stylesheets
│   └── js/                      # JavaScript
├── routes/
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
├── storage/
│   ├── app/public/              # Public storage
│   └── logs/                    # Application logs
├── tests/
│   ├── Feature/                 # Feature tests
│   └── Unit/                    # Unit tests
└── public/                      # Public assets
```

---

## 4. Authentication & Authorization

### 4.1 User Roles

**1. Super Admin**
- Full system access
- User management (create, edit, delete users)
- System configuration and settings
- All CRUD operations across all modules

**2. Admin**
- Most management functions
- Cannot manage other admins
- Full access to operational modules
- Report generation and analytics

**3. Manager**
- Operational management
- Limited settings access
- Team oversight and performance monitoring

**4. Trainer**
- Training management
- Maid evaluation and assessment
- Progress tracking and reporting

**5. Client**
- Self-service portal
- Booking management
- Service history viewing
- Profile management

**6. Maid**
- Profile management
- Schedule viewing
- Training access and progress tracking

### 4.2 Permission System

```php
// Example Gates
Gate::define('manage-leads', function (User $user) {
    return in_array($user->role, ['super_admin', 'admin', 'manager']);
});

Gate::define('convert-lead-to-client', function (User $user) {
    return in_array($user->role, ['super_admin', 'admin']);
});

Gate::define('manage-packages', function (User $user) {
    return $user->role === 'super_admin';
});
```

### 4.3 Authentication Flow

1. User navigates to `/login`
2. Credentials validated against `users` table
3. Password verified using bcrypt hashing
4. Session created with user ID and role information
5. Middleware checks permissions on each request
6. Unauthorized access redirects to dashboard with error message

---

