# Opportunity to Client Conversion Flow

## Complete Workflow: From Opportunity to Client

### The Two-Step Process

When you win an opportunity, you need **TWO steps** to complete the customer journey:

```
STEP 1: Mark Opportunity as Won
    ↓
STEP 2: Convert Lead to Client
    ↓
FINAL: Client Ready for Service
```

---

## Step-by-Step Guide

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
- Activity logged
- Opportunity marked as closed

---

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

---

## Visual Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    OPPORTUNITY CREATED                       │
│  - Lead exists                                              │
│  - Tracking potential deal                                  │
│  - Status: Open                                             │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────┐
        │  SALES PROCESS                 │
        │  - Prospecting                 │
        │  - Qualification               │
        │  - Proposal                    │
        │  - Negotiation                 │
        └────────┬───────────────────────┘
                 │
                 ▼
        ┌────────────────────┐
        │  DEAL WON?         │
        └────┬───────────────┘
             │
        ┌────┴────┐
        │         │
        ▼         ▼
    ┌─────┐   ┌─────┐
    │ YES │   │ NO  │
    └──┬──┘   └──┬──┘
       │         │
       │         └──> Mark as Lost → End
       │
       ▼
┌──────────────────────────────────────┐
│  STEP 1: MARK OPPORTUNITY AS WON     │
│  - Click "Mark Won" button           │
│  - Confirm action                    │
│  - Opportunity closed                │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│  BLUE BOX APPEARS                    │
│  💡 Next Step: Convert to Client     │
│  [Convert Lead to Client] button    │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│  STEP 2: CONVERT LEAD TO CLIENT      │
│  - Click "Convert Lead to Client"   │
│  - Redirected to Lead page          │
│  - Click "Convert to Client"        │
│  - Choose conversion method         │
│  - Confirm conversion               │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│  CLIENT CREATED                      │
│  - Lead status: "converted"         │
│  - Client record created            │
│  - Bookings transferred             │
│  - Ready for service                │
└──────────────────────────────────────┘
```

---

## What You'll See After Winning

### Scenario 1: Lead Not Yet Converted

**Blue Box Appears**:
```
┌─────────────────────────────────────────┐
│ 💡 Next Step: Convert to Client         │
│                                         │
│ This opportunity is won! Convert the   │
│ lead to a client to start service      │
│ delivery.                               │
│                                         │
│ [Convert Lead to Client] (button)      │
└─────────────────────────────────────────┘
```

**Action**: Click button → Go to Lead page → Convert to Client

---

### Scenario 2: Lead Already Converted

**Green Box Appears**:
```
┌─────────────────────────────────────────┐
│ ✓ Lead Already Converted                │
│                                         │
│ [View Client] (button)                  │
└─────────────────────────────────────────┘
```

**Action**: Click button → View Client record

---

## Why Two Steps?

### Opportunity ≠ Client

**Opportunity**:
- Tracks a potential deal
- Part of sales pipeline
- May or may not close
- Focused on deal value and probability

**Client**:
- Actual paying customer
- Ready for service delivery
- Has bookings and subscriptions
- Focused on service fulfillment

### The Separation Allows:

1. **Sales Tracking**: Track deals that might not close
2. **Pipeline Management**: Move opportunities through stages
3. **Forecasting**: Predict revenue based on probability
4. **Clean Data**: Only create clients when deals are won
5. **No Duplicates**: Prevent creating clients for lost deals

---

## Complete Example

### Example: Hotel Chain Deal

**Day 1**: Lead created
- Name: Grand Hotel Group
- Email: contracts@grandhotel.com
- Source: Referral

**Day 5**: Opportunity created
- Title: "Bulk Maid Services - 10 Properties"
- Amount: $50,000
- Probability: 60%
- Stage: Prospecting

**Day 10**: Move to Qualification
- Probability: 70%

**Day 20**: Move to Proposal
- Probability: 80%

**Day 30**: Move to Negotiation
- Probability: 90%

**Day 40**: Deal Won! 🎉
1. **Click "Mark Won"**
   - Opportunity closed
   - won_at: 2025-11-09
   - probability: 100%

2. **Blue box appears**: "Next Step: Convert to Client"

3. **Click "Convert Lead to Client"**
   - Redirected to Lead page
   - Lead status: "qualified"
   - Has opportunity: Yes

4. **Click "Convert to Client"** (purple button)
   - Modal opens
   - Choose "Create new client"
   - Click "Convert to Client"

5. **Client Created!**
   - Client: Grand Hotel Group
   - Status: Active
   - Ready for bookings
   - Can start service delivery

---

## Quick Reference

### When to Mark as Won
✅ Deal is closed  
✅ Contract signed  
✅ Customer committed  
✅ Ready to start service  

### When to Convert to Client
✅ Immediately after marking as won  
✅ When customer is ready for service delivery  
✅ When you need to create bookings  
✅ When you need to start billing  

### When NOT to Convert
❌ Opportunity still in negotiation  
❌ Deal not yet closed  
❌ Customer not committed  
❌ Just exploring/qualifying  

---

## Benefits of This Flow

### For Sales Team:
- Track deals through pipeline
- Forecast revenue accurately
- Manage probability and stages
- Focus on closing deals

### For Operations Team:
- Only work with real clients
- No confusion with prospects
- Clean client database
- Clear service delivery queue

### For Management:
- Accurate sales metrics
- Clear conversion rates
- Pipeline visibility
- Revenue forecasting

---

## Summary

**The Process**:
1. Create Opportunity (track deal)
2. Move through pipeline stages
3. Mark as Won (deal closed)
4. Convert Lead to Client (ready for service)
5. Start service delivery

**Key Point**: Winning an opportunity is NOT the same as creating a client. You need both steps to complete the customer journey.

**Remember**: The blue "Convert Lead to Client" button appears automatically after marking an opportunity as won!

---

**Questions?** Check the Lead-Client System User Manual for more details.
