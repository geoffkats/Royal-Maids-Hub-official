# Royal Maids Hub v3.1.26+ — Comprehensive Feature & Bug Fix Plan

**Date:** February 3, 2026  
**Status:** Planning Phase  
**Scope:** 14 items (13 features + 1 bug)

---

## Executive Summary

This plan outlines a phased approach to implementing significant system enhancements across:
- **Client Management** (identity fields, evaluations, family members, tickets)
- **Maid/Trainer Management** (contract tracking, evaluations, deployment data, tickets)
- **Contracts & Compliance** (contract management tab, email contracts, training programs)
- **CRM Integration** (opportunities linking, evaluations)
- **Operations** (deployment data collection, training programs)
- **Bug Fixes** (convert button, client dropdown)

**Recommended Timeline:** 3 releases (v3.1.26, v3.2.0, v3.2.1)

---

## Part A: Database Architecture

### Phase 1 Migrations (v3.1.26)

#### 1. Clients Table Enhancements (Identity Fields)
```sql
ALTER TABLE clients ADD COLUMN identity_type ENUM('nin', 'passport') NULLABLE;
ALTER TABLE clients ADD COLUMN identity_number VARCHAR(50) NULLABLE UNIQUE;
CREATE INDEX idx_clients_identity_type ON clients(identity_type);
CREATE INDEX idx_clients_identity_number ON clients(identity_number);
```

**Rationale:** One identity model prevents duplicate logic and allows easy expansion for other national IDs.

**Refinement (preferred if migrations are not finalized yet):**
```sql
ALTER TABLE clients DROP INDEX idx_clients_identity_number;
ALTER TABLE clients DROP INDEX identity_number;
ALTER TABLE clients ADD UNIQUE KEY uniq_client_identity (identity_type, identity_number);
```

**Note:** If migrations are already shipped, keep the current approach for v3.1.26 and flag this as a v3.3 cleanup.

#### 2. New Table: `client_family_members`
```sql
CREATE TABLE client_family_members (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    client_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    relationship VARCHAR(100), -- parent, child, spouse, etc.
    age INT,
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);
```

**Rationale:** Clients may have multiple family members needing services. This allows tracking all household members relevant to bookings.

#### 3. New Table: `bookings_documents`
```sql
CREATE TABLE bookings_documents (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT UNSIGNED NOT NULL,
    document_type VARCHAR(100), -- 'contract', 'id_scan', 'passport_scan', 'nin_proof'
    file_path VARCHAR(255),
    uploaded_by BIGINT UNSIGNED, -- user_id
    uploaded_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);
```

**Rationale:** Store booking-related documents (contracts, IDs) for compliance and easy retrieval.

#### 4. New Table: `maid_contracts`
```sql
CREATE TABLE maid_contracts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    maid_id BIGINT UNSIGNED NOT NULL,
    contract_start_date DATE NOT NULL,
    contract_end_date DATE,
   contract_status ENUM('pending', 'active', 'completed', 'terminated') DEFAULT 'pending',
    contract_type VARCHAR(100), -- 'full-time', 'part-time', 'seasonal'
    notes TEXT,
   worked_days INT DEFAULT 0, -- cached only
   pending_days INT DEFAULT 0, -- cached only
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (maid_id) REFERENCES maids(id) ON DELETE CASCADE
);
```

**Rationale:** Store dates as the source of truth. Cache `worked_days` and `pending_days` only for reporting.

#### 5. New Table: `client_evaluations`
```sql
CREATE TABLE client_evaluations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    client_id BIGINT UNSIGNED NOT NULL,
    booking_id BIGINT UNSIGNED,
    trainer_id BIGINT UNSIGNED, -- trainer who conducted evaluation
    evaluation_date DATE NOT NULL,
    evaluation_type ENUM('3_months', '6_months', '12_months', 'custom') DEFAULT 'custom',
    overall_rating INT, -- 1-5 scale
    comments TEXT,
    areas_of_improvement JSON, -- array of improvement areas
    strengths JSON, -- array of strengths
    next_evaluation_date DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE SET NULL
);
```

**Rationale:** Store periodic evaluations for clients (how satisfied with service). Separate from worker evaluations.

#### 6. New Table: `worker_evaluations`
```sql
CREATE TABLE worker_evaluations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    maid_id BIGINT UNSIGNED NOT NULL,
    trainer_id BIGINT UNSIGNED NOT NULL,
    evaluation_date DATE NOT NULL,
    evaluation_type ENUM('3_months', '6_months', '12_months', 'custom') DEFAULT 'custom',
    training_program_id BIGINT UNSIGNED, -- which program/curriculum
    overall_score INT, -- 1-5 scale
    performance_data JSON, -- individual question scores
    reviewed_by BIGINT UNSIGNED, -- admin/trainer who reviewed
    next_evaluation_date DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (maid_id) REFERENCES maids(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE,
    FOREIGN KEY (training_program_id) REFERENCES training_programs(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);
```

**Rationale:** Track maid performance evaluations at critical intervals. JSON allows flexible question/answer storage.

#### 7. New Table: `training_programs`
```sql
CREATE TABLE training_programs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL, -- 'Meal Preparation', 'Laundry', etc.
    description TEXT,
    duration_weeks INT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_program_name (name)
);
```

**Insert data:**
```sql
INSERT INTO training_programs (name, description, duration_weeks) VALUES
('Meal Preparation', 'Training on meal planning, cooking, dietary restrictions', 4),
('Laundry', 'Training on laundry techniques, stain removal, fabric care', 2),
('Cleaning', 'Training on cleaning techniques, hygiene standards, safety', 4),
('Housekeeping', 'Comprehensive housekeeping management and standards', 6);
```

#### 8. New Table: `training_program_questions`
```sql
CREATE TABLE training_program_questions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    training_program_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('rating_1_5', 'yes_no', 'text', 'multiple_choice') DEFAULT 'rating_1_5',
    options JSON, -- for multiple choice: ['Option A', 'Option B']
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (training_program_id) REFERENCES training_programs(id) ON DELETE CASCADE
);
```

**Rationale:** Store evaluation questions per program. Type allows flexible question formats.

#### 9. Deployments Table Enhancement
```sql
ALTER TABLE deployments ADD COLUMN maid_salary DECIMAL(12,2) NULLABLE;
ALTER TABLE deployments ADD COLUMN client_payment DECIMAL(12,2) NULLABLE;
ALTER TABLE deployments ADD COLUMN service_paid DECIMAL(12,2) NULLABLE;
ALTER TABLE deployments ADD COLUMN salary_paid_date DATE NULLABLE;
ALTER TABLE deployments ADD COLUMN payment_status ENUM('pending', 'partial', 'paid') DEFAULT 'pending';
ALTER TABLE deployments ADD COLUMN currency CHAR(3) DEFAULT 'UGX';
```

**Rationale:** Track financial data at deployment level. Dashboard can then SUM these fields.

#### 10. Opportunities Table Enhancement
```sql
ALTER TABLE opportunities ADD COLUMN linked_client_id BIGINT UNSIGNED NULLABLE AFTER status;
ALTER TABLE opportunities ADD FOREIGN KEY (linked_client_id) REFERENCES clients(id) ON DELETE SET NULL;
CREATE INDEX idx_opportunities_linked_client ON opportunities(linked_client_id);
```

**Rationale:** Link already-converted opportunities to their client accounts for CRM continuity.

#### 11. Bookings Table Enhancement (Identity Fields)
```sql
ALTER TABLE bookings ADD COLUMN identity_type ENUM('nin', 'passport') NULLABLE;
ALTER TABLE bookings ADD COLUMN identity_number VARCHAR(50) NULLABLE;
CREATE INDEX idx_bookings_identity_type ON bookings(identity_type);
CREATE INDEX idx_bookings_identity_number ON bookings(identity_number);
ALTER TABLE bookings ADD COLUMN client_contact_email_sent BOOLEAN DEFAULT false;
ALTER TABLE bookings ADD COLUMN contract_email_sent_at TIMESTAMP NULLABLE;
```

**Rationale:** Store identity at booking level (may differ from client record for verification). Track email status.

**Refinement (preferred if migrations are not finalized yet):**
```sql
ALTER TABLE bookings DROP INDEX idx_bookings_identity_number;
ALTER TABLE bookings DROP INDEX identity_number;
ALTER TABLE bookings ADD UNIQUE KEY uniq_booking_identity (identity_type, identity_number);
```

**Note:** If migrations are already shipped, keep the current approach for v3.1.26 and flag this as a v3.3 cleanup.

---

## Part B: Feature Implementation Strategy

### Phase 1: v3.1.26 (Quick Wins + Foundation)

#### Item #1: Add Identity Fields to Clients
**Effort:** Low | **Time:** 2-3 hours

**Steps:**
1. Create migration for `clients.identity_type`, `clients.identity_number`
2. Update `Client` model with casts & validation rules
3. Add form fields to `Clients\Create` and `Clients\Edit` components
4. Update `Clients\Index` to display identity data (optional, can be hidden by default)
5. Add validation: `identity_type` required when `identity_number` is present, and `identity_number` unique
6. Test: Unit test for unique identity constraint

**Form UI:**
- Select: "Identity Type" (NIN, Passport)
- Text input: "Identity Number"
- Pattern validation: based on `identity_type`

**Dashboard:** Show identity in client details view (collapsible section or modal)

---

#### Item #2: Add Identity Fields to Booking Form
**Effort:** Low | **Time:** 2 hours

**Steps:**
1. Create migration for `bookings.identity_type`, `bookings.identity_number`, `bookings.client_contact_email_sent`
2. Update `Booking` model
3. Add fields to `Bookings\Create` and `Bookings\Edit` components
4. Pre-populate from linked client's identity fields (if available)
5. Make manually editable (client may provide different ID for booking)
6. Test: Ensure identity data is captured on booking form

**Form Logic:**
- Pre-fill from `Client.identity_type` and `Client.identity_number`
- Override fields: Allow different identity for a booking if needed
- Validation: Optional but encourage completion

---

#### Item #14: Fix "Convert Opportunity to Client" Button + Dropdown
**Effort:** Low | **Time:** 1-2 hours

**Steps:**
1. Locate the "convert" button issue in opportunities view/component
2. Add dropdown of existing clients (searchable, with pagination)
3. Allow two workflows:
   - **Convert to New Client:** Create new client record
   - **Link to Existing Client:** Select from dropdown
4. On conversion, set `Opportunity.linked_client_id`
5. Update `Opportunity` model validation
6. Test: Both workflows work, dropdown is responsive

**UI:**
- Modal dialog with two radio buttons:
  - "Create New Client" (show form)
  - "Link to Existing Client" (show searchable dropdown)
- Dropdown: Search by name, email, phone
- Button: "Convert Opportunity"

---

#### Item #7: Track Contract Period (Worked/Pending) on Maid Profile
**Effort:** Medium | **Time:** 3-4 hours

**Steps:**
1. Create migration for `maid_contracts` table (see Part A)
2. Create `MaidContract` Eloquent model
3. Add relationship on `Maid` model
4. Create seeder to initialize contracts for existing maids (optional, or allow manual entry)
5. Add query methods to calculate `worked_days` from deployments
6. Create admin UI: `Maids\ContractManagement` component (show contract timeline, worked/pending days)
7. Update maid profile view to display contract info
8. Add chart/visualization: Contract timeline with worked days highlighted

**Calculation Logic:**
```
worked_days = SUM(deployments.days)
pending_days = date_diff(contract_end_date, today) if contract_end_date exists
```

**Safe Compromise:** Keep `worked_days` and `pending_days` as cached fields, but always recalculate on read or via a nightly cron. Never allow manual edits.

**UI:**
- Maid profile sidebar: "Contract Status" card
  - Status badge: `active`, `pending`, `completed`, `terminated`
  - Days worked: X days
  - Days remaining: Y days
  - Start/End dates
- Timeline: Visual representation of contract period

---

### Phase 2: v3.2.0 (Core Features + Evaluations)

#### Item #3: Create Client Evaluation Form
**Effort:** Medium | **Time:** 4-5 hours

**Steps:**
1. Create migration for `client_evaluations` table
2. Create `ClientEvaluation` model with relationships to `Client`, `Booking`, `Trainer`
3. Create Livewire component: `ClientEvaluations\Create` (admin-only, trainer-fillable)
4. Add form fields:
   - Client (dropdown, pre-filled if accessed from client profile)
   - Booking (linked booking, optional)
   - Evaluation date (date picker, default today)
   - Evaluation type (3mo/6mo/12mo/custom)
   - Overall rating (1-5 star selector)
   - Strengths (multi-line text or tag input)
   - Areas for improvement (multi-line text or tag input)
   - Comments (textarea)
   - Next evaluation date (date picker, auto-calculated if type is 3/6/12 mo)
5. Create view: `ClientEvaluations\Show` + `ClientEvaluations\Index`
6. Add to client profile: Tab/card showing evaluation history
7. Test: Create, view, list evaluations

**UI:**
- Modal or full-page form
- Star rating component for overall_rating
- Tag input for strengths/improvements
- Date picker for evaluation_date with auto-suggestion for next_evaluation_date

**Automation:**
- If evaluation_type = '3_months': next_evaluation_date = today + 3 months
- Trigger email: "New evaluation due" 7 days before next_evaluation_date

---

#### Item #4: Add Worker Evaluation Tasks (3mo, 6mo, 12mo)
**Effort:** High | **Time:** 6-8 hours

**Steps:**
1. Create migration for `worker_evaluations` and `training_program_questions` tables
2. Create models: `WorkerEvaluation`, `TrainingProgram`, `TrainingProgramQuestion`
3. Add relationships on `Maid`, `Trainer` models
4. Create admin UI: `WorkerEvaluations\Create` component
5. Build dynamic form generator:
   - Load training program
   - Display all questions based on program
   - Render form fields based on question_type (rating, yes/no, text, multiple choice)
   - Store responses in JSON `performance_data`
6. Create scheduler job: `EvaluationReminderJob` to send notifications for due evaluations
7. Create dashboard: `Trainers\EvaluationDashboard` showing:
   - Due evaluations
   - Completed evaluations
   - Pending reviews
8. Add to maid profile: Evaluation history with scores/ratings

**Database Seed (Training Program Questions):**
```
// Meal Preparation Questions
'Demonstrates knowledge of nutrition and dietary requirements'
'Ability to plan and prepare diverse meals'
'Food safety and hygiene standards'
'Time management in meal prep'
'Communication with clients about preferences'

// Laundry Questions
'Proper sorting and washing techniques'
'Stain identification and treatment'
'Fabric care and ironing skills'
'Understanding special care labels'
'Efficiency and speed'

// Cleaning Questions
'Understanding of cleaning standards'
'Use of appropriate cleaning materials'
'Safety protocols during cleaning'
'Attention to detail'
'Time management'

// Housekeeping Questions
'Overall organization skills'
'Initiative and responsibility'
'Problem-solving ability'
'Communication and reporting'
'Adherence to schedules'
```

**Automation:**
- Cron job: Check for maids whose evaluations are due (3mo after hire, 6mo, 12mo)
- Email trainers: "Evaluation due for Maid X"
- Auto-create evaluation task in trainer dashboard

---

#### Item #13: Training Programs + Evaluation Questions
**Effort:** Medium | **Time:** 4-5 hours

**Steps:**
1. Migrations already created in Part A
2. Create models: `TrainingProgram`, `TrainingProgramQuestion`
3. Seed database with 4 programs (Meal Prep, Laundry, Cleaning, Housekeeping)
4. Create admin UI: `TrainingPrograms\Index`, `TrainingPrograms\Edit`
5. Build question management interface:
   - Add/edit/delete questions per program
   - Drag-to-reorder questions
   - Choose question type (dropdown)
   - For multiple choice: multi-line input for options
6. Link training programs to worker evaluations (dropdown in evaluation form)
7. Make training programs reusable: Can select any program when creating evaluation

**Admin UI:**
- List of training programs (Meal Prep, Laundry, Cleaning, Housekeeping)
- Click to edit: Show all questions for that program
- Add question button: Modal form
  - Question text
  - Question type dropdown
  - Options field (if multiple_choice)
  - Sort order
- Drag-to-reorder rows
- Delete question button

---

### Phase 3: v3.2.1 (Advanced Features + Integration)

#### Item #5: Royal Maids Hub Training Program Management
**Effort:** Medium | **Time:** 3-4 hours

**Steps:**
1. Extend `TrainingProgram` model with metadata:
   - `created_by` (admin who created it)
   - `duration_weeks`
   - `curriculum_file_path` (optional PDF/doc)
   - `is_mandatory` boolean
2. Create training assignment UI: Link maids to training programs
3. New table: `maid_training_assignments`
   ```sql
   CREATE TABLE maid_training_assignments (
       id BIGINT, maid_id BIGINT, training_program_id BIGINT,
       assigned_date DATE, completion_date DATE,
       status ENUM('in_progress', 'completed', 'failed'),
       created_at, updated_at
   );
   ```
4. Create trainer dashboard: View assigned maids per program
5. Mark completion: Trainer marks maid as completed training
6. Dashboard: Show training completion stats

**UI:**
- Trainers list with "Manage Training" button
- Modal: Assign training programs
- Assign form: Select maid(s), select program(s), click "Assign"
- Dashboard: Training progress (pie charts by program)

---

#### Item #6: Tickets on Client & Maid Profiles
**Effort:** Low | **Time:** 2-3 hours

**Steps:**
1. Create migration: `ALTER TABLE tickets ADD COLUMN client_id BIGINT UNSIGNED NULLABLE;` (link to client, not just booking)
2. Update `Ticket` model with `belongsTo(Client)` relationship
3. On ticket creation: Also capture client_id from booking
4. Update client profile: Add "Tickets" tab
   - Show all tickets related to this client (via booking.client_id)
   - Filter by status, date
5. Update maid profile: Add "Tickets" tab
   - Show all tickets about deployments for this maid
   - Left join: deployments -> bookings -> tickets
6. Test: View tickets from both profiles

**UI:**
- Client profile: Tab "Tickets & Issues"
  - Card list of related tickets
  - Status badges (open, in_progress, resolved)
  - Link to full ticket details
- Maid profile: Tab "Work Tickets"
  - Similar layout
  - Shows issues reported about maid's work

---

#### Item #8: Email Contracts to Clients
**Effort:** Medium | **Time:** 4-5 hours

**Steps:**
1. Create mailable: `ContractMailable` 
2. Create artisan command: `SendContractEmail`
3. Add to `Bookings\Show` component: "Send Contract Email" button
4. Booking workflow:
   - Admin creates contract (stored in `bookings_documents` table)
   - Admin clicks "Send Contract Email"
   - System generates contract PDF (if not already done)
   - Sends email with PDF attachment
   - Sets `bookings.contract_email_sent_at`
5. Track email status: "Contract sent on [date]" in booking view
6. Optional: Auto-send contract when booking is confirmed
7. Resend button: If email bounces, allow resend

**Implementation:**
- Use `barryvdh/laravel-dompdf` (already installed) to generate PDF
- Store PDF in `storage/app/contracts/booking_{id}.pdf`
- Queue email job: `SendContractEmailJob`
- Email template: Professional, includes client details, services, dates, signature lines

**Email Template:**
```
Dear [Client Name],

Your contract for our housekeeping services is attached. Please review and sign both copies:
- Keep one for your records
- Return one signed copy to us

Service Details:
- Service Period: [Date] to [Date]
- Assigned Maid: [Maid Name]
- Service: [Service Type]
- Cost: [Amount]

Please contact us with any questions.

Best regards,
Royal Maids Hub
```

---

#### Item #9: Button to Add More Family Members
**Effort:** Low | **Time:** 2-3 hours

**Steps:**
1. Create migration for `client_family_members` table (Part A)
2. Create `ClientFamilyMember` model
3. Add relationship on `Client` model
4. Update `Clients\Show` component:
   - Add "Family Members" tab
   - List existing family members
   - Add "Add Family Member" button
5. Create modal: `Clients\FamilyMemberForm`
   - Name (required)
   - Relationship (dropdown: parent, child, spouse, sibling, other)
   - Age (optional number)
   - Notes (optional textarea)
   - Save/Cancel buttons
6. Add validation: Name required, max 255 chars
7. Test: Add/edit/delete family members

**UI:**
- Client profile: Tab "Household Members"
- Cards for each family member
- Edit icon per member
- "Add Family Member" button (bottom of list)
- Delete confirmation modal

---

#### Item #10: Linking Opportunities to Existing Clients
**Effort:** Low | **Time:** 2-3 hours

**Steps:**
1. Migration: Add `linked_client_id` to `opportunities` table (Part A done)
2. Update `Opportunity` model: Add `belongsTo(Client)` relationship
3. Update opportunities convert workflow:
   - Option 1: Create new client
   - Option 2: Link to existing client (dropdown)
4. Create searchable client dropdown (reusable component)
5. On convert: Set `linked_client_id` and optionally create booking
6. Update opportunities index: Show linked client (if any)
7. Test: Both conversion workflows

**UI Change:**
- "Convert to Client" button
- Modal with two options:
  - Radio: "Create New Client Entry" (show form)
  - Radio: "Link to Existing Client" (show dropdown)
- Dropdown: Autocomplete client search by name/email/phone
- Action button: "Convert"

---

#### Item #11: Contracts Management Tab
**Effort:** High | **Time:** 6-8 hours

**Steps:**
1. Create new admin section: `admin/contracts`
2. Add to sidebar: "Contracts Management" (with contracts icon)
3. Create interfaces:
   - **Contracts List**: Show all maid & deployment contracts
     - Filter by type (maid, deployment, client)
     - Filter by status (pending, active, completed, terminated)
     - Search by name/maid/client
     - Bulk download contracts
   - **Generate Contract**: Button to auto-generate contract PDF
     - Uses template based on contract type
     - Pre-fill with booking/maid/client details
     - Allow editing before save
   - **Track Status**: Column showing contract status
     - Pending signature
     - Signed
     - Executed
     - Expired
   - **Email Tracker**: Column showing email sent date/status
4. Create models & migrations for contract status tracking
5. Add to maid profile: Contract history link
6. Add to booking: Associated contracts

**Database:**
```sql
ALTER TABLE bookings_documents ADD COLUMN status ENUM('unsigned', 'signed', 'executed');
ALTER TABLE bookings_documents ADD COLUMN signed_date TIMESTAMP NULLABLE;
ALTER TABLE bookings_documents ADD COLUMN signed_by BIGINT UNSIGNED NULLABLE;
```

**Admin UI:**
- Left sidebar: "Contracts" link under Admin
- Main view: Table of contracts
  - Type, Party, Status, Date, Actions (view, download, email, update status)
  - Filters: Type, Status, Date range
  - Search bar
- Detail view: Show contract, status timeline, email history

---

#### Item #12: Deployment Popup - Collect Salary/Payment/Service Fees
**Effort:** Medium | **Time:** 4-5 hours

**Steps:**
1. Migration: Add `maid_salary`, `client_payment`, `service_paid`, `salary_paid_date`, `payment_status` to `deployments` table (Part A)
2. Update `Deployment` model
3. Update `Deployments\Show` or create new `Deployments\FinancialDetails` component
4. Add financial fields to deployment form/popup:
   - Maid Salary (decimal)
   - Client Payment (decimal)
   - Service Paid (decimal)
   - Payment Status (dropdown: pending, partial, paid)
   - Salary Paid Date (date picker)
5. Create calculation defaults:
   - If maid has rate: maid_salary = days * daily_rate
   - If booking has rate: client_payment = days * booking_rate
   - service_paid = client_payment (or admin can override)
6. Dashboard enhancements:
   - Add financial widgets:
     - Total Salary Owed
     - Total Fee Collected
     - Outstanding Payments
   - Use SUM aggregates from deployments table
7. Test: Financial data is captured and displayed

**Future Upgrade:** Move these financial fields to a dedicated `transactions` table in Phase 3+.

**UI:**
- New section in deployment details: "Financial Details"
  - Maid Salary field (pre-calculated, editable)
  - Client Payment field (pre-calculated, editable)
  - Service Paid field (editable, may differ from client payment)
  - Payment Status dropdown
  - Salary Paid Date picker
- Save button
- Dashboard: 3 big number cards at top
  - "Total Salary Owed"
  - "Total Payment Collected"
  - "Service Fee Earned"

---

## Part C: Best Practices & Implementation Guidelines

### Architecture Principles

1. **Separation of Concerns:**
   - Models: Business logic & relationships only
   - Services: Complex domain logic (e.g., contract calculation)
   - Livewire Components: UI state management
   - Migrations: Database structure

2. **Validation:**
   - Always use Form Request classes for complex validation
   - Server-side AND client-side validation
   - Custom validation rules in `app/Rules/`

3. **Authorization:**
   - Use policies for all model operations
   - Middleware for role-based access
   - Check both route + component level

4. **Testing:**
   - Unit tests for models & services
   - Feature tests for workflows
   - Pest framework (already in use)

### Database Design Best Practices

1. **Foreign Keys:**
   - Always include `ON DELETE CASCADE` for child tables
   - Use `ON DELETE SET NULL` for optional relationships

2. **JSON Fields:**
   - Use for flexible data (evaluation answers, options)
   - Don't over-normalize; keep related data together
   - Validate structure in model casts

3. **Timestamps:**
   - Always include `created_at`, `updated_at`
   - Add custom timestamps where needed (e.g., `signed_date`)

4. **Indexes:**
- Index frequently searched columns (identity_number)
   - Index foreign keys
   - Index date columns used in filters

### UI/UX Guidelines

1. **Forms:**
   - Use Livewire for dynamic forms
   - Pre-fill from related models
   - Show validation errors inline
   - Debounce expensive queries (search dropdowns)

2. **Lists/Tables:**
   - Pagination for large datasets
   - Sorting and filtering
   - Bulk actions where appropriate
   - Responsive design

3. **Modals:**
   - Confirm destructive actions (delete)
   - Use for quick data entry
   - Auto-focus first field

### Testing Strategy

**Unit Tests:**
- Model relationships
- Validation rules
- Service calculations (worked_days, salary)

**Feature Tests:**
- Create/read/update/delete workflows
- Authorization checks
- Email sending
- PDF generation

**Manual Testing:**
- Cross-browser testing
- Mobile responsiveness
- Email delivery (use Mailtrap in dev)

---

## Part D: Recommended Phasing & Priority

### v3.1.26 (10-14 days max)
**Quick Wins + Bug Fixes**
1. ✅ Item #1: Add identity fields to clients
2. ✅ Item #2: Add identity fields to booking form
3. ✅ Item #14: Fix convert button + client dropdown
4. ✅ Item #7: Contract tracking (worked/pending days)
5. ✅ Item #12: Deployment financial fields (no dashboard totals yet)

**Nice-to-have (only if time allows)**
- Read-only contract display on maid profile
- Email sent tracking flag (no resend yet)

**Deliverable:** Core compliance data + quick bug fixes

### v3.2.0 (2-3 weeks)
**Worker Evaluations + Core Features**
1. ✅ Item #4: Worker evaluation tasks (3mo/6mo/12mo)
2. ✅ Item #5: Training program management
3. ✅ Item #13: Training programs with questions
4. ✅ Item #3: Client evaluation form
5. ✅ Item #6: Tickets on profiles
6. ✅ Item #9: Add family members button
7. ✅ Item #8: Email contracts

**Deliverable:** Complete evaluation system + family member support

### v3.2.1 (2-3 weeks)
**Advanced Features + Integration**
1. ✅ Item #10: Link opportunities to clients
2. ✅ Item #11: Contracts management tab
3. ✅ Item #12: Deployment financial data + dashboard

**Deliverable:** Financial tracking + contract management

---

## Part E: Implementation Checklist Template

For each feature, follow this template:

```
## Item X: [Feature Name]

### Migrations
- [ ] Create migration file(s)
- [ ] Review schema
- [ ] Test rollback

### Models
- [ ] Create/update models
- [ ] Add relationships
- [ ] Add validation rules
- [ ] Update casts

### Services
- [ ] Create service class if needed
- [ ] Business logic implementation
- [ ] Add service tests

### Livewire Components
- [ ] Create component(s)
- [ ] Add authorization checks
- [ ] Implement form/list logic
- [ ] Add validation

### Views/Blade
- [ ] Create/update views
- [ ] Responsive design
- [ ] Dark mode support

### Tests
- [ ] Unit tests (models, services)
- [ ] Feature tests (workflows)
- [ ] Authorization tests
- [ ] All tests passing

### Formatting & Cleanup
- [ ] Run Pint linter
- [ ] Code review
- [ ] Update documentation

### Deployment
- [ ] Tag version
- [ ] Update changelog
- [ ] Push to GitHub
- [ ] Create release notes
```

---

## Part F: Final Decisions (Locked)

1. **Timeline:** v3.1.26 in 10-14 days max. If it goes past 2 weeks, cut scope, not quality.

2. **Training Questions:** Use provided questions as v1. Store in DB and version (version = 1). Never hardcode.

3. **Contract Templates:** Hybrid approach.
   - Phase 1: Upload PDF templates and fill dynamic fields.
   - Phase 2: Fully auto-generated contracts.

4. **Email Service:** Use SMTP first. Wrap in a `ContractMailerInterface` for a clean switch to SendGrid later.

5. **Financial Data (Item #12):** Both. Default auto-calc from rates, but always editable before save. Log who edited and why.

6. **Evaluation Automation:** Hybrid. Cron creates tasks, humans complete evaluations. Never auto-submit.

## Part G: Execution Order for v3.1.26 (Approved)

**Day 1-2**
- DB migrations
- Identity fields
- Convert button fix

**Day 3-4**
- Contract table
- Contract date logic
- Worked/pending calculations (derived)

**Day 5-6**
- Deployment financial fields
- Payment status logic

**Day 7**
- Admin dashboard totals
- QA + bug fixes

**Rule:** Do not start evaluations, training programs, or ticket aggregation in Phase 1.

## Part H: Silent Killers (Must Address Now)

1. **Audit Fields**
   - Add `created_by` and `updated_by` where money, contracts, and identity data exist.

2. **Soft Deletes**
   - At minimum for: clients, maids, contracts, deployments.

3. **Access Control**
   - Trainers cannot see financials.
   - Admin only can edit contracts.
   - Staff cannot edit identity data once verified.

---

## Summary

**Total Scope:** 14 items  
**Estimated Total Time:** 7-10 weeks  
**Recommended Phases:** 3 releases (v3.1.26, v3.2.0, v3.2.1)  
**Key Dependencies:** All items after v3.1.26 depend on database foundation

**Next Step:** Begin v3.1.26 implementation following Part G, and include Part H safeguards.
