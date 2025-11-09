# Lead-Client Management System - Complete User Manual

**Version**: 5.0 | **Last Updated**: November 9, 2025 | **For**: RoyalMaids Hub CRM

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [How It Works](#2-how-it-works)
3. [Lead Conversion Paths](#3-lead-conversion-paths)
4. [Opportunity to Client Flow](#4-opportunity-to-client-flow)
5. [Lead Status Automation](#5-lead-status-automation)
6. [Duplicate Prevention](#6-duplicate-prevention)
7. [Step-by-Step Workflows](#7-step-by-step-workflows)
8. [User Interface Guide](#8-user-interface-guide)
9. [Convert to Client Modal](#9-convert-to-client-modal)
10. [Data Management](#10-data-management)
11. [Troubleshooting](#11-troubleshooting)

---

## 1. System Overview

### Core Principle
**All customer interactions start as Leads, then convert to Clients when ready.**

### The Problem We Solved

**Before**:
- Marketing creates Leads in CRM
- Booking forms create Clients directly
- Result: Duplicates, no connection between marketing and bookings

**After**:
- All bookings create Leads first
- Automatic duplicate detection
- Leads convert to Clients when ready
- Result: Single customer journey, no duplicates

### Benefits
✅ No duplicate records  
✅ Complete customer journey tracking  
✅ Marketing and sales alignment  
✅ Data preservation throughout process  

---

## 2. How It Works

### Complete Customer Journey

```
BOOKING SUBMITTED
    ↓
DUPLICATE CHECK (email/phone)
    ├─ Match found → Use existing lead
    └─ No match → Create new lead
    ↓
LEAD CREATED (status: "new")
    ├─ Lead record created
    ├─ Booking linked to lead
    └─ All form data preserved
    ↓
MARKETING QUALIFIES LEAD
    ├─ Review information
    ├─ View bookings
    └─ Update to "qualified"
    ↓
CONVERSION DECISION
    ├─ Convert to Opportunity (sales tracking)
    └─ Convert to Client (service delivery)
    ↓
CLIENT CREATED
    ├─ Client record created
    ├─ Bookings transferred
    ├─ Lead marked "converted"
    └─ Ready for service
```

### Data Flow

```
Booking Form (40+ fields)
    ↓
BookingToLeadService
    ├─ Duplicate detection
    ├─ Email/phone matching
    └─ Lead creation/linking
    ↓
Lead + Booking Created
    ↓
(Conversion)
    ↓
ConvertLeadToClientService
    ├─ Client creation/linking
    ├─ Booking transfer
    └─ Data preservation
    ↓
Client + Bookings Ready
```

---

## 3. Lead Conversion Paths

### Path 1: Lead → Opportunity (Sales Pipeline)

**Purpose**: Track potential deals through sales pipeline

**When to Use**:
- Large corporate contracts
- Bulk booking deals
- Multi-stage negotiations
- Deals requiring approvals

**What Happens**:
1. Lead status → "working"
2. Opportunity record created
3. Lead stays as lead (NOT converted)
4. Moves through pipeline stages
5. Can convert to Client later

**Button**: 🟢 Green "Convert to Opportunity"

**Example**: Hotel chain inquires about bulk services for 10 properties. Create opportunity to track $50,000 deal through negotiation stages.

---

### Path 2: Lead → Client (Direct Conversion)

**Purpose**: Convert qualified lead to paying client

**When to Use**:
- Individual bookings
- Confirmed bookings
- Ready to start service
- Qualified leads ready to pay

**What Happens**:
1. Lead status → "converted"
2. Client record created/linked
3. All bookings transferred
4. Lead marked with timestamp
5. **FINAL conversion**

**Button**: 🟣 Purple "Convert to Client"

**Example**: Customer submits booking for weekly service. Marketing qualifies, converts to client, service begins.

---

### Key Differences

| Feature | Lead → Opportunity | Lead → Client |
|---------|-------------------|---------------|
| Purpose | Track sales deal | Create customer |
| Lead Status | "working" | "converted" |
| Creates | Opportunity | Client |
| Bookings | Stay with lead | Transfer to client |
| Reversible | Yes | No (final) |
| Best for | B2B, large deals | Individual bookings |

---

## 4. Opportunity to Client Flow

### The Two-Step Process

When you win an opportunity, you need **TWO steps** to complete the customer journey:

```
STEP 1: Mark Opportunity as Won
    ↓
STEP 2: Convert Lead to Client
    ↓
FINAL: Client Ready for Service
```

### Step 1: Mark Opportunity as Won

**When**: After successfully closing a deal

**How**:
1. Go to Opportunity detail page
2. Click **"Mark Won"** button (green)
3. Confirm the action
4. Opportunity status changes to "Won"

**What Happens**:
- `won_at` timestamp set
- `close_date` set to today
- `probability` set to 100%
- **Lead status automatically updated to "qualified"**
- Activity logged
- Opportunity marked as closed

### Step 2: Convert Lead to Client

**When**: Immediately after marking opportunity as won

**How**:
1. After marking as won, you'll see a **blue box** appear:
   ```
   💡 Next Step: Convert to Client
   This opportunity is won! Convert the lead to a client 
   to start service delivery.
   
   [Convert Lead to Client] (button)
   ```

2. Click **"Convert Lead to Client"** button
3. You'll be taken to the Lead detail page
4. Click **"Convert to Client"** button (purple)
5. Choose conversion method:
   - Create new client (recommended)
   - Link to existing client
6. Click **"Convert to Client"** in modal
7. Done! Client created

**What Happens**:
- Lead status → "converted"
- Client record created
- All bookings transferred to client
- Lead marked with conversion timestamp
- Client ready for service delivery

### Visual Flow

```
OPPORTUNITY CREATED
    ↓
SALES PROCESS
(Prospecting → Qualification → Proposal → Negotiation)
    ↓
DEAL WON?
    ├─ YES → MARK AS WON
    └─ NO → Mark as Lost
    ↓
STEP 1: MARK OPPORTUNITY AS WON
├─ Opportunity closed
├─ Lead status → "qualified" (automatic)
└─ Blue box appears
    ↓
STEP 2: CONVERT LEAD TO CLIENT
├─ Click "Convert Lead to Client"
├─ Go to Lead page
├─ Click "Convert to Client"
└─ Choose conversion method
    ↓
CLIENT CREATED
├─ Lead status: "converted"
├─ Client record created
├─ Bookings transferred
└─ Ready for service
```

### What You'll See After Winning

**Scenario 1: Lead Not Yet Converted**

Blue Box Appears:
```
💡 Next Step: Convert to Client
This opportunity is won! Convert the lead to a client 
to start service delivery.

[Convert Lead to Client] (button)
```

**Scenario 2: Lead Already Converted**

Green Box Appears:
```
✓ Lead Already Converted

[View Client] (button)
```

### Why Two Steps?

**Opportunity** (Sales Tracking):
- Tracks a potential deal
- Part of sales pipeline
- May or may not close
- Focused on deal value and probability

**Client** (Service Delivery):
- Actual paying customer
- Ready for service delivery
- Has bookings and subscriptions
- Focused on service fulfillment

**The Separation Allows**:
1. Track deals that might not close
2. Move opportunities through stages
3. Predict revenue based on probability
4. Only create clients when deals are won
5. Prevent creating clients for lost deals

### Complete Example: Hotel Chain Deal

**Day 1**: Lead created
- Name: Grand Hotel Group
- Email: contracts@grandhotel.com
- Source: Referral

**Day 5**: Opportunity created
- Title: "Bulk Maid Services - 10 Properties"
- Amount: $50,000
- Probability: 60%
- Stage: Prospecting

**Day 30**: Opportunity moves through pipeline
- Prospecting → Qualification → Proposal → Negotiation
- Lead status: "working"

**Day 40**: Deal Won! 🎉
1. Click **"Mark Won"**
   - Opportunity closed
   - Lead status → "qualified" (automatic)
   - Blue box appears

2. Click **"Convert Lead to Client"**
   - Redirected to Lead page
   - "Convert to Client" button visible

3. Click **"Convert to Client"** (purple button)
   - Modal opens
   - Choose "Create new client"
   - Confirm

4. **Client Created!**
   - Client: Grand Hotel Group
   - Ready for bookings
   - Can start service delivery

### Quick Reference

**When to Mark as Won**:
✅ Deal is closed  
✅ Contract signed  
✅ Customer committed  
✅ Ready to start service  

**When to Convert to Client**:
✅ Immediately after marking as won  
✅ When customer is ready for service delivery  
✅ When you need to create bookings  
✅ When you need to start billing  

**When NOT to Convert**:
❌ Opportunity still in negotiation  
❌ Deal not yet closed  
❌ Customer not committed  
❌ Just exploring/qualifying  

---

## 5. Lead Status Automation

### Automatic Lead Status Update

When you mark an opportunity as **Won**, the system automatically updates the lead status to **"qualified"** so you can immediately convert it to a client.

### The Problem We Solved

**Before (The Issue)**:
```
Mark Opportunity as Won
    ↓
Lead status: "working" (unchanged)
    ↓
Go to Lead page
    ↓
❌ "Convert to Client" button NOT visible
    ↓
Manual step: Update lead status to "qualified"
    ↓
✅ Now button appears
```

**After (The Fix)**:
```
Mark Opportunity as Won
    ↓
✅ Lead status automatically → "qualified"
    ↓
Activity logged on lead
    ↓
Go to Lead page
    ↓
✅ "Convert to Client" button visible immediately
    ↓
Convert to client
```

### What Happens When You Mark as Won

**Step-by-Step Automation**:

1. **You click "Mark Won"**
   - Opportunity status → "won"
   - `won_at` timestamp set
   - `close_date` set to today
   - `probability` set to 100%

2. **System checks the lead**
   - Is there a lead linked? ✓
   - Is lead already converted? ✗

3. **System updates lead status**
   - Lead status → "qualified"
   - Activity logged: "Lead Qualified - Opportunity Won"
   - Description: "Lead status updated to qualified after winning opportunity: [Opportunity Title]"

4. **Result**
   - Lead is now qualified
   - "Convert to Client" button visible
   - Ready for immediate conversion

### Lead Status Flow

**Complete Status Progression**:

```
NEW
 ↓
 │ (Marketing reviews and qualifies)
 ↓
QUALIFIED ←──────────────────┐
 ↓                           │
 │ (Create opportunity)      │
 ↓                           │
WORKING                      │
 ↓                           │
 │ (Opportunity in pipeline) │
 ↓                           │
 │ (Mark opportunity as won) │
 ↓                           │
QUALIFIED ───────────────────┘ (Auto-updated)
 ↓
 │ (Convert to client)
 ↓
CONVERTED
```

**Status Meanings**:

| Status | Meaning | Can Convert to Client? |
|--------|---------|----------------------|
| **new** | Just created, not reviewed | ❌ No |
| **qualified** | Reviewed and valid | ✅ Yes |
| **working** | Has opportunity, in pipeline | ❌ No |
| **converted** | Already a client | ❌ Already done |
| **disqualified** | Not a valid lead | ❌ No |

### Why This Approach?

**Benefits**:

1. **Seamless Workflow** - No manual status updates needed
2. **Clear Intent** - Won opportunity = qualified lead
3. **Audit Trail** - Activity logged on lead
4. **Prevents Confusion** - No "where's the button?" moments
5. **Consistent Logic** - Winning = qualifying

### Activity Logging

**What Gets Logged on the Lead**:

```
Activity Created:
├─ Type: "status_change"
├─ Subject: "Lead Qualified - Opportunity Won"
├─ Description: "Lead status updated to qualified after winning 
│                opportunity: [Opportunity Title]"
├─ Status: "completed"
├─ Completed at: [timestamp]
└─ Created by: [User who marked as won]
```

**Visible in**: Lead Activities section

### Edge Cases Handled

**Case 1: Lead Already Converted**
- Skip status update (already a client)
- Show "View Client" button instead

**Case 2: No Lead Linked**
- Skip status update
- Just mark opportunity as won

**Case 3: Lead Status is "new"**
- Lead status: "new" → "qualified"
- Activity logged
- Ready for conversion

**Case 4: Lead Status is "disqualified"**
- Lead status: "disqualified" → "qualified"
- Activity logged
- Re-qualified, ready for conversion

### User Experience Comparison

**Before (Manual)**:
```
1. Mark opportunity as won
2. Go to lead page
3. ❌ Button not visible
4. Think: "Where's the button?"
5. Check lead status: "working"
6. Manually update to "qualified"
7. ✅ Button appears
8. Convert to client

Steps: 8 | Confusion: High
```

**After (Automatic)**:
```
1. Mark opportunity as won
2. Go to lead page
3. ✅ Button visible immediately
4. Convert to client

Steps: 4 | Confusion: None
```

**50% fewer steps, 100% less confusion!**

---

## 6. Duplicate Prevention

### How It Works

**Email Matching** (100% confidence):
- Exact match, case-insensitive
- Checks leads and clients

**Phone Matching** (95% confidence):
- Normalized comparison
- Removes spaces, dashes, parentheses
- Works across formats

**Client Detection**:
- Checks if email/phone belongs to existing client
- Auto-links lead to client
- Sets status to "qualified"

### Prevention Flow

```
Booking Form Submitted
    ↓
Check for Duplicates
    ├─ Email match? → Link to existing lead
    ├─ Phone match? → Link to existing lead
    ├─ Client exists? → Create lead linked to client
    └─ No match? → Create new lead
    ↓
Create Booking Linked to Lead
```

### What Gets Prevented
✅ Duplicate leads (same email/phone)  
✅ Duplicate clients  
✅ Data loss  
✅ Orphaned bookings  

---

## 7. Step-by-Step Workflows

### Workflow 1: New Customer Books Online

```
1. Customer fills booking form
   - Name, email, phone, service details
   
2. System checks for duplicates
   - No existing lead/client found
   
3. System creates new lead
   - Status: "new"
   - Source: "Website"
   
4. System creates booking
   - Linked to lead
   - All data saved
   
5. Marketing qualifies lead
   - Reviews details
   - Status → "qualified"
   
6. Convert to client
   - Click "Convert to Client"
   - Choose "Create new client"
   - Bookings transferred
   
7. Service begins
```

### Workflow 2: Existing Lead Books Again

```
1. Lead exists from marketing
   - No bookings yet
   
2. Lead submits booking online
   - Same email detected
   
3. System links to existing lead
   - NO duplicate created
   
4. Booking created
   - Linked to existing lead
   
5. Marketing sees update
   - Lead now has booking
   - Higher priority
   
6. Convert to client
```

### Workflow 3: Existing Client Books Again

```
1. Client exists with bookings
   
2. Client submits new booking
   - Same email detected
   
3. System creates lead linked to client
   - Status: "qualified"
   
4. Booking created
   - Linked to both lead and client
   
5. Automatic handling
   - No manual conversion needed
```

---

## 8. User Interface Guide

### Lead Detail Page

**Header** (Before Conversion):
```
👤 John Doe
john@example.com • 256-123-4567 • Qualified

[Convert to Opportunity]  [Convert to Client]
```

**Header** (After Conversion):
```
👤 John Doe
john@example.com • 256-123-4567 • Converted

[View Client]  [✓ Converted]
```

**Bookings Section**:
```
📅 Bookings                    [3 Bookings]

┌─────────────────────────────────────┐
│ Booking #123          [Pending]     │
│ Service: Weekly Cleaning            │
│ Start: Nov 15, 2025                 │
│ Maid: Maria Santos    [View →]      │
└─────────────────────────────────────┘
```

**Status Colors**:
- 🟡 Pending (Yellow)
- 🔵 Confirmed (Blue)
- 🟢 Active (Green)
- ⚪ Completed (Gray)
- 🔴 Cancelled (Red)

---

### Convert to Client Modal

**Structure**:
```
┌────────────────────────────────────┐
│ 👥 Convert Lead to Client          │
├────────────────────────────────────┤
│                                    │
│ Lead Information                   │
│ Name: John Doe                     │
│ Email: john@example.com            │
│ Phone: 256-123-4567                │
│ Bookings: 2                        │
│                                    │
│ Conversion Method                  │
│                                    │
│ ⦿ Create a New Client              │
│   ✓ Ready to convert               │
│   Click "Convert to Client" below  │
│                                    │
│ ○ Link to Existing Client          │
│   Search and select client         │
│                                    │
│ ⚠️ Important                       │
│ Cannot be undone. Bookings will    │
│ be transferred to client.          │
│                                    │
│ ───────────────────────────────    │
│                                    │
│ [Cancel]    [Convert to Client]    │
└────────────────────────────────────┘
```

**Steps**:
1. Click "Convert to Client" button
2. Modal opens with lead info
3. Choose conversion method
4. Click "Convert to Client" at bottom
5. Redirected to client page

---

## 9. Convert to Client Modal

### Modal Features

The "Convert to Client" modal has been designed to be professional, clear, and user-friendly.

### 1. Lead Information Summary (Top Section)

Shows a clear summary of the lead being converted:
- Name
- Email
- Phone (if available)
- Number of bookings (if any)

**Visual**: Blue/indigo highlighted box at the top

### 2. Two Clear Conversion Options

#### Option 1: Create a New Client ✨

- **Large clickable card** with radio button
- **Clear description**: "Create a fresh client record from this lead's information. All bookings will be transferred to the new client."
- **Visual feedback**: When selected, shows green checkmark with "Ready to convert" message
- **Instructions**: "Click 'Convert to Client' below to proceed"

#### Option 2: Link to Existing Client 🔗

- **Large clickable card** with radio button
- **Clear description**: "Merge this lead with an existing client record. Prevents duplicate clients."
- **Search functionality**: When selected, shows search box
- **Live search**: Type name, email, or phone to find clients
- **Visual selection**: Selected client shows green checkmark
- **Confirmation**: Shows "Client selected" with Client ID

### 3. Important Warning ⚠️

Yellow warning box at the bottom:
- "This action cannot be undone"
- "Lead will be marked as 'converted'"
- "All bookings will be transferred to the client"

### 4. Clear Action Buttons (Footer)

**Cancel Button** (Left):
- Gray/neutral color
- Closes modal without changes

**Convert to Client Button** (Right):
- **Large, prominent indigo button**
- **Icon + Text**: "Convert to Client"
- **Smart disable**: Disabled if "Link to Existing Client" is selected but no client chosen
- **Visual feedback**: Shadow effect on hover

### How to Use

**Creating a New Client** (Most Common):

1. Click **"Convert to Client"** button on lead page
2. Modal opens with lead information displayed
3. **"Create a New Client"** is selected by default
4. You'll see: ✓ "Ready to convert" message
5. Click the **large "Convert to Client"** button at the bottom
6. Done! Lead is converted to a new client

**Steps**: 2 clicks total

**Linking to Existing Client** (Prevent Duplicates):

1. Click **"Convert to Client"** button on lead page
2. Modal opens with lead information displayed
3. Click **"Link to Existing Client"** option
4. Search box appears
5. Type client name, email, or phone
6. Click on the matching client from results
7. You'll see: ✓ "Client selected" confirmation
8. Click the **large "Convert to Client"** button at the bottom
9. Done! Lead is merged with existing client

**Steps**: 4-5 clicks total

### Visual Improvements

**Before (Old Modal)**:
- ❌ Small, unclear modal
- ❌ No visual feedback when selecting option
- ❌ Button not visible/prominent
- ❌ No lead information shown
- ❌ Confusing what to do next

**After (New Modal)**:
- ✅ Large, professional modal
- ✅ Lead information summary at top
- ✅ Large clickable option cards
- ✅ Visual feedback (borders, colors, checkmarks)
- ✅ Clear "Ready to convert" message
- ✅ Prominent "Convert to Client" button
- ✅ Warning message about action
- ✅ Disabled state when incomplete

### User Flow

```
Click "Convert to Client"
        ↓
Modal Opens
        ↓
See Lead Information (Name, Email, Phone, Bookings)
        ↓
Choose Option:
├─ Create New Client (default)
│  ├─ See "Ready to convert" ✓
│  └─ Click "Convert to Client" button
│
└─ Link to Existing Client
   ├─ Search for client
   ├─ Select from results
   ├─ See "Client selected" ✓
   └─ Click "Convert to Client" button
        ↓
Conversion Complete!
        ↓
Redirected to Client Page
```

### Design Elements

**Colors & States**:
- **Default option cards**: Gray border, white background
- **Selected option**: Indigo border, light indigo background
- **Hover**: Border changes to light indigo
- **Ready state**: Green checkmark + message
- **Warning**: Yellow background with warning icon
- **Action button**: Indigo, large, with shadow
- **Disabled button**: Gray, no shadow, no hover

**Visual Hierarchy**:
1. **Lead Information** (Top) - Blue box
2. **Conversion Method** (Middle) - Large option cards
3. **Warning** (Bottom) - Yellow box
4. **Actions** (Footer) - Cancel (left) + Convert (right)

### Key Improvements

1. **No confusion**: Clear what each option does
2. **Visual feedback**: Always know what's selected
3. **Prominent button**: Can't miss the "Convert to Client" button
4. **Smart validation**: Button disabled if incomplete
5. **Professional design**: Modern, clean, accessible
6. **Clear instructions**: "Click 'Convert to Client' below to proceed"
7. **Warning visible**: Can't miss the important warning
8. **Lead context**: See who you're converting at a glance

---

## 10. Data Management

### Database Structure

**Bookings Table**:
- `lead_id` (nullable) - Links to lead
- `client_id` (nullable) - Links to client
- Contact snapshot (name, email, phone)
- Service details (40+ fields)

**Leads Table**:
- Contact info (name, email, phone)
- `status` (new, qualified, working, converted)
- `client_id` (set after conversion)
- `converted_at` (timestamp)

**Clients Table**:
- Contact person, phone, city
- Subscription tier, status
- Links to user account

### Relationships

```
Lead ──has many──> Bookings
  │                  │
  │                  └──> belongs to ──> Client
  │
  └──> converts to ──> Client

Client ──has many──> Leads (converted)
Client ──has many──> Bookings
```

### Data Preservation

**Preserved**:
✅ All booking data (40+ fields)  
✅ Lead information  
✅ Activities and history  
✅ Source attribution  
✅ Conversion timestamp  

**Changes During Conversion**:
- Lead status → "converted"
- Lead client_id → set
- Booking client_id → updated
- Booking lead_id → preserved

---

## 11. Troubleshooting

### Issue 1: Button Not Visible

**Cause**: Lead already converted

**Solution**: 
- Check lead status
- Click "View Client" instead
- Lead can only convert once

### Issue 2: Duplicate Leads Created

**Cause**: Service not detecting duplicates

**Solution**:
1. Check email format
2. Verify phone normalization
3. Check logs: `storage/logs/laravel.log`
4. Ensure `BookingToLeadService` running

### Issue 3: Bookings Not Transferring

**Cause**: Conversion service error

**Solution**:
1. Check error logs
2. Verify `lead_id` column exists
3. Run conversion again
4. Check database relationships

### Issue 4: Cannot Convert - Status Error

**Cause**: Lead status must be "qualified" or "working"

**Solution**:
1. Check current status
2. Update to "qualified" first
3. Reactivate if "disqualified"

### Issue 5: Modal Button Not Visible

**Cause**: Button below fold

**Solution**:
1. Scroll down in modal
2. Look for border separator
3. Button at bottom after warning
4. Refresh page if needed

---

### Data Backfill Command

For existing bookings without leads:

```bash
# Test run
php artisan leads:backfill-bookings --dry-run

# Apply changes
php artisan leads:backfill-bookings
```

**What It Does**:
- Finds bookings with client but no lead
- Creates/links leads
- Marks as "converted"
- Shows progress and summary

---

### Run Tests

```bash
php artisan test --filter=LeadClientConversionTest
```

**Tests** (11 total):
1. New booking creates lead
2. Existing lead links correctly
3. Client detection works
4. Bookings link to leads
5. Conversion creates client
6. Bookings transfer correctly
7. Existing client detection
8. Duplicate prevention (email)
9. Duplicate prevention (phone)
10. Booking count display
11. Client-lead relationships

---

## Quick Reference

### Lead Statuses

| Status | Meaning | Action |
|--------|---------|--------|
| new | Just created | Qualify |
| qualified | Reviewed, valid | Convert |
| working | Has opportunity | Move through stages |
| converted | Now a client | View client |
| disqualified | Not valid | Archive |

### Key Commands

```bash
# Migrations
php artisan migrate

# Backfill
php artisan leads:backfill-bookings --dry-run
php artisan leads:backfill-bookings

# Tests
php artisan test --filter=LeadClientConversionTest

# Cache
php artisan cache:clear
php artisan view:clear
```

### Best Practices

**Marketing Team**:
1. Review new leads daily
2. Prioritize leads with bookings
3. Qualify within 24 hours
4. Convert promptly
5. Log all interactions

**Operations Team**:
1. Monitor bookings on leads
2. Ensure conversion before service
3. Report suspected duplicates
4. Verify client data accuracy

---

## Summary

### The System in 3 Steps

1. **Booking Submitted** → Lead Created (with duplicate check)
2. **Marketing Qualifies** → Lead status updated
3. **Convert to Client** → Bookings transferred, service begins

### Key Points

- ✅ All bookings start as leads
- ✅ Automatic duplicate prevention
- ✅ Two conversion paths (Opportunity or Client)
- ✅ All data preserved
- ✅ Complete customer journey tracking

### For Booking-Based Leads

**Always use**: "Convert to Client" (purple button)  
**Not**: "Convert to Opportunity" (unless large B2B deal)

---

**Questions?** Check logs at `storage/logs/laravel.log` or run tests to verify system health.

**End of Manual** | Version 5.0 | November 2025
