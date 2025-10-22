# 🏠 Royal Maids Hub - Complete System Documentation

**Version:** 2.0  
**Last Updated:** October 22, 2025  
**Developed by:** Synthilogic Enterprises  

---

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [Architecture & Technology Stack](#architecture--technology-stack)
3. [User Roles & Access Control](#user-roles--access-control)
4. [Core Modules](#core-modules)
5. [Database Architecture](#database-architecture)
6. [File Structure](#file-structure)
7. [Dashboard Systems](#dashboard-systems)
8. [API Endpoints](#api-endpoints)
9. [Security Features](#security-features)
10. [Deployment & Configuration](#deployment--configuration)
11. [Future Migration (Laravel)](#future-migration-laravel)
12. [Version 3.0 (Laravel 12 + Livewire)](#version-30-laravel-12--livewire)

---

## 🎯 System Overview

### What is Royal Maids Hub?

Royal Maids Hub is a **comprehensive web-based platform** designed for managing domestic workers (maids), training programs, client relationships, and booking operations. It serves as a complete solution for maid agencies, training centers, and clients seeking domestic help.

### Key Objectives

- **Streamline maid management** from registration to deployment
- **Track training progress** and evaluations
- **Facilitate client-maid matching** based on requirements
- **Manage bookings and deployments** efficiently
- **Provide real-time analytics** and reporting
- **Enable communication** between stakeholders

### System Capabilities

- ✅ **100+ maids** can be managed simultaneously
- ✅ **Dual status tracking** for complex state management
- ✅ **Real-time chat system** for internal communication
- ✅ **Advanced reporting** with PDF export capabilities
- ✅ **Role-based access control** for security
- ✅ **Mobile-responsive** interface for all devices

---

## 🏗️ Architecture & Technology Stack

### Backend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | 8.0+ | Server-side programming language |
| **MySQL** | 8.0+ | Database management system |
| **PDO** | Latest | Database abstraction layer |
| **Composer** | Latest | PHP dependency management |
| **FPDF** | 1.84 | PDF generation library |
| **Ratchet** | Latest | WebSocket server for real-time chat |

### Frontend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| **HTML5** | Latest | Markup language |
| **CSS3** | Latest | Styling |
| **Bootstrap** | 5.3.0 | CSS framework |
| **Tailwind CSS** | 4.1.13 | Utility-first CSS framework |
| **JavaScript** | ES6+ | Client-side interactivity |
| **Chart.js** | 2.9.4 | Data visualization |
| **Feather Icons** | 4.29.0 | Icon library |
| **Alpine.js** | Latest | Lightweight JavaScript framework |

### Development Tools

- **Webpack** (5.86.0) - Module bundler
- **Babel** (7.22.5) - JavaScript compiler
- **SASS** (1.63.2) - CSS preprocessor
- **PostCSS** (8.5.6) - CSS transformer
- **Autoprefixer** (10.4.14) - CSS vendor prefixing

### Server Environment

```
Web Server: Apache (WAMP Stack)
PHP Version: 8.0+
MySQL Version: 8.0+
Document Root: C:\wamp64\www\royalmaidshub
Database Name: u622340404_royalmaids
Default User: root (development)
Session Timeout: 30 minutes (1800 seconds)
```

---

## 👥 User Roles & Access Control

### 1. System Administrator

**Access Level:** Full System Control

**Permissions:**
- Full access to all system features
- User management (create, edit, delete users)
- System configuration and settings
- Database management and backups
- Access to all reports and analytics
- Audit log viewing
- Security settings management

**Primary Tasks:**
- System maintenance
- User role assignment
- Security monitoring
- Performance optimization
- Backup management

### 2. Agency Manager / Admin

**Access Level:** Operational Management

**Permissions:**
- Maid management (add, edit, delete, status updates)
- Client management (view, manage bookings)
- Trainer oversight
- Booking and deployment management
- Financial reports access
- KPI dashboard access
- PDF report generation

**Primary Tasks:**
- Daily maid operations
- Client relationships
- Booking management
- Staff coordination
- Performance monitoring

### 3. Trainer

**Access Level:** Training Operations

**Permissions:**
- View assigned maids
- Submit evaluations and assessments
- Track training progress
- Update training status
- Access training reports
- Submit training completion

**Primary Tasks:**
- Conduct training sessions
- Evaluate maid performance
- Track progress
- Submit assessments
- Report training issues

### 4. Client (Public User)

**Access Level:** Client Portal

**Permissions:**
- Browse available maids
- View maid profiles
- Make booking requests
- View own bookings
- Provide feedback
- Update profile information

**Primary Tasks:**
- Search for maids
- Review maid profiles
- Submit booking requests
- Track booking status
- Communicate preferences

---

## 🔧 Core Modules

### 1. Maid Management System

**Purpose:** Complete lifecycle management of domestic workers

#### Features

**Registration System (`add-maid.php`)**
- Comprehensive registration form with 50+ fields
- Personal information capture
- Document upload functionality
- Medical status tracking
- Image upload with preview
- Real-time validation

**Profile Management (`edit-maid.php`, `maid-profile.php`)**
- Full profile editing
- Status management (dual status system)
- Document management
- Performance tracking
- Training history

**Listing & Search (`maids.php`)**
- Paginated maid listing
- Advanced filtering (status, role, experience)
- Search functionality
- Bulk operations
- Export capabilities

#### Maid Information Fields

**Personal Information:**
- First Name, Last Name
- Phone Number (Primary & Secondary)
- Date of Birth (with age calculation)
- National ID Number (NIN)
- Nationality
- Marital Status (Single/Married)
- Number of Children (conditional)

**Location Details:**
- Tribe (12 options: Muganda, Munyankole, Mukiga, etc.)
- Village
- District (12 options: Kampala, Wakiso, Mukono, etc.)
- LC1 Chairperson Details

**Family Information:**
- Mother's Name and Phone (combined)
- Father's Name and Phone (combined)

**Education & Experience:**
- Education Level (P.7, S.4, Certificate, Diploma)
- Years of Experience (numeric)
- Previous Work Details (text area)
- Mother Tongue
- English Proficiency (1-10 scale)

**Professional Information:**
- Role (6 options):
  - Housekeeper
  - House Manager
  - Nanny
  - Chef
  - Elderly Caretaker
  - Nakawere Caretaker

**Status Management:**
- Primary Status (main operational state)
- Secondary Status (additional information)
- Work Status (employment type)

**Medical Information:**
- Hepatitis B Test (Result + Date)
- HIV Test (Result + Date)
- Urine HCG Test (Result + Date)
- Medical Notes

**Media & Documents:**
- Profile Image
- Additional Documents
- ID Scans

---

### 2. Status Management System

**Purpose:** Track complex maid states using dual-status architecture

#### Primary Status (Main Operational State)

| Status | Color | Description | Use Case |
|--------|-------|-------------|----------|
| **Available** | 🟢 Green | Ready for booking | Maid completed training, ready to work |
| **In Training** | 🟡 Yellow | Currently in training program | Maid undergoing training |
| **Booked** | 🔵 Blue | Has a booking but not deployed | Client reserved maid |
| **Deployed** | 🔴 Red | Currently working at client location | Maid actively working |
| **Absconded** | 🟠 Orange | Left without notice | Maid disappeared from assignment |
| **Terminated** | ⚫ Black | Contract ended or dismissed | Employment ended |
| **On Leave** | 🟣 Purple | Temporary absence | Vacation or temporary break |

#### Secondary Status (Additional Information)

Provides additional context to the primary status:

- **Booked** - Can combine with "In Training"
- **Available** - Can indicate availability during other states
- **Deployed** - Current work assignment
- **On Leave** - Temporary status
- **Absconded** - Left position
- **Terminated** - Employment ended

#### Work Status (Employment Type)

| Work Status | Badge Color | Description |
|-------------|-------------|-------------|
| **Brokerage** | 🟡 Warning | Short-term/Emergency work |
| **Long-term** | 🟢 Success | Extended contracts (6+ months) |
| **Part-time** | 🔵 Info | Part-time work only |
| **Full-time** | 🔵 Primary | Full-time work only |

#### Status Combination Examples

```
"In Training + Booked"
→ Maid is currently in training but already reserved by a client

"Available + Brokerage"  
→ Maid is available for short-term emergency work

"Deployed + Full-time"
→ Maid is currently working full-time at a client location

"On Leave + Part-time"
→ Maid is temporarily unavailable but works part-time
```

---

### 3. Client Management System

**Purpose:** Manage client profiles and booking requirements

#### Client Registration (`clients-match.php`)

**Contact Information:**
- Full Name
- Phone Number (WhatsApp preferred)
- Email Address
- National ID / Passport Upload

**Location Details:**
- Country
- City
- Division
- Parish

**Home Environment:**
- Home Type (Apartment, Bungalow, Maisonette, Other)
- Number of Bedrooms
- Number of Bathrooms
- Outdoor Responsibilities

**Household Information:**
- Household Size
- Number of Children
- Children's Ages
- Elderly or Special Needs
- Pets Present

**Service Requirements:**
- General Cleaning (Required/Not Required/Occasionally)
- Laundry & Ironing
- Cooking
- Childcare
- Elderly Care
- Errands & Shopping

**Preferences:**
- Preferred Language
- Work Schedule
- Live-in or Live-out
- Additional Requirements

#### Client Management (`clients.php`)

- View all registered clients
- Client details page
- Export client data to PDF
- Delete client records
- Generate client form link for sharing

---

### 4. Booking System

**Purpose:** Manage maid bookings and deployments

#### Booking Process Flow

```
1. Client Submits Booking Form
   ↓
2. Booking Request Created in Database
   ↓
3. Admin Reviews Booking
   ↓
4. Maid Matched to Client Requirements
   ↓
5. Booking Confirmed
   ↓
6. Maid Status → Booked
   ↓
7. Deployment Scheduled
   ↓
8. Maid Status → Deployed
   ↓
9. Contract Management
   ↓
10. Payment Tracking
```

#### Booking Management (`bookings.php`, `view-booking.php`)

- View all bookings
- Filter by status, date, client
- Booking details view
- Status updates
- Payment tracking
- PDF export of booking details

#### Booking Fields

**Basic Information:**
- Booking ID (auto-generated)
- Client ID (from clients table)
- Maid ID (from maids table)
- Booking Date
- Start Date
- End Date
- Status

**Service Details:**
- Service Type
- Work Schedule
- Contract Type (Live-in/Live-out)
- Duration
- Special Requirements

**Financial Information:**
- Service Fee
- Payment Method
- Payment Status
- Commission
- Total Amount

---

### 5. Trainer Management System

**Purpose:** Manage trainers and training programs

#### Trainer Profiles (`trainers.php`, `add-trainer.php`)

**Trainer Information:**
- Name
- Phone Number
- Email
- Specialization
- Experience (years)
- Status (Active/Inactive)
- Working Hours
- Profile Picture

**Trainer Features:**
- View all trainers
- Add new trainers
- Edit trainer profiles
- Delete trainers
- Performance tracking
- Assignment management

#### Training Dashboard (`trainers-dashbaord.php`)

- Assigned maids overview
- Training progress tracking
- Evaluation submissions
- Performance metrics
- Training calendar

---

### 6. Evaluation & Assessment System

**Purpose:** Track maid performance and training progress

#### Evaluation Categories

1. **Skill Proficiency**
   - Cleaning techniques
   - Cooking skills
   - Childcare abilities
   - Specialized tasks

2. **Work Attitude**
   - Punctuality
   - Reliability
   - Initiative
   - Professionalism

3. **Communication**
   - Language skills
   - Understanding instructions
   - Feedback reception
   - Client interaction

4. **Reliability**
   - Consistency
   - Trustworthiness
   - Honesty
   - Dependability

#### Evaluation Submission (`submissions.php`)

- Submit evaluations
- View evaluation history
- Track KPI scores
- Generate performance reports
- Export evaluations to PDF

#### Scoring System

- **Score Range:** 0-100
- **Rating Categories:**
  - 90-100: Excellent
  - 80-89: Very Good
  - 70-79: Good
  - 60-69: Satisfactory
  - Below 60: Needs Improvement

---

### 7. Dashboard & Analytics

**Purpose:** Provide real-time insights and system overview

#### Main Admin Dashboard (`static/index.php`)

**KPI Widgets:**
- Total Maids
- Available Maids
- In Training
- Deployed Maids
- Total Clients
- Active Bookings
- Revenue This Month
- Pending Evaluations

**Quick Navigation:**
- Reports
- KPI Dashboard
- Maids Management
- Trainers
- Clients
- Bookings

**Charts & Visualizations:**
- Maid status distribution (pie chart)
- Training progress over time (line chart)
- Booking trends (bar chart)
- Revenue analytics (area chart)

#### KPI Dashboard (`kpi-dashboard.php`)

**Advanced Analytics:**
- Training completion rates
- Maid deployment rates
- Client satisfaction metrics
- Revenue trends
- Performance benchmarks

**Interactive Charts:**
- Filter by date range
- Export charts as images
- Drill-down capabilities
- Comparative analysis

#### Reports (`reports.php`)

**Report Types:**
- Maid Performance Reports
- Financial Reports
- Client Activity Reports
- Training Reports
- System Usage Reports

**Export Formats:**
- PDF
- Excel
- CSV
- Print-friendly views

---

### 8. Real-time Chat System

**Purpose:** Enable internal communication between staff

#### Chat Features (`api/chat/`)

**WebSocket Server:**
- Real-time messaging
- Chat rooms
- Direct messaging
- Typing indicators
- Online status

**Chat Interface:**
- Message history
- File sharing
- Emoji support
- Message search
- Notifications

**Implementation:**
- Ratchet PHP WebSocket
- JavaScript WebSocket client
- Message persistence in database
- Security through authentication tokens

#### Starting the Chat Server

```powershell
# PowerShell command
php websocket/chat_server.php
```

---

## 🗄️ Database Architecture

### Database Details

```
Database Name: u622340404_royalmaids
Database Type: MySQL 8.0+
Character Set: utf8mb4
Collation: utf8mb4_unicode_ci
```

### Core Tables

#### 1. `maids` Table

**Purpose:** Store all maid information and profiles

```sql
CREATE TABLE maids (
    maid_id INT AUTO_INCREMENT PRIMARY KEY,
    maid_code VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    mobile_number_2 VARCHAR(20),
    date_of_birth DATE,
    date_of_arrival DATE,
    nationality VARCHAR(50),
    
    -- Status System
    status ENUM('available', 'in-training', 'booked', 'deployed', 
                'absconded', 'terminated', 'on-leave') DEFAULT 'available',
    secondary_status ENUM('booked', 'available', 'deployed', 
                          'on-leave', 'absconded', 'terminated'),
    work_status ENUM('brokerage', 'long-term', 'part-time', 'full-time'),
    
    -- Personal Information
    nin_number VARCHAR(50) NOT NULL UNIQUE,
    lc1_chairperson TEXT,
    mother_name_phone VARCHAR(255) NOT NULL,
    father_name_phone VARCHAR(255) NOT NULL,
    marital_status ENUM('single', 'married') DEFAULT 'single',
    number_of_children INT DEFAULT 0,
    
    -- Location
    tribe VARCHAR(100) NOT NULL,
    village VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    
    -- Education & Experience
    education_level ENUM('P.7', 'S.4', 'Certificate', 'Diploma') DEFAULT 'P.7',
    experience_years INT DEFAULT 0,
    mother_tongue VARCHAR(100) NOT NULL,
    english_proficiency INT DEFAULT 1,
    
    -- Professional
    role ENUM('housekeeper', 'house_manager', 'nanny', 'chef', 
              'elderly_caretaker', 'nakawere_caretaker') DEFAULT 'housekeeper',
    previous_work TEXT,
    
    -- Medical & Documents
    medical_status JSON,
    profile_image VARCHAR(255),
    additional_notes TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_work_status (work_status),
    INDEX idx_role (role),
    INDEX idx_date_arrival (date_of_arrival)
);
```

**Key Features:**
- Auto-incrementing maid_id
- Unique maid_code (auto-generated via trigger)
- Dual status system (primary + secondary + work)
- JSON field for medical status
- Comprehensive indexing for performance

#### 2. `clients` Table

**Purpose:** Store client information and requirements

```sql
CREATE TABLE clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    whatsapp_number VARCHAR(20),
    email VARCHAR(100),
    
    -- Location
    country VARCHAR(50),
    city VARCHAR(50),
    division VARCHAR(50),
    parish VARCHAR(50),
    address TEXT,
    national_id VARCHAR(50),
    
    -- Household Information
    household_size INT,
    number_of_children INT,
    children_ages TEXT,
    elderly_or_special_needs TINYINT(1),
    special_needs_details TEXT,
    pets_present TINYINT(1),
    pet_type VARCHAR(50),
    
    -- Home Details
    home_type VARCHAR(50),
    bedrooms INT,
    bathrooms INT,
    outdoor_responsibilities TEXT,
    
    -- Service Requirements
    preferred_language VARCHAR(50),
    general_cleaning ENUM('Required', 'Not Required', 'Occasionally'),
    laundry_ironing ENUM('Required', 'Not Required', 'Occasionally'),
    cooking ENUM('Required', 'Not Required', 'Occasionally'),
    cooking_notes TEXT,
    childcare ENUM('Required', 'Not Required', 'Occasionally'),
    childcare_notes TEXT,
    elderly_care ENUM('Required', 'Not Required', 'Occasionally'),
    elderly_care_notes TEXT,
    errands ENUM('Required', 'Not Required', 'Occasionally'),
    other_duties TEXT,
    
    -- Work Preferences
    work_schedule VARCHAR(50),
    live_in_preference TINYINT(1),
    start_date DATE,
    
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 3. `trainers` Table

```sql
CREATE TABLE trainers (
    TrainerID INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    Name VARCHAR(100) NOT NULL,
    Phone VARCHAR(20),
    Email VARCHAR(100),
    Specialization VARCHAR(100),
    Experience INT,
    Status ENUM('Active', 'Inactive') DEFAULT 'Active',
    WorkingHours INT DEFAULT 0,
    ProfilePic VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```

#### 4. `users` Table

```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    role ENUM('admin', 'manager', 'trainer', 'client') DEFAULT 'client',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    profile_image VARCHAR(255),
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 5. `bookings` Table

```sql
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    maid_id INT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    start_date DATE,
    end_date DATE,
    status ENUM('pending', 'confirmed', 'active', 'completed', 'cancelled'),
    service_type VARCHAR(100),
    work_schedule VARCHAR(50),
    contract_type ENUM('live-in', 'live-out'),
    service_fee DECIMAL(10, 2),
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'partial', 'paid'),
    commission DECIMAL(10, 2),
    total_amount DECIMAL(10, 2),
    special_requirements TEXT,
    notes TEXT,
    
    FOREIGN KEY (client_id) REFERENCES clients(client_id),
    FOREIGN KEY (maid_id) REFERENCES maids(maid_id)
);
```

#### 6. `training_assignments` Table

```sql
CREATE TABLE training_assignments (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    maid_id INT NOT NULL,
    TrainerID INT NOT NULL,
    program_id INT,
    status ENUM('pending', 'in-progress', 'completed', 'cancelled'),
    start_date DATE,
    end_date DATE,
    completion_percentage INT DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (maid_id) REFERENCES maids(maid_id),
    FOREIGN KEY (TrainerID) REFERENCES trainers(TrainerID)
);
```

#### 7. `evaluation_submissions` Table

```sql
CREATE TABLE evaluation_submissions (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    maid_id INT NOT NULL,
    TrainerID INT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    overall_score DECIMAL(5, 2),
    status ENUM('pending', 'completed', 'reviewed'),
    comments TEXT,
    
    FOREIGN KEY (maid_id) REFERENCES maids(maid_id),
    FOREIGN KEY (TrainerID) REFERENCES trainers(TrainerID)
);
```

#### 8. `evaluation_scores` Table

```sql
CREATE TABLE evaluation_scores (
    score_id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    category_id INT NOT NULL,
    score DECIMAL(5, 2),
    notes TEXT,
    
    FOREIGN KEY (submission_id) REFERENCES evaluation_submissions(submission_id),
    FOREIGN KEY (category_id) REFERENCES evaluation_categories(category_id)
);
```

#### 9. `maid_evaluations` Table

```sql
CREATE TABLE maid_evaluations (
    evaluation_id INT AUTO_INCREMENT PRIMARY KEY,
    maid_id INT NOT NULL,
    trainer_id INT NOT NULL,
    trainee_name VARCHAR(100),
    evaluation_date DATE,
    status ENUM('pending', 'completed'),
    overall_score DECIMAL(5, 2),
    skill_proficiency INT,
    work_attitude INT,
    communication INT,
    reliability INT,
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (maid_id) REFERENCES maids(maid_id),
    FOREIGN KEY (trainer_id) REFERENCES trainers(TrainerID)
);
```

### Database Triggers

#### Auto-generate Maid Code

```sql
DELIMITER $$
CREATE TRIGGER generate_maid_code 
BEFORE INSERT ON maids
FOR EACH ROW
BEGIN
    IF NEW.maid_code IS NULL OR NEW.maid_code = '' THEN
        SET NEW.maid_code = CONCAT('RMH', 
            LPAD(NEW.maid_id, 5, '0'));
    END IF;
END$$
DELIMITER ;
```

---

## 📁 File Structure

### Complete Directory Breakdown

```
royalmaidshub/
│
├── config/                          # Configuration files
│   ├── config.php                  # Main configuration (DB, paths, constants)
│   └── backup.text                 # Backup configuration
│
├── includes/                        # Shared PHP classes and includes
│   ├── Database.php                # Database singleton class
│   ├── MaidService.php             # Maid business logic
│   ├── ChatService.php             # Chat functionality
│   ├── DashboardData.php           # Dashboard data provider
│   ├── ChartDataService.php        # Chart data processing
│   ├── NotificationService.php     # Notification handling
│   ├── auth.php                    # Authentication logic
│   ├── header.php                  # Common header HTML
│   ├── footer.php                  # Common footer HTML
│   └── path_helper.php             # Path utilities
│
├── static/                          # Main application files
│   ├── index.php                   # Admin dashboard (main entry)
│   ├── login.php                   # Login page
│   ├── logout.php                  # Logout handler
│   ├── profile.php                 # User profile management
│   ├── settings.php                # System settings
│   │
│   ├── maids.php                   # Maids listing dashboard
│   ├── add-maid.php                # Add new maid form
│   ├── edit-maid.php               # Edit maid details
│   ├── maid-profile.php            # Individual maid profile
│   ├── delete-maid.php             # Delete maid handler
│   │
│   ├── clients.php                 # Clients listing
│   ├── client_details.php          # Client detail view
│   ├── delete_client.php           # Delete client handler
│   ├── confirm_delete_client.php   # Delete confirmation
│   ├── export_client_pdf.php       # Export client to PDF
│   │
│   ├── trainers.php                # Trainers listing
│   ├── add-trainer.php             # Add new trainer
│   ├── edit-trainer.php            # Edit trainer
│   ├── view-trainer.php            # Trainer profile
│   ├── delete-trainer.php          # Delete trainer
│   │
│   ├── bookings.php                # Bookings management
│   ├── view-booking.php            # Booking details
│   ├── export_booking_pdf.php      # Export booking to PDF
│   │
│   ├── submissions.php             # Evaluation submissions
│   ├── view-evaluation.php         # View evaluation details
│   ├── export_maid_evaluation_pdf.php  # Export evaluation PDF
│   │
│   ├── kpi-dashboard.php           # KPI analytics dashboard
│   ├── reports.php                 # Advanced reports
│   ├── users.php                   # User management
│   │
│   ├── includes/                   # Static includes (duplicated from root)
│   ├── uploads/                    # Uploaded files
│   ├── css/                        # Compiled CSS
│   ├── js/                         # JavaScript files
│   ├── img/                        # Images
│   └── fonts/                      # Custom fonts
│
├── api/                            # API endpoints
│   ├── maids.php                   # Maids API
│   ├── maid-details.php            # Get maid details
│   ├── process_maid.php            # Process maid data
│   ├── update-maid.php             # Update maid API
│   ├── delete_maid.php             # Delete maid API
│   ├── submission-details.php      # Evaluation submissions
│   └── chat/                       # Chat WebSocket APIs
│       ├── send_message.php
│       ├── get_messages.php
│       └── get_online_users.php
│
├── uploads/                        # File uploads
│   ├── maids/                      # Maid profile images
│   ├── id_documents/               # ID document scans
│   └── avatars/                    # User avatars
│
├── src/                            # Source assets (pre-build)
│   ├── scss/                       # SASS stylesheets
│   ├── js/                         # JavaScript source
│   ├── img/                        # Source images
│   └── fonts/                      # Font files
│
├── css/                            # Public CSS
│   ├── styles.css                  # Main styles
│   ├── hero.css                    # Hero section styles
│   ├── tailwind.css                # Tailwind output
│   └── modal.css                   # Modal styles
│
├── js/                             # Public JavaScript
│   ├── main.js                     # Main application JS
│   ├── maids-dashboard.js          # Maids dashboard logic
│   ├── booking.js                  # Booking functionality
│   ├── hero.js                     # Hero animations
│   ├── team.js                     # Team page
│   └── config.js                   # JS configuration
│
├── database/                       # Database files
│   ├── setup_booking_tables.php    # Booking tables setup
│   └── migrations/                 # Database migrations
│
├── vendor/                         # Composer dependencies
│   ├── setasign/fpdf/             # PDF generation
│   ├── cboden/ratchet/            # WebSocket server
│   └── symfony/                    # Symfony components
│
├── royal-maids-laravel/           # Laravel migration (in progress)
│   ├── app/                       # Laravel application
│   ├── resources/                 # Views and assets
│   ├── routes/                    # Route definitions
│   ├── database/                  # Migrations and seeds
│   └── public/                    # Public assets
│
├── Documentation Files
│   ├── README.md                          # Project overview
│   ├── SYSTEM_CHANGES_DOCUMENTATION.md    # System changes
│   ├── ROYAL_MAIDS_HUB_COMPLETE_SYSTEM_GUIDE.html
│   ├── ROYAL_MAIDS_HUB_FEATURES_GUIDE.html
│   ├── HOSTINGER-DEPLOYMENT-GUIDE.md      # Deployment guide
│   ├── MIGRATION_PLAN.md                  # Laravel migration plan
│   └── CHANGELOG.md                       # Version history
│
├── Database Scripts
│   ├── complete_database.sql      # Complete database schema
│   ├── quick_fix.sql              # Quick fixes
│   ├── smart_fix.php              # Smart database updates
│   ├── fix_database.php           # Database repair
│   ├── cleanup_database.php       # Database cleanup
│   ├── database_update.sql        # Update scripts
│   ├── add_date_of_arrival.sql    # Add arrival date
│   ├── fix_missing_columns.sql    # Fix missing columns
│   └── update_medical_fields.sql  # Update medical fields
│
├── Utilities & Tools
│   ├── composer.json              # PHP dependencies
│   ├── package.json               # Node dependencies
│   ├── webpack.config.js          # Webpack configuration
│   ├── tailwind.config.js         # Tailwind configuration
│   ├── postcss.config.js          # PostCSS configuration
│   └── start_chat_server.ps1     # Chat server startup script
│
├── Public Pages
│   ├── index.html                 # Public homepage
│   ├── booking.php                # Public booking form
│   ├── clients-match.php          # Client registration
│   ├── maids.php                  # Public maid browser
│   ├── navbar.php                 # Public navigation
│   └── robots.txt                 # SEO robots file
│
└── Configuration
    ├── .env                       # Environment variables
    ├── .gitignore                 # Git ignore rules
    └── LICENSE                    # License file
```

### Key File Purposes

**Authentication:**
- `login.php` - User authentication
- `logout.php` - Session termination
- `includes/auth.php` - Auth helper functions

**Main Dashboards:**
- `static/index.php` - Main admin dashboard
- `static/maids.php` - Maids management
- `static/kpi-dashboard.php` - Analytics dashboard

**CRUD Operations:**
- `add-*.php` - Create new records
- `edit-*.php` - Update existing records
- `delete-*.php` - Delete records
- `view-*.php` - View details

**API Endpoints:**
- `api/maids.php` - Maids AJAX operations
- `api/process_maid.php` - Process maid forms
- `api/chat/*` - Real-time chat

---

## 📊 Dashboard Systems

### 1. Main Admin Dashboard (`static/index.php`)

**Purpose:** Central hub for all administrative operations

#### Widgets & Metrics

**Top KPI Cards:**
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ Total Maids │ Available  │ In Training │  Deployed   │
│     150     │     45     │      30     │     60      │
│   +12%      │   +5%      │    -3%      │    +8%      │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

**Secondary Metrics:**
- Total Clients: 85
- Active Bookings: 60
- Revenue This Month: UGX 15,000,000
- Pending Evaluations: 12

**Quick Actions:**
- Add New Maid
- Add Booking
- View Reports
- Manage Trainers

**Recent Activity Feed:**
- New maid registered: Jane Doe
- Booking confirmed: Client #123
- Evaluation completed: Maid #RMH00045
- Training completed: 5 maids

### 2. Maids Dashboard (`static/maids.php`)

**Features:**
- **Grid/List View Toggle**
- **Advanced Filters:**
  - Status (All, Available, In Training, Booked, Deployed)
  - Role (Housekeeper, Nanny, Chef, etc.)
  - Experience (0-2 years, 3-5 years, 6+ years)
  - Education Level
  - Work Status

- **Search:**
  - By name
  - By maid code
  - By phone number

- **Bulk Operations:**
  - Export selected to PDF
  - Update status
  - Delete multiple

**Maid Card Display:**
```
┌──────────────────────────────────────┐
│  [Profile Image]                     │
│                                      │
│  Jane Doe                            │
│  📱 +256 700 123456                  │
│                                      │
│  🟢 Available + Brokerage            │
│  📅 Days on Training: 45             │
│  💼 Experience: 3 years              │
│  👔 Role: Housekeeper                │
│  📆 Arrived: Jan 15, 2025            │
│                                      │
│  [View Profile] [Edit] [Delete]     │
└──────────────────────────────────────┘
```

### 3. KPI Dashboard (`kpi-dashboard.php`)

**Analytics & Charts:**

**Training Progress Chart:**
- Line chart showing training completion over time
- Filter by date range
- Breakdown by trainer

**Status Distribution:**
- Pie chart of maid status distribution
- Percentages and counts
- Interactive segments

**Revenue Analytics:**
- Monthly revenue trends
- Booking vs. actual revenue
- Profit margins

**Performance Metrics:**
- Average KPI scores by category
- Top-performing maids
- Areas needing improvement

### 4. Submissions Dashboard (`submissions.php`)

**Purpose:** Manage training evaluations and assessments

**Features:**
- View all submissions
- Filter by status, trainer, date
- Approve/reject evaluations
- Export to PDF
- Track KPI trends

---

## 🔌 API Endpoints

### Maids API (`api/maids.php`)

**GET /api/maids.php**
```
Parameters:
- page (int): Page number
- limit (int): Items per page
- search (string): Search query
- status (string): Filter by status
- role (string): Filter by role

Response:
{
  "data": [...maids...],
  "total": 150,
  "page": 1,
  "totalPages": 15
}
```

**POST /api/process_maid.php**
```
Handles maid creation and updates
Multipart form data with file uploads
```

**PUT /api/update-maid.php**
```
Parameters:
- maid_id (int): Maid ID
- field (string): Field to update
- value (mixed): New value
```

**DELETE /api/delete_maid.php**
```
Parameters:
- maid_id (int): Maid ID to delete
```

### Chat API (`api/chat/`)

**POST /api/chat/send_message.php**
```json
{
  "sender_id": 1,
  "receiver_id": 5,
  "message": "Hello!",
  "type": "text"
}
```

**GET /api/chat/get_messages.php**
```
Parameters:
- user_id (int): Current user
- chat_id (int): Chat conversation ID
- limit (int): Number of messages
```

---

## 🔒 Security Features

### Authentication & Authorization

**Password Security:**
- Passwords hashed using `password_hash()` (bcrypt)
- Minimum 8 characters required
- Password strength validation
- Secure password reset via email

**Session Management:**
- Session timeout: 30 minutes
- Secure session cookies
- Session regeneration on login
- CSRF token validation

### SQL Injection Prevention

**Prepared Statements:**
```php
$stmt = $dbh->prepare("SELECT * FROM maids WHERE maid_id = :id");
$stmt->execute([':id' => $maidId]);
```

All database queries use PDO prepared statements with parameter binding.

### XSS Protection

**Output Escaping:**
```php
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

echo h($user_input);
```

### File Upload Security

**Validation:**
- File type checking
- File size limits (2MB for images)
- Secure file naming
- Storage outside public directory
- Virus scanning (recommended)

### Access Control

**Role-Based Permissions:**
```php
if ($_SESSION['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}
```

### Audit Logging

**Activity Tracking:**
- User login/logout
- Data modifications
- File uploads
- Permission changes

---

## 🚀 Deployment & Configuration

### System Requirements

**Minimum Requirements:**
- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache 2.4+ (or Nginx)
- 512MB RAM minimum
- 1GB disk space minimum

**Recommended:**
- PHP 8.2+
- MySQL 8.0+
- 2GB RAM
- 5GB disk space
- SSL certificate

### Installation Steps

**1. Server Setup**
```powershell
# Install WAMP/XAMPP
# Configure Apache and MySQL
# Enable required PHP extensions:
# - PDO
# - mysqli
# - mbstring
# - json
# - fileinfo
```

**2. Database Setup**
```sql
-- Create database
CREATE DATABASE u622340404_royalmaids 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Import schema
mysql -u root -p u622340404_royalmaids < complete_database.sql
```

**3. Configuration**

Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'u622340404_royalmaids');
```

**4. File Permissions**
```powershell
# Set upload directory permissions
icacls "uploads" /grant Users:F /T
icacls "static/uploads" /grant Users:F /T
```

**5. Composer Dependencies**
```powershell
composer install
```

**6. Build Assets**
```powershell
npm install
npm run build
```

### Production Deployment

**1. Environment Configuration**
```
- Set production database credentials
- Enable error logging (disable display)
- Configure SMTP for emails
- Set up SSL certificate
- Configure backup schedule
```

**2. Security Hardening**
```
- Disable directory listing
- Remove development files
- Secure file uploads directory
- Implement rate limiting
- Enable HTTPS only
```

**3. Performance Optimization**
```
- Enable OPcache
- Configure database indexes
- Implement caching (Redis/Memcached)
- Optimize images
- Enable gzip compression
```

### Backup Strategy

**Automated Daily Backups:**
```powershell
# Database backup
mysqldump -u root -p u622340404_royalmaids > backup_$(date +%Y%m%d).sql

# File backup
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz uploads/
```

**Backup Locations:**
- Local server backups
- Cloud storage (recommended)
- Off-site backups

---

## 🚀 Version 3.0 (Laravel 12 + Livewire)

Status: In Progress (Active migration from v2.0 plain PHP)

Summary: Version 3.0 upgrades Royal Maids Hub to a modern Laravel 12 + Livewire stack for faster development, stronger security, cleaner architecture, and long-term maintainability while keeping hosting costs low.

### Goals

- Migrate core features from v2.0 (plain PHP) to Laravel 12
- Adopt Livewire for rich, reactive UIs without heavy JavaScript
- Keep operating costs low (database queues, file cache, shared/VPS hosting)
- Strengthen security (Fortify auth, email verification, 2FA)
- Establish automated testing and CI for reliability

### Tech Stack (Cost‑effective defaults)

- Backend: Laravel 12 (PHP 8.2+), Eloquent ORM
- UI: Livewire v3 (with Volt/Flux), Blade, Tailwind CSS
- Auth: Laravel Fortify (login, registration, email verification, optional 2FA)
- Build: Vite (with Laravel plugin)
- Testing: Pest (feature + unit tests)
- Queue: Database driver (no Redis needed initially)
- Cache: File cache locally, upgrade to Redis later if needed
- DB: MySQL 8 (MariaDB 10.6+ compatible)
- Mail: SES/Mailgun/MailerSend free tier (choose 1 per environment)

### New Laravel Project Structure (this repo)

Key paths used in v3.0:

- app/Livewire/Dashboard/
  - AdminDashboard.php, TrainerDashboard.php, ClientDashboard.php
- resources/views/livewire/dashboard/
  - admin-dashboard.blade.php, trainer-dashboard.blade.php, client-dashboard.blade.php
- routes/web.php
  - Uses Fortify + Volt for settings pages and the main dashboard route
- app/Models/User.php (extend with roles/permissions)
- database/migrations/* (users, jobs, 2FA columns etc.)

### Roles and Dashboards

- Roles: admin, trainer, client
- Entry: /dashboard (guards: auth + verified). We’ll route role-specific dashboards:

Example routing approach (pseudo):

```php
// routes/web.php
use App\Livewire\Dashboard\{AdminDashboard, TrainerDashboard, ClientDashboard};

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // redirect based on role
        $role = auth()->user()->role; // string: admin|trainer|client
        return match($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'trainer' => redirect()->route('dashboard.trainer'),
            default => redirect()->route('dashboard.client'),
        };
    })->name('dashboard');

    Route::get('/admin', AdminDashboard::class)->name('dashboard.admin');
    Route::get('/trainer', TrainerDashboard::class)->name('dashboard.trainer');
    Route::get('/client', ClientDashboard::class)->name('dashboard.client');
});
```

For robust authorization, prefer a roles table or a package like spatie/laravel-permission (free). To stay lean, start with a simple role column on users and add policies/gates.

### Data Model (Eloquent) – v2.0 → v3.0 mapping

- Maid (maids) → Model: Maid
- Client (clients) → Model: Client
- Booking (bookings) → Model: Booking
- Trainer (trainers) → Model: Trainer
- TrainingAssignment (training_assignments) → Model: TrainingAssignment
- Evaluation (evaluation_submissions / evaluation_scores / maid_evaluations) → Models: EvaluationSubmission, EvaluationScore, MaidEvaluation

Start with current migrations in database/migrations and add new ones for missing entities. Use enums or string columns for statuses; add proper indexes.

### Migration of Data from v2.0

Low‑risk strategy:

1) Freeze legacy writes (maintenance window)
2) Export legacy DB (mysqldump)
3) Create import commands in Laravel (Artisan) to map/transform old rows
4) Run import in staging, verify with sample UI/tests
5) Run import in production window, flip DNS or web root

Skeleton Artisan command (example):

```php
// app/Console/Commands/ImportLegacyMaids.php
protected $signature = 'rmh:import-maids {--chunk=500}';
public function handle() {
    // connect to legacy DB via env LEGACY_DB_* (separate connection)
    // stream records in chunks, transform and insert via Eloquent
}
```

### Environment & Configuration

- .env keys
  - APP_ENV, APP_URL, APP_KEY
  - DB_* for MySQL
  - QUEUE_CONNECTION=database
  - CACHE_STORE=file (local), SESSION_DRIVER=file
  - MAIL_MAILER=smtp, MAIL_HOST/PORT/USER/PASS (SES/Mailgun/MailerSend)
  - TRUSTED_PROXIES if behind CDN/reverse proxy

### Local Development

```powershell
# from project root
composer install
cp .env.example .env  # or copy manually on Windows
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

Optional: use the provided composer script for concurrent dev (server + queue + vite) if Node is installed:

```powershell
composer dev
```

### Queues, Scheduling, and Mail (cost‑effective)

- Queues: database driver + php artisan queue:work (or queue:listen in dev)
- Scheduler: php artisan schedule:run every minute via cron (Linux) or Task Scheduler (Windows)
- Mail: choose one transactional provider per environment using free tier

### Deployment (low‑cost options)

- VPS (Hetzner/DigitalOcean/Linode ~€4–€6/mo) or shared hosting that supports PHP 8.2
- Serve with Nginx/Apache + PHP‑FPM
- Use database queues (no Redis). Add Redis later if needed.
- Storage: local disk + backups; upgrade to S3/R2 later
- Zero‑downtime optional. Simple SSH deploy is fine initially.

Basic steps:

1) git pull on server
2) composer install --no-dev --optimize-autoloader
3) php artisan migrate --force
4) php artisan config:cache && php artisan route:cache && php artisan view:cache
5) restart queue workers (if any)

### Security

- Fortify: email verification + optional 2FA
- CSRF, encryption, signed URLs (native Laravel)
- Authorization via Gates/Policies; restrict admin/trainer routes
- Validate uploads with Laravel validation rules; store outside public when needed

### Testing & QA

- Pest for unit/feature tests
- Minimal smoke tests: auth, dashboard visibility per role, booking creation
- Example test idea:

```php
it('shows the right dashboard per role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    actingAs($admin)->get('/dashboard')->assertRedirect(route('dashboard.admin'));
});
```

### Observability & Logs (free tiers)

- Laravel Log (daily files), rotate and ship with a lightweight agent if needed
- Optional: Sentry/Logtail (free tiers) for error tracking
- Pail (dev) for nicer local logging

### Roadmap (next)

- Wire real data into Livewire dashboards
- Implement role middleware and policies
- Migrate legacy data via import commands
- Add booking flows + payments (if applicable)
- Add background jobs for notifications/emails

---

## 🔄 Future Migration (Laravel)

### Laravel 12 Migration Plan

**Status:** In Progress (Phase 1)

**Timeline:** 12 weeks total

### Current Progress

**Phase 1: Foundation (Completed)**
- ✅ Laravel 12 application created
- ✅ Environment configured
- ✅ Database connection established
- ✅ Basic authentication setup

**Phase 2: Database Migration (In Progress)**
- ⏳ Eloquent models creation
- ⏳ Relationships mapping
- ⏳ Data migration scripts

### Benefits of Laravel Migration

**Technical Benefits:**
- Modern MVC architecture
- Eloquent ORM for database operations
- Built-in authentication and authorization
- Better code organization and maintainability
- Comprehensive testing framework
- Enhanced security features

**Development Benefits:**
- Faster feature development
- Easier maintenance
- Better code reusability
- Strong community support
- Rich ecosystem of packages

**Performance Benefits:**
- Query optimization with Eloquent
- Built-in caching mechanisms
- Queue management for async tasks
- Better session management
- Optimized asset compilation

### Migration Approach

**Gradual Migration Strategy:**
1. Build new features in Laravel
2. Migrate existing features incrementally
3. Maintain backward compatibility
4. Parallel testing environment
5. Gradual traffic migration

---

## 📚 Appendix

### Common Tasks

#### Adding a New Maid
1. Navigate to **Maids → Add Maid**
2. Fill in all required fields
3. Upload profile image
4. Set initial status
5. Submit form
6. Verify maid code generation

#### Creating a Booking
1. Navigate to **Bookings → New Booking**
2. Select client (or create new)
3. Choose maid based on requirements
4. Set booking dates
5. Configure service details
6. Submit booking
7. Update maid status to "Booked"

#### Running Database Updates
```powershell
# Run smart fix (recommended)
php smart_fix.php

# Or run specific SQL file
mysql -u root -p u622340404_royalmaids < quick_fix.sql
```

#### Starting WebSocket Chat Server
```powershell
# Run the PowerShell script
.\start_chat_server.ps1

# Or directly
php websocket/chat_server.php
```

### Troubleshooting

**Database Connection Issues:**
```
Check config/config.php credentials
Verify MySQL service is running
Check firewall settings
```

**File Upload Failures:**
```
Verify directory permissions
Check PHP upload_max_filesize setting
Ensure adequate disk space
```

**Session Timeout:**
```
Adjust SESSION_TIMEOUT in config.php
Check PHP session configuration
Verify session directory permissions
```

### Support & Contact

**Technical Support:**
- Email: support@royalmaidshub.com
- Documentation: /docs/
- Issue Tracker: GitHub repository

**Development Team:**
- **Developer:** Synthilogic Enterprises
- **Project Manager:** [Contact Info]
- **Database Admin:** [Contact Info]

---

## 📝 Version History

### Version 3.0 (Laravel 12) – In Progress
- Migrating from plain PHP to Laravel 12 + Livewire
- Fortify authentication with email verification and optional 2FA
- Role-based dashboards scaffolded (Admin, Trainer, Client)
- Database queues and file cache for low-cost operations
- Pest testing baseline and CI-ready scripts
- Gradual data import plan from legacy database

### Version 2.0 (Current)
- Enhanced maid registration with 50+ fields
- Dual status system implementation
- Medical status tracking
- Improved UI/UX
- Advanced filtering and search
- PDF export capabilities

### Version 1.5
- Initial maid management system
- Client registration
- Basic booking functionality
- Trainer management
- Simple reporting

### Version 1.0
- Core database setup
- Basic CRUD operations
- User authentication
- Simple dashboard

---

## 🏁 Conclusion

Royal Maids Hub is a comprehensive, feature-rich platform designed to streamline the entire lifecycle of domestic worker management. From registration and training to deployment and performance tracking, the system provides all the tools needed to run an efficient maid agency.

**Key Strengths:**
- ✅ Comprehensive feature set
- ✅ Dual status tracking
- ✅ Robust security
- ✅ Advanced analytics
- ✅ User-friendly interface
- ✅ Scalable architecture

**Future Development:**
- 🔄 Laravel 12 migration
- 📱 Mobile application
- 🤖 AI-powered matching
- 📊 Advanced analytics
- 🌍 Multi-language support

---

**Document Version:** 2.0  
**Last Updated:** October 22, 2025  
**Maintained by:** Synthilogic Enterprises

For the latest updates and documentation, visit the project repository or contact the development team.
