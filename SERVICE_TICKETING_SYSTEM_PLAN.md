---
# Service / Ticketing System Implementation Plan
## 🚦 Implementation Progress (as of 2025-10-25)

|
| Feature/Phase                                 | Status         | Notes |
|-----------------------------------------------|----------------|-------|
| Database schema & migrations                  | ✅ Complete    | All core tables created |
| Ticket model with tier/SLA logic              | ✅ Complete    | Auto-priority, SLA, on-behalf logic implemented |
| Ticket creation UI (Livewire)                 | ✅ Complete    | Professional form, dynamic fields, brand styling |
| Dynamic form logic (priority preview, on behalf)| ✅ Complete  | Livewire reactivity, file uploads, tier preview |
| Ticket list/index UI                          | ✅ Complete    | Filters, search, pagination, badges, SLA indicators |
| Ticket detail/show UI                         | ✅ Complete    | 2-column layout, status management, assignment |
| Ticket seeder with test data                  | ✅ Complete    | 15+ realistic scenarios, all tiers, on-behalf cases |
| End-to-end ticket creation testing            | ⏳ In Progress | Testing as all roles, validating business rules |
| Comments, attachments, status history         | ⏳ Not Started | Models scaffolded, UI pending |
| Assignment, workflow, escalation              | ⏳ Not Started | |
| Notifications (email, in-app, SMS)            | ⏳ Not Started | |
| Analytics & reporting                         | ⏳ Not Started | |
| Satisfaction ratings, SLA dashboard           | ⏳ Not Started | |

---
## Royal Maids Hub - Customer & Operations Support

---

## 🎯 Executive Summary

Implement a comprehensive ticketing system within Royal Maids Hub to manage:
- **Client Issues**: Service complaints, maid performance concerns, billing disputes
- **Maid Support**: Deployment problems, workplace issues, payment queries
- **Operations**: Facility issues, equipment needs, administrative requests
- **Training**: Trainer concerns, program issues, evaluation disputes

---

## 📋 Business Requirements

### Use Cases for Royal Maids Context

#### 1. **Client-Submitted Tickets**
- "The maid assigned to me is not showing up"
- "Quality of cleaning service is not satisfactory"
- "I want to request a maid replacement"
- "Billing issue - charged incorrectly"
- "Schedule change request"
- "Extension of service contract"

#### 2. **Maid-Submitted Tickets**
- "Client is not paying salary on time"
- "Unsafe working conditions at deployment location"
- "Need emergency leave approval"
- "Lost or damaged equipment"
- "Training certificate not received"
- "Accommodation issue"

#### 3. **Trainer-Submitted Tickets**
- "Training materials shortage"
- "Maid not attending scheduled sessions"
- "Equipment malfunction"
- "Facility maintenance needed"

#### 4. **Admin/Operations Tickets**
- "Background check delayed for new maid"
- "Medical clearance document expired"
- "Visa/work permit issue"
- "Vehicle breakdown - deployment delayed"

---

## 🎫 Ticket Creation Scenarios & Workflows

### Who Can Create Tickets for Whom?

#### Scenario 1: **Client Creates Own Ticket (Self-Service)**
**Context**: Client logs into their portal and submits an issue
- **Requester**: Client
- **On Behalf Of**: NULL (self)
- **Priority Logic**: 
  - Base priority: User-selected or Medium (default)
  - **Auto-boost**: If client has Platinum package → boost to High/Urgent
  - **Auto-boost**: If client has Gold package → boost to Medium/High
  - Silver remains as-is
- **Example**: 
# Service / Ticketing System Implementation Plan

## 🚦 Implementation Progress (as of 2025-10-25)

| Feature/Phase                                 | Status         | Notes |
|-----------------------------------------------|----------------|-------|
  Client: John Doe (Platinum)
  Issue: "Maid not showing up"
  User selects: "Medium" priority
  System auto-boosts to: "Urgent" (Platinum tier)
  SLA: 15 min response, 2 hour resolution
  ```

#### Scenario 2: **Admin Creates Ticket for Client (Phone Call)**
**Context**: Client calls support, admin creates ticket on their behalf
- **Requester**: Admin (e.g., Sarah - Customer Service)
- **On Behalf Of**: Client (John Doe)
- **Display**: "Ticket created by Admin Sarah on behalf of Client John Doe"
- **Priority Logic**: Same tier-based auto-boost applies
- **Notifications**: Client receives email: "A support ticket has been created for you"
- **Example**:
  ```
  Phone call received from John Doe (Platinum client)
  Issue: "Billing problem - double charged"
  Admin creates ticket as "created_on_behalf_of: John Doe"
  Auto-priority: Critical (Platinum + Billing issue)
  Client gets email with ticket number
  ```

#### Scenario 3: **Trainer Creates Ticket for Client**
**Context**: Trainer interacts with client at deployment site, client reports issue
- **Requester**: Trainer (e.g., Emily)
- **On Behalf Of**: Client (Jane Smith)
- **Use Case**: Client mentions problem during trainer visit
- **Priority Logic**: Tier-based boost + trainer can flag as urgent
- **Audit Trail**: "Ticket created by Trainer Emily on behalf of Client Jane Smith"
- **Example**:
  ```
  Trainer Emily visits Jane Smith (Gold client)
  Jane mentions: "Maid damaged expensive vase"
  Emily creates ticket immediately from mobile
  Marked as: On behalf of Jane Smith
  Priority: High (Gold tier + property damage)
  ```

#### Scenario 4: **Admin Creates Ticket for Maid**
**Context**: Maid calls with concern (no portal access or emergency)
- **Requester**: Admin/HR
- **On Behalf Of**: Maid (Mary)
- **Use Case**: Maid reports safety issue, payment delay, etc.
- **Priority Logic**: Based on issue type (safety = urgent)
- **Example**:
  ```
  Maid Mary calls: "Client locked me in room"
  Admin creates emergency ticket
  On behalf of: Maid Mary
  Priority: Critical (safety issue)
  Escalation: Immediate to management
  ```

#### Scenario 5: **Trainer Creates Ticket for Trainer Issue**
**Context**: Trainer submits issue about training facility/materials
- **Requester**: Trainer (self)
- **On Behalf Of**: NULL
- **Priority**: Based on impact (equipment broken = high)
- **Example**:
  ```
  Trainer creates: "Projector not working"
  Priority: Medium
  Department: Training/Operations
  ```

#### Scenario 6: **Client Creates Ticket About Maid**
**Context**: Client reports maid performance/behavior issue
- **Requester**: Client (self)
- **Related**: Maid ID, Booking ID, Deployment ID
- **Priority Logic**: Tier-based boost
- **Workflow**: 
  1. Client creates ticket
  2. Maid is **notified** (transparency)
  3. Admin investigates both sides
  4. Resolution recorded
- **Example**:
  ```
  Client (Platinum): "Maid broke dishwasher"
  Related: Maid ID #45, Booking #123
  Priority: Urgent (Platinum)
  Maid receives notification: "A ticket has been created related to your deployment"
  ```

#### Scenario 7: **Maid Creates Ticket About Client**
**Context**: Maid reports client misconduct, unsafe conditions
- **Requester**: Maid (self or via admin)
- **Related**: Client ID, Deployment ID
- **Priority**: Safety issues = Critical, Others based on severity
- **Confidentiality**: Maid identity protected if safety concern
- **Example**:
  ```
  Maid: "Client making inappropriate advances"
  Priority: Critical
  Confidential flag: TRUE
  Immediate escalation to management
  Maid removed from deployment pending investigation
  ```

#### Scenario 8: **System Auto-Creates Ticket**
**Context**: Automated triggers for SLA breaches, missed payments, expiring documents
- **Requester**: System (automated)
- **Related**: Booking/Maid/Client as applicable
- **Examples**:
  ```
  - "Maid contract expires in 7 days - no renewal"
  - "Booking payment overdue by 5 days"
  - "Medical clearance expires tomorrow"
  - "SLA breach: Ticket #1234 not resolved in time"
  ```

---

## 🎯 Tier-Based Priority Matrix

### Automatic Priority Rules

| Package Tier | User-Selected Priority | Auto-Adjusted Priority | First Response SLA | Resolution SLA |
|--------------|------------------------|------------------------|-------------------|----------------|
| **Platinum** | Low                    | Medium                 | 30 minutes        | 4 hours        |
| **Platinum** | Medium                 | High                   | 15 minutes        | 2 hours        |
| **Platinum** | High                   | Urgent                 | 10 minutes        | 1 hour         |
| **Platinum** | Urgent                 | **Critical**           | 5 minutes         | 30 minutes     |
| **Gold**     | Low                    | Low                    | 2 hours           | 24 hours       |
| **Gold**     | Medium                 | Medium                 | 1 hour            | 8 hours        |
| **Gold**     | High                   | High                   | 30 minutes        | 4 hours        |
| **Gold**     | Urgent                 | Urgent                 | 15 minutes        | 2 hours        |
| **Silver**   | Low                    | Low                    | 4 hours           | 48 hours       |
| **Silver**   | Medium                 | Medium                 | 2 hours           | 24 hours       |
| **Silver**   | High                   | High                   | 1 hour            | 12 hours       |
| **Silver**   | Urgent                 | High                   | 30 minutes        | 6 hours        |

### Special Priority Overrides

**Critical Issues (Auto-set to Critical regardless of tier)**:
- Safety concerns (client or maid in danger)
- Legal/compliance issues (visa expiration, work permit)
- Payment fraud detected
- Major property damage
- Medical emergencies

**Type-Based Priority Boosts**:
```php
// Billing issues for Platinum → always Urgent+
// Safety concerns → always Critical
// Training issues → default Medium (unless urgent flagged)
// Operations → varies by impact
```

### Visual Indicators

```
🔴 CRITICAL - Platinum Urgent / Safety / Emergency
🟠 URGENT - Platinum High / Gold Urgent
🟡 HIGH - Platinum Medium / Gold High / Silver Urgent
🔵 MEDIUM - Gold Medium / Silver High
⚪ LOW - Silver Medium/Low
```

---

## 📋 Enhanced Ticket Creation Form

### Dynamic Form Fields Based on Context

#### **On Behalf Of** Section (Admin/Trainer Only)
```html
<!-- Only visible to admin/trainer roles -->
<div class="form-section" *ngIf="userRole === 'admin' || userRole === 'trainer'">
    <label class="form-label">Creating ticket on behalf of:</label>
    <div class="flex gap-4">
        <label class="radio">
            <input type="radio" wire:model="onBehalfType" value="self" checked>
            <span>Myself</span>
        </label>
        <label class="radio">
            <input type="radio" wire:model="onBehalfType" value="client">
            <span>Client</span>
        </label>
        <label class="radio">
            <input type="radio" wire:model="onBehalfType" value="maid">
            <span>Maid</span>
        </label>
    </div>
    
    <!-- Client selector -->
    <div *ngIf="onBehalfType === 'client'">
        <select wire:model="onBehalfOfClientId" class="form-select">
            <option value="">Select Client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">
                    {{ $client->contact_person }} 
                    <span class="badge badge-{{ $client->package_tier }}">
                        {{ ucfirst($client->package_tier) }}
                    </span>
                </option>
            @endforeach
        </select>
        
        <!-- Auto-display client's package tier info -->
        @if($selectedClient)
            <div class="alert alert-info">
                This client has a <strong>{{ ucfirst($selectedClient->package_tier) }}</strong> package.
                Priority will be automatically adjusted for faster response.
            </div>
        @endif
    </div>
    
    <!-- Maid selector -->
    <div *ngIf="onBehalfType === 'maid'">
        <select wire:model="onBehalfOfMaidId" class="form-select">
            <option value="">Select Maid</option>
            @foreach($maids as $maid)
                <option value="{{ $maid->id }}">{{ $maid->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>
```

#### **Priority Selection with Auto-Boost Preview**
```html
<div class="form-section">
    <label class="form-label">Priority</label>
    <select wire:model.live="userSelectedPriority" class="form-select">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
        <option value="urgent">Urgent</option>
    </select>
    
    <!-- Live preview of auto-adjusted priority -->
    @if($autoAdjustedPriority !== $userSelectedPriority)
        <div class="alert alert-success mt-2">
            <flux:icon.arrow-up class="w-4 h-4" />
            Priority will be automatically boosted to 
            <strong>{{ ucfirst($autoAdjustedPriority) }}</strong>
            based on {{ $clientPackageTier }} package tier.
            <br>
            <small>
                Expected response: {{ $slaResponseTime }} | 
                Expected resolution: {{ $slaResolutionTime }}
            </small>
        </div>
    @endif
</div>
```

#### **Related Entity Smart Linking**
```html
<div class="form-section">
    <label class="form-label">Related to:</label>
    
    <!-- Smart search across clients/maids/bookings -->
    <input type="text" 
           wire:model.live="relatedEntitySearch" 
           placeholder="Search client, maid, or booking..."
           class="form-input">
    
    <!-- Search results -->
    @if($searchResults)
        <div class="search-results">
            @foreach($searchResults as $result)
                <div class="search-result-item" wire:click="selectEntity('{{ $result->type }}', {{ $result->id }})">
                    <div class="flex items-center gap-2">
                        @if($result->type === 'client')
                            <flux:icon.user class="w-4 h-4" />
                            <span>{{ $result->name }}</span>
                            <span class="badge badge-{{ $result->package_tier }}">
                                {{ ucfirst($result->package_tier) }}
                            </span>
                        @elseif($result->type === 'maid')
                            <flux:icon.user-circle class="w-4 h-4" />
                            <span>{{ $result->name }}</span>
                        @elseif($result->type === 'booking')
                            <flux:icon.calendar class="w-4 h-4" />
                            <span>Booking #{{ $result->id }} - {{ $result->client_name }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    <!-- Selected entities display -->
    @if($selectedClient)
        <div class="selected-entity">
            <strong>Client:</strong> {{ $selectedClient->contact_person }}
            <span class="badge badge-{{ $selectedClient->package_tier }}">
                {{ ucfirst($selectedClient->package_tier) }}
            </span>
        </div>
    @endif
</div>
```

---

## 🏗️ System Architecture

### Database Schema

```sql
-- Tickets Table
CREATE TABLE tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(20) UNIQUE NOT NULL, -- e.g., TKT-2024-001234
    
    -- Categorization
    type ENUM('client_issue', 'maid_support', 'deployment_issue', 'billing', 'training', 'operations', 'general') NOT NULL,
    category VARCHAR(100), -- e.g., "Maid Performance", "Payment Dispute", "Safety Concern"
    subcategory VARCHAR(100),
    
    -- Parties Involved
    requester_id BIGINT UNSIGNED NOT NULL, -- User who created ticket (can be admin on behalf of client)
    requester_type ENUM('client', 'maid', 'trainer', 'admin') NOT NULL,
    created_on_behalf_of BIGINT UNSIGNED NULL, -- If admin/trainer creates ticket for client/maid
    created_on_behalf_type ENUM('client', 'maid') NULL,
    
    -- Related Entities
    client_id BIGINT UNSIGNED NULL,
    maid_id BIGINT UNSIGNED NULL,
    booking_id BIGINT UNSIGNED NULL,
    deployment_id BIGINT UNSIGNED NULL,
    trainer_id BIGINT UNSIGNED NULL,
    program_id BIGINT UNSIGNED NULL,
    package_id BIGINT UNSIGNED NULL, -- Link to package for tier-based priority
    
    -- Ticket Content
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent', 'critical') DEFAULT 'medium',
    auto_priority BOOLEAN DEFAULT FALSE, -- TRUE if priority auto-set by tier/package
    tier_based_priority ENUM('silver', 'gold', 'platinum') NULL, -- Package tier affecting priority
    
    -- Status & Assignment
    status ENUM('open', 'pending', 'in_progress', 'on_hold', 'resolved', 'closed', 'cancelled') DEFAULT 'open',
    assigned_to BIGINT UNSIGNED NULL, -- Admin/Staff handling the ticket
    department ENUM('customer_service', 'operations', 'finance', 'hr', 'training', 'technical') NULL,
    
    -- SLA & Timing
    due_date DATETIME NULL,
    sla_response_due DATETIME NULL, -- Calculated based on tier SLA rules
    sla_resolution_due DATETIME NULL, -- Calculated based on tier SLA rules
    sla_breached BOOLEAN DEFAULT FALSE, -- TRUE if SLA deadlines missed
    first_response_at DATETIME NULL,
    resolved_at DATETIME NULL,
    closed_at DATETIME NULL,
    
    -- Location (if applicable)
    location_address TEXT NULL,
    location_lat DECIMAL(10, 8) NULL,
    location_lng DECIMAL(11, 8) NULL,
    
    -- Resolution
    resolution_notes TEXT NULL,
    satisfaction_rating TINYINT NULL, -- 1-5 stars
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_ticket_number (ticket_number),
    INDEX idx_requester (requester_id, requester_type),
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_related_client (client_id),
    INDEX idx_related_maid (maid_id),
    INDEX idx_related_booking (booking_id),
    INDEX idx_package (package_id),
    INDEX idx_tier_priority (tier_based_priority),
    INDEX idx_sla_breach (sla_breached),
    INDEX idx_created_behalf (created_on_behalf_of),
    
    -- Foreign Keys
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (maid_id) REFERENCES maids(id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    FOREIGN KEY (deployment_id) REFERENCES deployments(id) ON DELETE SET NULL,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
);

-- Ticket Comments / Updates
CREATE TABLE ticket_comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    comment_type ENUM('comment', 'internal_note', 'status_change', 'assignment_change') DEFAULT 'comment',
    body TEXT NOT NULL,
    is_internal BOOLEAN DEFAULT FALSE, -- Only visible to staff
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_ticket (ticket_id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ticket Attachments
CREATE TABLE ticket_attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_type VARCHAR(100),
    file_size INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_ticket (ticket_id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Ticket Status History (Audit Trail)
CREATE TABLE ticket_status_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    changed_by BIGINT UNSIGNED NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_ticket (ticket_id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- SLA Rules Configuration (Tier-Based Priority & Response Times)
CREATE TABLE ticket_sla_rules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Rule Identification
    rule_name VARCHAR(100) NOT NULL, -- e.g., "Platinum Client - Urgent"
    
    -- Matching Criteria
    ticket_type VARCHAR(100), -- e.g., "client_issue", "billing", NULL for all
    package_tier ENUM('silver', 'gold', 'platinum') NULL, -- Package tier
    priority VARCHAR(20), -- e.g., "urgent", "high", NULL for all
    
    -- SLA Targets (in minutes)
    first_response_minutes INT UNSIGNED NOT NULL, -- e.g., 15 mins for Platinum urgent
    resolution_minutes INT UNSIGNED NOT NULL, -- e.g., 120 mins for Platinum urgent
    escalation_minutes INT UNSIGNED NULL, -- Auto-escalate if not resolved
    
    -- Priority Boost Rules
    auto_boost_priority BOOLEAN DEFAULT FALSE, -- Auto-increase priority based on tier
    boosted_priority ENUM('low', 'medium', 'high', 'urgent', 'critical') NULL,
    
    -- Escalation
    escalate_to_user_id BIGINT UNSIGNED NULL, -- Manager to escalate to
    escalate_to_department ENUM('customer_service', 'operations', 'finance', 'hr', 'training', 'management') NULL,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    applies_to_business_hours BOOLEAN DEFAULT TRUE, -- If TRUE, SLA only counts business hours
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_package_tier (package_tier),
    INDEX idx_ticket_type (ticket_type),
    INDEX idx_active (is_active)
);
```

---

## 🎨 User Interface Design

### 1. **Ticket Creation Form**

**Route**: `/tickets/create`

**Fields**:
```php
- Ticket Type (dropdown): Client Issue, Maid Support, Deployment, Billing, Training, Operations
- Category (dynamic based on type):
  * Client Issue: Service Quality, Maid Absence, Schedule Change, Replacement Request
  * Maid Support: Payment Issue, Safety Concern, Leave Request, Contract Query
  * Deployment: Delayed Start, Early Termination, Location Issue
  * Billing: Incorrect Charge, Payment Not Received, Invoice Request
- Priority: Low, Medium, High, Urgent
- Subject: (text input, max 255)
- Description: (textarea, rich text)
- Related Entity: (smart select - auto-populate if coming from specific page)
  * Client (if applicable)
  * Maid (if applicable)
  * Booking (if applicable)
- Location: (optional - address + map picker)
- Attachments: (multi-file upload - photos, PDFs)
```

### 2. **Ticket List View**

**Route**: `/tickets`

**Filters**:
- Status (Open, In Progress, Resolved, Closed, All)
- Priority (All, Urgent, High, Medium, Low)
- Type (All, Client Issue, Maid Support, etc.)
- Assigned To (Me, Unassigned, All)
- Date Range

**Table Columns**:
```
Ticket # | Type | Subject | Priority | Status | Requester | Assigned To | Created | Due Date | Actions
```

**Badges** (branded):
- Priority: Urgent (red), High (orange), Medium (yellow), Low (gray)
- Status: Open (blue), In Progress (purple), Resolved (green), Closed (gray)

### 3. **Ticket Detail View**

**Route**: `/tickets/{ticket}`

**Layout**:

```
┌─────────────────────────────────────────────────────────────┐
│ Header                                                       │
│ TKT-2024-001234 | [Priority Badge] [Status Badge]           │
│ Subject: "Maid not showing up for scheduled service"        │
│ Created by: John Doe (Client) | 2 hours ago                 │
├─────────────────────────────────────────────────────────────┤
│ Left Column (2/3 width)                                      │
│                                                              │
│ ┌─────────────────────────────────────────────┐            │
│ │ Description                                  │            │
│ │ [Full ticket description with formatting]    │            │
│ └─────────────────────────────────────────────┘            │
│                                                              │
│ ┌─────────────────────────────────────────────┐            │
│ │ Attachments (3)                             │            │
│ │ 📷 photo1.jpg  📄 contract.pdf  📷 photo2.jpg│            │
│ └─────────────────────────────────────────────┘            │
│                                                              │
│ ┌─────────────────────────────────────────────┐            │
│ │ Activity / Comments                          │            │
│ │ ┌─────────────────────────────────────────┐ │            │
│ │ │ Admin replied: "We're looking into..."  │ │            │
│ │ │ 1 hour ago                               │ │            │
│ │ └─────────────────────────────────────────┘ │            │
│ │ ┌─────────────────────────────────────────┐ │            │
│ │ │ Status changed: Open → In Progress      │ │            │
│ │ │ 30 mins ago by Staff Name               │ │            │
│ │ └─────────────────────────────────────────┘ │            │
│ └─────────────────────────────────────────────┘            │
│                                                              │
│ [Add Comment textarea + Send button]                        │
│                                                              │
├─────────────────────────────────────────────────────────────┤
│ Right Column (Sidebar - 1/3 width)                          │
│                                                              │
│ ┌─────────────────────────┐                                │
│ │ Details                  │                                │
│ │ Status: [Dropdown]       │                                │
│ │ Priority: [Dropdown]     │                                │
│ │ Assigned to: [Select]    │                                │
│ │ Department: [Select]     │                                │
│ │ Due Date: [Date picker]  │                                │
│ │ [Update Button]          │                                │
│ └─────────────────────────┘                                │
│                                                              │
│ ┌─────────────────────────┐                                │
│ │ Related Entities         │                                │
│ │ Client: John Doe         │                                │
│ │ Maid: Jane Smith         │                                │
│ │ Booking: #12345          │                                │
│ │ [View links]             │                                │
│ └─────────────────────────┘                                │
│                                                              │
│ ┌─────────────────────────┐                                │
│ │ Timeline                 │                                │
│ │ Created: 2h ago          │                                │
│ │ First Response: 1h ago   │                                │
│ │ Last Update: 30m ago     │                                │
│ └─────────────────────────┘                                │
│                                                              │
│ ┌─────────────────────────┐                                │
│ │ Actions                  │                                │
│ │ [Mark as Resolved]       │                                │
│ │ [Close Ticket]           │                                │
│ │ [Escalate]               │                                │
│ └─────────────────────────┘                                │
└─────────────────────────────────────────────────────────────┘
```

### 4. **Dashboard Widget**

Add to Admin Dashboard:

```
┌──────────────────────────────────────┐
│ Support Tickets Overview             │
├──────────────────────────────────────┤
│ Open: 23    In Progress: 15          │
│ Urgent: 3   Pending: 8               │
├──────────────────────────────────────┤
│ Recent Tickets:                      │
│ • TKT-001234: Maid absence (Urgent)  │
│ • TKT-001233: Payment issue (High)   │
│ • TKT-001232: Schedule change (Med)  │
│                                      │
│ [View All Tickets →]                 │
└──────────────────────────────────────┘
```

---

## 🔧 Technical Implementation

### Laravel Models

```php
// app/Models/Ticket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'type',
        'category',
        'subcategory',
        'requester_id',
        'requester_type',
        'created_on_behalf_of',
        'created_on_behalf_type',
        'client_id',
        'maid_id',
        'booking_id',
        'deployment_id',
        'trainer_id',
        'program_id',
        'package_id',
        'subject',
        'description',
        'priority',
        'auto_priority',
        'tier_based_priority',
        'status',
        'assigned_to',
        'department',
        'due_date',
        'sla_response_due',
        'sla_resolution_due',
        'sla_breached',
        'first_response_at',
        'resolved_at',     'closed_at',
        'location_address',
        'location_lat',
        'location_lng',
        'resolution_notes',
        'satisfaction_rating',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'sla_response_due' => 'datetime',
        'sla_resolution_due' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'auto_priority' => 'boolean',
        'sla_breached' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            // Generate unique ticket number
            $ticket->ticket_number = 'TKT-' . date('Y') . '-' . str_pad(
                static::whereYear('created_at', date('Y'))->count() + 1, 
                6, 
                '0', 
                STR_PAD_LEFT
            );
            
            // Auto-apply tier-based priority boost
            $ticket->applyTierBasedPriority();
            
            // Calculate SLA deadlines based on priority and tier
            $ticket->calculateSLADeadlines();
        });
    }
    
    /**
     * Apply automatic priority boost based on client package tier
     */
    public function applyTierBasedPriority(): void
    {
        // Determine the package tier
        $packageTier = null;
        
        if ($this->package_id) {
            $package = \App\Models\Package::find($this->package_id);
            $packageTier = $package?->tier;
        } elseif ($this->client_id) {
            // Get from client's active booking/subscription
            $client = \App\Models\Client::with('activeBooking.package')->find($this->client_id);
            $packageTier = $client?->activeBooking?->package?->tier;
        } elseif ($this->booking_id) {
            $booking = \App\Models\Booking::with('package')->find($this->booking_id);
            $packageTier = $booking?->package?->tier;
        }
        
        if (!$packageTier) {
            return; // No tier found, keep user-selected priority
        }
        
        $this->tier_based_priority = $packageTier;
        
        // Priority boost matrix
        $boostMatrix = [
            'platinum' => [
                'low' => 'medium',
                'medium' => 'high',
                'high' => 'urgent',
                'urgent' => 'critical',
            ],
            'gold' => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
                'urgent' => 'urgent',
            ],
            'silver' => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
                'urgent' => 'high', // Cap urgent at high for silver
            ],
        ];
        
        $currentPriority = $this->priority ?? 'medium';
        $boostedPriority = $boostMatrix[$packageTier][$currentPriority] ?? $currentPriority;
        
        if ($boostedPriority !== $currentPriority) {
            $this->priority = $boostedPriority;
            $this->auto_priority = true;
        }
        
        // Override for critical issues (safety, legal)
        if ($this->isCriticalIssue()) {
            $this->priority = 'critical';
            $this->auto_priority = true;
        }
    }
    
    /**
     * Calculate SLA response and resolution deadlines
     */
    public function calculateSLADeadlines(): void
    {
        // SLA matrix (in minutes)
        $slaMatrix = [
            'platinum' => [
                'critical' => ['response' => 5, 'resolution' => 30],
                'urgent' => ['response' => 10, 'resolution' => 60],
                'high' => ['response' => 15, 'resolution' => 120],
                'medium' => ['response' => 30, 'resolution' => 240],
                'low' => ['response' => 30, 'resolution' => 240],
            ],
            'gold' => [
                'urgent' => ['response' => 15, 'resolution' => 120],
                'high' => ['response' => 30, 'resolution' => 240],
                'medium' => ['response' => 60, 'resolution' => 480],
                'low' => ['response' => 120, 'resolution' => 1440],
            ],
            'silver' => [
                'high' => ['response' => 60, 'resolution' => 720],
                'medium' => ['response' => 120, 'resolution' => 1440],
                'low' => ['response' => 240, 'resolution' => 2880],
            ],
        ];
        
        $tier = $this->tier_based_priority ?? 'silver';
        $priority = $this->priority ?? 'medium';
        
        $sla = $slaMatrix[$tier][$priority] ?? ['response' => 120, 'resolution' => 1440];
        
        $this->sla_response_due = now()->addMinutes($sla['response']);
        $this->sla_resolution_due = now()->addMinutes($sla['resolution']);
    }
    
    /**
     * Check if ticket involves critical safety/legal issues
     */
    protected function isCriticalIssue(): bool
    {
        $criticalKeywords = [
            'safety', 'danger', 'emergency', 'injury', 'abuse', 
            'harassment', 'threat', 'legal', 'police', 'visa', 
            'fraud', 'stolen', 'assault', 'fire', 'medical'
        ];
        
        $searchText = strtolower($this->subject . ' ' . $this->description);
        
        foreach ($criticalKeywords as $keyword) {
            if (str_contains($searchText, $keyword)) {
                return true;
            }
        }
        
        // Also check category
        if (in_array($this->category, ['Safety Concern', 'Legal Issue', 'Emergency', 'Harassment'])) {
            return true;
        }
        
        return false;
    }

    // Relationships
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    
    public function createdOnBehalfOf(): BelongsTo
    {
        // Polymorphic-like relationship based on created_on_behalf_type
        if ($this->created_on_behalf_type === 'client') {
            return $this->belongsTo(Client::class, 'created_on_behalf_of');
        } elseif ($this->created_on_behalf_type === 'maid') {
            return $this->belongsTo(Maid::class, 'created_on_behalf_of');
        }
        
        return $this->belongsTo(User::class, 'created_on_behalf_of');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class);
    }
    
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'pending']);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
    
    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }
    
    public function scopePlatinumTier($query)
    {
        return $query->where('tier_based_priority', 'platinum');
    }
    
    public function scopeSLABreached($query)
    {
        return $query->where('sla_breached', true);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }
    
    public function scopeCreatedOnBehalf($query)
    {
        return $query->whereNotNull('created_on_behalf_of');
    }

    // Helper Methods
    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'pending', 'in_progress']);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->isOpen();
    }
    
    public function isSLABreached(): bool
    {
        if (!$this->isOpen()) {
            return false;
        }
        
        // Check if response SLA breached
        if (!$this->first_response_at && $this->sla_response_due && now()->isAfter($this->sla_response_due)) {
            return true;
        }
        
        // Check if resolution SLA breached
        if (!$this->resolved_at && $this->sla_resolution_due && now()->isAfter($this->sla_resolution_due)) {
            return true;
        }
        
        return false;
    }
    
    public function getTimeUntilSLABreach(): ?string
    {
        if (!$this->isOpen()) {
            return null;
        }
        
        $deadline = !$this->first_response_at ? $this->sla_response_due : $this->sla_resolution_due;
        
        if (!$deadline) {
            return null;
        }
        
        return $deadline->diffForHumans();
    }
    
    public function isPlatinumClient(): bool
    {
        return $this->tier_based_priority === 'platinum';
    }
    
    public function wasCreatedOnBehalf(): bool
    {
        return !is_null($this->created_on_behalf_of);
    }
    
    public function getCreatedByText(): string
    {
        if ($this->wasCreatedOnBehalf()) {
            $onBehalfName = '';
            
            if ($this->created_on_behalf_type === 'client') {
                $onBehalfName = $this->client?->contact_person ?? 'Client';
            } elseif ($this->created_on_behalf_type === 'maid') {
                $onBehalfName = $this->maid?->full_name ?? 'Maid';
            }
            
            return "Created by {$this->requester->name} on behalf of {$onBehalfName}";
        }
        
        return "Created by {$this->requester->name}";
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'open' => 'blue',
            'pending' => 'yellow',
            'in_progress' => 'purple',
            'on_hold' => 'orange',
            'resolved' => 'green',
            'closed' => 'zinc',
            'cancelled' => 'red',
            default => 'zinc'
        };
    }

    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'critical' => 'red',
            'urgent' => 'orange',
            'high' => 'amber',
            'medium' => 'yellow',
            'low' => 'zinc',
            default => 'zinc'
        };
    }
    
    public function getTierBadgeColor(): string
    {
        return match($this->tier_based_priority) {
            'platinum' => 'purple',
            'gold' => 'yellow',
            'silver' => 'zinc',
            default => 'zinc'
        };
    }
}

// app/Models/TicketComment.php
class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment_type',
        'body',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/TicketAttachment.php
class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
```

### Livewire Components

```php
// app/Livewire/Tickets/Index.php
namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'open';
    public $priorityFilter = 'all';
    public $typeFilter = 'all';
    public $assignedFilter = 'all';

    public function render()
    {
        $tickets = Ticket::query()
            ->with(['requester', 'assignedTo', 'client', 'maid'])
            ->when($this->search, fn($q) => $q->where('ticket_number', 'like', "%{$this->search}%")
                ->orWhere('subject', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->priorityFilter !== 'all', fn($q) => $q->where('priority', $this->priorityFilter))
            ->when($this->typeFilter !== 'all', fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->assignedFilter === 'me', fn($q) => $q->where('assigned_to', auth()->id()))
            ->when($this->assignedFilter === 'unassigned', fn($q) => $q->whereNull('assigned_to'))
            ->latest()
            ->paginate(20);

        return view('livewire.tickets.index', [
            'tickets' => $tickets,
        ]);
    }
}

// app/Livewire/Tickets/Create.php
class Create extends Component
{
    public $type = '';
    public $category = '';
    public $priority = 'medium';
    public $subject = '';
    public $description = '';
    public $client_id = null;
    public $maid_id = null;
    public $booking_id = null;
    public $location_address = '';
    public $attachments = [];

    protected $rules = [
        'type' => 'required|string',
        'category' => 'required|string',
        'priority' => 'required|in:low,medium,high,urgent',
        'subject' => 'required|string|max:255',
        'description' => 'required|string',
        'attachments.*' => 'nullable|file|max:10240', // 10MB
    ];

    public function save()
    {
        $this->validate();

        $ticket = Ticket::create([
            'type' => $this->type,
            'category' => $this->category,
            'priority' => $this->priority,
            'subject' => $this->subject,
            'description' => $this->description,
            'requester_id' => auth()->id(),
            'requester_type' => auth()->user()->role,
            'client_id' => $this->client_id,
            'maid_id' => $this->maid_id,
            'booking_id' => $this->booking_id,
            'location_address' => $this->location_address,
            'status' => 'open',
        ]);

        // Handle file uploads
        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                $ticket->attachments()->create([
                    'uploaded_by' => auth()->id(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Send notification to admins
        // TODO: Implement notification system

        session()->flash('message', 'Ticket created successfully!');
        return redirect()->route('tickets.show', $ticket);
    }

    public function render()
    {
        return view('livewire.tickets.create');
    }
}

// app/Livewire/Tickets/Show.php
class Show extends Component
{
    public Ticket $ticket;
    public $newComment = '';
    public $statusUpdate = '';
    public $assignedTo = null;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load([
            'requester', 
            'assignedTo', 
            'client', 
            'maid', 
            'booking',
            'comments.user',
            'attachments',
            'statusHistory.changedBy'
        ]);
        
        $this->statusUpdate = $ticket->status;
        $this->assignedTo = $ticket->assigned_to;
    }

    public function addComment()
    {
        $this->validate(['newComment' => 'required|string']);

        $this->ticket->comments()->create([
            'user_id' => auth()->id(),
            'comment_type' => 'comment',
            'body' => $this->newComment,
            'is_internal' => false,
        ]);

        // Update first_response_at if this is first staff response
        if (!$this->ticket->first_response_at && auth()->user()->role === 'admin') {
            $this->ticket->update(['first_response_at' => now()]);
        }

        $this->newComment = '';
        $this->ticket->refresh();
        
        // Send notification
        // TODO: Notify ticket requester
    }

    public function updateTicket()
    {
        $changes = [];

        if ($this->statusUpdate !== $this->ticket->status) {
            $changes['old_status'] = $this->ticket->status;
            $changes['new_status'] = $this->statusUpdate;
            
            $this->ticket->update(['status' => $this->statusUpdate]);
            
            // Record status change
            $this->ticket->statusHistory()->create([
                'changed_by' => auth()->id(),
                'old_status' => $changes['old_status'],
                'new_status' => $changes['new_status'],
            ]);

            // Update resolved_at or closed_at timestamps
            if ($this->statusUpdate === 'resolved') {
                $this->ticket->update(['resolved_at' => now()]);
            } elseif ($this->statusUpdate === 'closed') {
                $this->ticket->update(['closed_at' => now()]);
            }
        }

        if ($this->assignedTo !== $this->ticket->assigned_to) {
            $this->ticket->update(['assigned_to' => $this->assignedTo]);
        }

        $this->ticket->refresh();
        session()->flash('message', 'Ticket updated successfully!');
    }

    public function render()
    {
        return view('livewire.tickets.show');
    }
}
```

---

## 📱 User Experience Flow

### For Clients:

1. **Access**: From their dashboard, see "Need Help?" or "Submit Issue" button
2. **Quick Actions**: 
   - Report maid absence
   - Request maid replacement
   - Service quality complaint
   - Billing inquiry
3. **Track**: View "My Tickets" with status updates
4. **Notifications**: Email/SMS when staff responds or status changes

### For Maids:

1. **Access**: From their mobile-friendly portal
2. **Quick Actions**:
   - Report safety concern
   - Payment issue
   - Leave request
   - Equipment problem
3. **Simplified UI**: Large touch targets, minimal text, photo upload

### For Admins/Staff:

1. **Dashboard**: See all open/urgent tickets at a glance
2. **Assignment**: Auto-route or manually assign to departments
3. **Workflow**: Comment, update status, escalate, resolve
4. **Reporting**: Analytics on response times, common issues, satisfaction ratings

---

## 🔔 Notifications System

### Triggers:

- **New Ticket Created** → Notify relevant department/admins
- **Ticket Assigned** → Notify assigned staff
- **Status Changed** → Notify requester
- **New Comment** → Notify requester (if from staff) or assigned staff (if from requester)
- **Overdue Ticket** → Notify assigned staff + manager
- **Ticket Resolved** → Ask requester for satisfaction rating

### Channels:

- In-app notifications (badge counter)
- Email notifications
- SMS (for urgent tickets)
- Push notifications (if mobile app exists)

---

## 📊 Reporting & Analytics

### Key Metrics Dashboard:

```
┌─────────────────────────────────────────────────────┐
│ Ticket Volume (Last 30 Days)                        │
│ [Line chart showing daily ticket creation]          │
├─────────────────────────────────────────────────────┤
│ By Status:           By Priority:                   │
│ Open: 45             Urgent: 8                      │
│ In Progress: 23      High: 15                       │
│ Resolved: 156        Medium: 35                     │
│ Closed: 289          Low: 10                        │
├─────────────────────────────────────────────────────┤
│ Average Response Time: 2.3 hours                    │
│ Average Resolution Time: 18.7 hours                 │
│ Customer Satisfaction: 4.2/5 ⭐                      │
├─────────────────────────────────────────────────────┤
│ Top Issues (This Month):                            │
│ 1. Maid Absence (32 tickets)                        │
│ 2. Schedule Changes (28 tickets)                    │
│ 3. Payment Issues (19 tickets)                      │
│ 4. Service Quality (15 tickets)                     │
└─────────────────────────────────────────────────────┘
```

### Reports to Generate:

- **Ticket Volume Report**: By date range, type, department
- **Performance Report**: Response/resolution times by staff member
- **Client Issues Report**: Most common problems, clients with most tickets
- **Maid Support Report**: Support requests by maid, deployment issues
- **SLA Compliance Report**: % of tickets meeting response/resolution targets

---

## 🔒 Security & Permissions

### Role-Based Access:

| Role | Permissions |
|------|------------|
| **Client** | Create tickets related to their bookings/maids; view own tickets; add comments; rate resolution |
| **Maid** | Create support tickets; view own tickets; add comments |
| **Trainer** | Create training-related tickets; view own tickets |
| **Staff/Admin** | View all tickets; assign tickets; change status; add internal notes; access reports |
| **Manager** | All admin permissions + access analytics; configure SLA rules; escalation handling |

### Data Protection:

- Tickets may contain sensitive info (client addresses, maid concerns)
- Implement row-level security (users see only their tickets unless admin)
- Encrypt attachments at rest
- Audit trail for all changes
- GDPR compliance: ability to export/delete ticket data

---

## 🚀 Implementation Phases


### **Phase 1: Core Ticketing (Week 1-2)**
- [x] Database schema migration *(done)*
- [x] Ticket, TicketComment, TicketAttachment models *(done)*
- [x] Basic CRUD: Create ticket *(done)*
- [x] File upload functionality *(done)*
- [x] Admin ticket list with filters *(done)*
- [x] Ticket detail/show page *(done)*
- [x] Comprehensive ticket seeder *(done)*


### **Phase 2: Assignment & Workflow (Week 3)**
- [x] Status management (open → in progress → resolved → closed) *(done in Show component)*
- [x] Assignment to staff *(done in Show component)*
- [ ] Department routing
- [ ] Status history tracking *(change detection exists, needs persistence)*
- [x] Priority management *(done in Show component)*


### **Phase 3: Integration & Context (Week 4)**
- [ ] Link tickets to existing entities (Client, Maid, Booking, Deployment)
- [ ] Pre-populate ticket forms from entity pages (e.g., "Report Issue" button on Booking page)
- [ ] Display related tickets on Client/Maid show pages
- [ ] Quick actions from dashboards


### **Phase 4: Notifications (Week 5)**
- [ ] Email notifications for new tickets, updates, resolutions
- [ ] In-app notification system
- [ ] SMS for urgent tickets (Twilio integration)
- [ ] Notification preferences per user


### **Phase 5: Analytics & Reporting (Week 6)**
- [ ] Admin analytics dashboard
- [ ] KPI metrics (response time, resolution time, satisfaction)
- [ ] Export reports (PDF/Excel)
- [ ] Ticket trends analysis


### **Phase 6: Advanced Features (Week 7-8)**
- [ ] SLA rules configuration & tracking
- [ ] Auto-assignment based on ticket type/department
- [ ] Escalation workflows
- [ ] Satisfaction rating system
- [ ] Knowledge base / FAQ integration
- [ ] Canned responses for common issues

---

## 💡 Quick Wins & Priorities

### Must Have (MVP):
1. ✅ Ticket creation (subject, description, attachments)
2. ✅ Ticket list with filters (status, priority)
3. ✅ Ticket detail view with comments
4. ✅ Link to Client/Maid/Booking
5. ✅ Basic status workflow (open → resolved → closed)
6. ✅ Email notification on ticket creation

### Should Have:
7. Assignment to staff
8. Priority management
9. File attachments
10. Status history
11. Dashboard widget

### Nice to Have:
12. SLA tracking
13. Analytics dashboard
14. Satisfaction ratings
15. Auto-assignment rules
16. SMS notifications

---

## 🎨 Branded UI Components

Use your existing brand tokens:

```css
/* Ticket Priority Badges */
.priority-urgent { 
    background: color-mix(in oklab, var(--color-error) 20%, transparent);
    border-color: var(--color-error);
    color: var(--color-error);
}

.priority-high { 
    background: color-mix(in oklab, orange 20%, transparent);
    color: orange;
}

.priority-medium { 
    background: color-mix(in oklab, var(--brand-gold) 20%, transparent);
    color: var(--brand-gold);
}

/* Ticket Status Badges */
.status-open { 
    background: color-mix(in oklab, var(--brand-royal-purple) 20%, transparent);
    color: var(--brand-royal-purple);
}

.status-in-progress { 
    background: color-mix(in oklab, var(--brand-deep-violet) 20%, transparent);
    color: var(--brand-deep-violet);
}

.status-resolved { 
    background: color-mix(in oklab, green 20%, transparent);
    color: green;
}
```

---

## 📝 Sample Routes

```php
// routes/web.php

Route::middleware(['auth'])->group(function () {
    // Ticket routes
    Route::get('/tickets', \App\Livewire\Tickets\Index::class)->name('tickets.index');
    Route::get('/tickets/create', \App\Livewire\Tickets\Create::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', \App\Livewire\Tickets\Show::class)->name('tickets.show');
    
    // Quick ticket creation from specific contexts
    Route::get('/bookings/{booking}/create-ticket', \App\Livewire\Tickets\CreateFromBooking::class)
        ->name('bookings.create-ticket');
    Route::get('/maids/{maid}/create-ticket', \App\Livewire\Tickets\CreateFromMaid::class)
        ->name('maids.create-ticket');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/tickets/analytics', \App\Livewire\Tickets\Analytics::class)
        ->name('tickets.analytics');
});
```

---

## 🧪 Testing Checklist

- [ ] Client can create ticket
- [ ] Admin can view all tickets
- [ ] Client can only view own tickets
- [ ] File upload works (images, PDFs)
- [ ] Comments are saved and displayed
- [ ] Status changes are logged
- [ ] Notifications are sent
- [ ] Ticket numbers are unique
- [ ] Related entities link correctly
- [ ] Filters work properly
- [ ] Pagination works
- [ ] Search works
- [ ] Permission checks prevent unauthorized access

---

## 📚 Next Steps

1. **Review this plan** with stakeholders
2. **Prioritize features** (MVP vs. nice-to-have)
3. **Create database migration** files
4. **Build models and relationships**
5. **Design UI mockups** (Figma/wireframes)
6. **Implement Phase 1** (core ticketing)
7. **Test with real users**
8. **Iterate based on feedback**

---

## 🤝 Integration Points

### Existing System Links:

- **Client Dashboard**: "Need Help?" button → Create ticket
- **Booking Show Page**: "Report Issue" button → Pre-populate booking context
- **Maid Show Page**: "Report Issue" button → Admin creates ticket for maid
- **Deployment Show Page**: "Report Problem" → Link to deployment
- **Admin Dashboard**: "Support Tickets" widget → Quick stats + recent tickets

---

## ✅ Success Metrics

After implementation, measure:

- **Ticket Volume**: How many tickets per week?
- **Response Time**: How fast do staff respond?
- **Resolution Time**: How long to close tickets?
- **Client Satisfaction**: Average rating on resolved tickets
- **Repeat Issues**: Are same problems reported multiple times?
- **Staff Efficiency**: Tickets handled per staff member
- **SLA Compliance**: % meeting response/resolution targets
- **Tier-Based Performance**: Platinum vs. Gold vs. Silver response/resolution comparison
- **Priority Boost Effectiveness**: How often auto-priority improves response times
- **On-Behalf Creation**: % of tickets created by staff for clients/maids

---

## 📊 Key Enhancements Summary

### 🎯 Tier-Based Priority & SLA System

**What It Does**:
- Automatically boosts ticket priority based on client's package tier (Silver/Gold/Platinum)
- Applies faster SLA response and resolution times for premium clients
- Ensures VIP clients (Platinum) get immediate attention

**How It Works**:
1. Client submits ticket (or staff submits on their behalf)
2. System detects client's package tier from booking/subscription
3. Priority is automatically adjusted:
   - **Platinum**: All priorities boosted one level (Medium → High, High → Urgent, Urgent → Critical)
   - **Gold**: Priorities maintained as selected
   - **Silver**: Urgent capped at High
4. SLA deadlines calculated based on tier + priority:
   - Platinum Critical: 5 min response, 30 min resolution
   - Platinum Urgent: 10 min response, 1 hour resolution
   - Gold Urgent: 15 min response, 2 hour resolution
   - Silver High: 1 hour response, 12 hour resolution

**Visual Indicators**:
```
Ticket Display:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
TKT-2025-001234 | � CRITICAL
Subject: Maid not showing up
Created by: John Doe

[🟣 PLATINUM CLIENT] [⚡ AUTO-BOOSTED]
Originally: High → Boosted to: Critical
SLA: Respond in 5 min | Resolve in 30 min
⏰ 27 minutes remaining
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### 👥 "On Behalf Of" Multi-Actor System

**What It Does**:
- Allows staff/trainers to create tickets for clients or maids who contact them via phone/in-person
- Maintains full audit trail of who created the ticket and for whom
- Notifies the actual affected party (client/maid) that a ticket was created for them

**Scenarios Covered**:

| Scenario | Requester | On Behalf Of | Notifications |
|----------|-----------|--------------|---------------|
| Client self-service | Client | NULL (self) | Admin alerted |
| Phone call from client | Admin | Client | Client emailed ticket# |
| Trainer visits client | Trainer | Client | Client + Admin notified |
| Maid calls support | Admin | Maid | Maid receives SMS/email |
| Admin creates for maid | Admin | Maid | Maid notified |
| Client reports maid issue | Client | NULL (maid linked) | Maid notified of ticket |
| Maid reports client issue | Maid/Admin | NULL (client linked) | Client + management notified |

**Database Tracking**:
```sql
-- Example record:
ticket_id: 1234
requester_id: 56 (Admin Sarah)
requester_type: 'admin'
created_on_behalf_of: 89 (Client John)
created_on_behalf_type: 'client'
client_id: 89
package_id: 3 (Platinum)
priority: 'critical' (auto-boosted from 'high')
auto_priority: TRUE
tier_based_priority: 'platinum'
sla_response_due: '2025-10-25 10:15:00' (5 min)
sla_resolution_due: '2025-10-25 10:40:00' (30 min)
```

**UI Display**:
```
Ticket Header:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
TKT-2025-001234
Created by: Admin Sarah on behalf of Client John Doe
[🟣 Platinum Client] [⚡ Priority Auto-Boosted]

Client notified via email at: john.doe@example.com
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### 🚨 Critical Issue Auto-Detection

**What It Does**:
- Scans ticket subject/description for safety/legal keywords
- Automatically sets priority to CRITICAL regardless of tier
- Triggers immediate escalation to management

**Keywords Monitored**:
- Safety: danger, emergency, injury, abuse, threat, assault, fire
- Legal: harassment, police, visa, fraud, stolen
- Medical: medical, hospital, ambulance

**Categories Auto-Flagged**:
- Safety Concern
- Legal Issue
- Emergency
- Harassment
- Abuse

**Example**:
```
Ticket subject: "Client locked maid in room"
Keyword detected: "locked" (safety)
Action: Priority set to CRITICAL
        SLA: 5 min response (regardless of tier)
        Escalated to: Management immediately
        SMS alert sent to: On-call manager
```

### 📈 Reporting Enhancements

**New Reports Available**:

1. **Tier Performance Report**
   - Average response time by tier (Platinum/Gold/Silver)
   - SLA compliance % by tier
   - Client satisfaction by tier

2. **Priority Boost Analysis**
   - How many tickets were auto-boosted
   - Impact on resolution times
   - Tier-specific boost effectiveness

3. **On-Behalf Creation Report**
   - % of tickets created by staff vs. self-service
   - Most common on-behalf scenarios
   - Client engagement patterns

4. **SLA Breach Dashboard**
   - Real-time view of tickets approaching SLA deadlines
   - Historical breach rates by tier
   - Staff performance vs. SLA targets

---

**Status**: 📋 Enhanced Planning Document  
**Created**: 2025-10-25  
**Enhanced**: 2025-10-25 (Tier-based priority + On-behalf system)  
**Next Review**: After stakeholder approval  
**Owner**: Development Team 