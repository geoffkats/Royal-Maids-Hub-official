# Royal Maids Hub - Automation Implementation Plan v1.0
**Date:** February 4, 2026  
**Timeline:** 0–3 Months  
**Priority:** High  
**Status:** In Progress

---

## Executive Summary

This document outlines a phased automation strategy to reduce manual workload across CRM, bookings, training, tickets, and finance modules. The plan focuses on removing repetitive tasks, reducing errors, and improving response times while managing risks through staged rollouts and validation.

**Expected Impact:**
- 30–40% reduction in manual data entry tasks
- 50% faster lead-to-client conversion
- 60% reduction in SLA breaches (tickets & opportunities)
- Improved team efficiency and customer satisfaction

---

## Module-by-Module Automation Audit

### 1. CRM Module

**Current State:**
- ✅ Lead scoring, auto-convert, SLA activities, and reminders are partially implemented
- ✅ Lead deduplication and merge services exist
- ❌ Source-based lead routing is not implemented (TODO in code)
- ❌ Auto-booking creation from won opportunities is incomplete
- ❌ Lead source segmentation for workflow routing is missing

**Manual Workflows Still Happening:**
1. Sales reps manually route leads to follow-up sequences based on source
2. No automated follow-ups for cold leads (7+ days without contact)
3. Conversion to booking is manual (won opportunity → booking step is disconnected)
4. No automated lead reassignment based on workload balancing

**Automation Gaps:**
| Gap | Manual Work | Impact | Priority |
|-----|-------------|--------|----------|
| Source-based routing | Reps assign leads manually by channel | Slow, inconsistent | HIGH |
| Opportunity → Booking | Sales rep must manually create booking | Delays service start | CRITICAL |
| Workload balancing | No auto-reassignment when reps overloaded | Poor lead quality | MEDIUM |
| Cold lead follow-up | Manual reminders for inactive leads | Missed opportunities | HIGH |

---

### 2. Booking Module

**Current State:**
- ✅ Booking form exists with multi-section intake
- ✅ Price calculation logic exists (but not auto-applied)
- ❌ No smart maid assignment based on skills/availability
- ❌ No automated client-to-maid matching
- ❌ No auto-invoicing or payment reconciliation

**Manual Workflows Still Happening:**
1. Operator manually selects maid for each booking (no recommendation engine)
2. Pricing is calculated then manually entered/overridden
3. Booking confirmation emails must be sent manually
4. Date/location conflicts are caught only when reviewing manually
5. Payment collection and invoicing require manual action

**Automation Gaps:**
| Gap | Manual Work | Impact | Priority |
|-----|-------------|--------|----------|
| Maid matching | Operator searches/selects maid manually | Slow, suboptimal matches | HIGH |
| Price auto-apply | Price calculated but not auto-filled | Data entry errors | MEDIUM |
| Booking validation | Conflicts caught manually | Double bookings possible | HIGH |
| Confirmation flow | Manual emails to client/maid | Delays and no-shows | HIGH |
| Invoicing | Invoice created and sent manually | Cashflow delay | CRITICAL |

---

### 3. Ticket/Support Module

**Current State:**
- ✅ Ticket SLA rules exist (response/resolution times)
- ✅ Tier-based priority boosting is implemented
- ❌ Bulk ticket assignment is not implemented (TODO)
- ❌ Ticket export is not implemented (TODO)
- ❌ SLA breach notifications are incomplete (TODO)
- ❌ Auto-assignment rules are missing
- ❌ Customer/staff notifications at ticket lifecycle are missing

**Manual Workflows Still Happening:**
1. Manager manually assigns tickets to staff members
2. SLA breaches are not actively monitored or escalated
3. Customers receive no automatic status updates
4. Staff don't get automatic reminders for approaching SLA deadlines
5. No spam filtering or automatic ticket categorization

**Automation Gaps:**
| Gap | Manual Work | Impact | Priority |
|-----|-------------|--------|----------|
| Auto-assignment | Manager assigns each ticket | Slow response | HIGH |
| SLA breach alerts | Breaches discovered reactively | Penalties and upset customers | CRITICAL |
| Notifications | Staff/customers manually checked | Missed updates | HIGH |
| Categorization | Manual ticket categorization | Routing errors | MEDIUM |
| Escalation | No auto-escalation logic | Critical issues ignored | CRITICAL |

---

### 4. Training Module

**Current State:**
- ✅ Training program creation and tracking exists
- ❌ No reminders or notifications for trainers/trainees
- ❌ No auto-completion logic based on hours/evaluations
- ❌ No retraining triggers if performance drops
- ❌ No schedule conflict detection

**Manual Workflows Still Happening:**
1. Trainer must manually check schedule and send reminders
2. Training completion is manually marked (no validation)
3. No alerts if maid misses scheduled training
4. Retraining after poor evaluations is not tracked
5. Training schedule conflicts must be spotted manually

**Automation Gaps:**
| Gap | Manual Work | Impact | Priority |
|-----|-------------|--------|----------|
| Session reminders | Trainer/maid manually notified | Missed sessions | HIGH |
| Completion validation | Manual marking, no rules | False completions possible | MEDIUM |
| Performance retraining | Manager must catch and assign | Bad staff not re-trained | MEDIUM |
| Schedule conflict detection | Manual review of calendar | Double bookings possible | MEDIUM |
| Progress tracking | Manual progress updates | Incomplete visibility | LOW |

---

### 5. Finance Module

**Current State:**
- ❌ No dedicated finance automation exists
- ❌ No invoice auto-generation from bookings
- ❌ No payment tracking or reconciliation
- ❌ No expense categorization automation
- ❌ No cashflow forecasting

**Manual Workflows Still Happening:**
1. Finance team must manually create invoices from bookings
2. Payments must be manually reconciled with invoices
3. Overdue payment reminders are manual
4. Expense coding is done by finance staff
5. Cashflow is manually estimated

**Automation Gaps:**
| Gap | Manual Work | Impact | Priority |
|-----|-------------|--------|----------|
| Invoice generation | Created manually per booking | Delays revenue recording | CRITICAL |
| Payment reconciliation | Manual bank-to-invoice matching | Accounting errors | CRITICAL |
| Overdue reminders | Manual follow-ups for unpaid invoices | Late payments | HIGH |
| Expense categorization | Finance team codes manually | Time-consuming | MEDIUM |
| Cashflow forecasting | Manual spreadsheet estimates | Poor planning | LOW |

---

## Phased Implementation Roadmap

### Phase 1: Quick Wins (Weeks 1–4) — High Impact, Low Risk

**Goal:** Remove the most painful manual touchpoints and establish automation foundation.

#### 1.1 Ticket Auto-Assignment (Week 1–2)
**Impact:** Immediate response time improvement  
**Effort:** Medium (4–6 hours)

- Implement round-robin or workload-based auto-assignment for new tickets
- Add assignment rules (e.g., by category, priority, service tier)
- Create assignment audit trail and ability to manually override

**Deliverable:**
- Auto-assignment rules configurable in CRM Settings
- Tickets assigned within 2 minutes of creation
- Manual override capability

---

#### 1.2 SLA Breach Notifications (Week 2–3)
**Impact:** No more missed SLA breaches  
**Effort:** Medium (4–5 hours)

- Complete the TODO in [app/Models/Ticket.php](app/Models/Ticket.php) (line 248) to send SLA breach notifications
- Send email/in-app alert when SLA deadline is 2 hours away
- Send escalation alert when SLA is breached

**Deliverable:**
- Email notifications to assigned staff (and manager if breached)
- In-app (browser) alerts visible on dashboard
- Escalation log for audit

---

#### 1.3 Booking Confirmation Flow (Week 3–4)
**Impact:** Clients and maids notified automatically  
**Effort:** Medium (5–7 hours)

- Auto-generate and send booking confirmation email to client
- Auto-send maid assignment notification (if maid exists)
- Auto-send confirmation reminders 24 hours and 2 hours before start date

**Deliverable:**
- Email templates for confirm/remind/assignment
- Automated mail queue processing
- Opt-out configuration per client/maid

---

### Phase 2: Core Automations (Weeks 5–8) — Medium Impact, Medium Risk

**Goal:** Automate high-value workflows (opportunities → bookings, smart matching).

#### 2.1 Opportunity → Booking Auto-Creation (Week 5–6)
**Impact:** Eliminates manual handoff; speeds service start  
**Effort:** High (8–10 hours)

- Complete the TODO in [app/Services/CRM/ConvertLeadToClientService.php](app/Services/CRM/ConvertLeadToClientService.php) (line 379)
- When opportunity is marked as won, auto-create a booking based on:
  - Package selected in opportunity
  - Client's location and preferences
  - Preferred start date (if available)
- Trigger maid search/assignment after booking creation

**Deliverable:**
- Booking auto-created when opportunity status = 'won'
- Pre-filled from opportunity data
- Manual review/confirmation option available
- Audit trail of auto-created vs. manually created bookings

**Risk Mitigation:**
- Start in "Draft" status (requires manager approval before confirming)
- Run 2-week shadow mode (create but don't confirm)
- 95% accuracy threshold before auto-confirmation enabled

---

#### 2.2 Smart Maid Assignment for Bookings (Week 6–7)
**Impact:** Better matching = fewer rework requests  
**Effort:** High (10–12 hours)

- Create a maid matching service that scores potential maids by:
  - Availability (no schedule conflicts)
  - Location proximity (travel time)
  - Service tier match (skills match service tier)
  - Workload (prefer less overbooked maids)
  - Rating (prefer 4.5+ stars)
- Recommend top 3 candidates to operator
- Allow operator to override and auto-assign

**Deliverable:**
- Smart matching algorithm in new `MaidMatchingService`
- Top 3 recommended maids during booking creation
- Automatic assignment option for repeat clients
- Matching score visible to operator

**Risk Mitigation:**
- Operator can always override recommendations
- Start with "suggested" mode (no auto-select)
- Track matching accuracy vs. rework requests (target: <5% rework)

---

#### 2.3 Booking Price Auto-Apply (Week 7–8)
**Impact:** Eliminates pricing errors; speeds billing  
**Effort:** Medium (5–6 hours)

- Use existing `calculateBookingPrice()` method to auto-populate price
- Apply price based on:
  - Selected package
  - Family size (from booking form)
  - Service level
- Allow manual override with audit trail

**Deliverable:**
- Price auto-calculated on form save
- Manual override with note field
- Pricing history audit trail

---

### Phase 3: Optimization & Intelligence (Weeks 9–12) — Medium Impact, Higher Risk

**Goal:** Add predictive and analytical automations.

#### 3.1 Lead Routing by Source (Week 9–10)
**Impact:** Faster conversion by source-specific workflows  
**Effort:** Medium (6–8 hours)

- Complete TODO in [app/Services/CRM/LeadAssignmentService.php](app/Services/CRM/LeadAssignmentService.php) (line 141)
- Route leads to sales reps by:
  - Source channel (e.g., Google Ads → specialist rep)
  - Lead score (high-score leads to closer)
  - Rep specialization (if configured)
- Auto-create follow-up sequences per source

**Deliverable:**
- Source-based routing rules in CRM Settings
- Routing audit trail
- Ability to exclude/override per lead

**Risk Mitigation:**
- Run 2-week A/B test (routed vs. manual)
- Measure conversion rate improvement
- Manual override always available

---

#### 3.2 Training Reminders & Auto-Completion (Week 10–11)
**Impact:** Higher attendance, less manual chasing  
**Effort:** Medium (6–8 hours)

- Auto-send trainer reminder 3 days, 1 day, and 2 hours before scheduled training
- Auto-notify maid 1 day and 2 hours before
- Auto-mark training complete if:
  - Required hours logged, AND
  - Evaluation score ≥ threshold (e.g., 70%)
- Auto-trigger retraining request if:
  - Maid evaluation drops below 60%
  - Maid hasn't completed refresher training in 6 months

**Deliverable:**
- Scheduled reminders (email + SMS if available)
- Auto-completion rules configurable
- Retraining auto-trigger with manager notification

---

#### 3.3 Finance Automation: Invoice & Payment (Week 11–12)
**Impact:** Faster cashflow; fewer accounting errors  
**Effort:** High (10–12 hours)

- Auto-generate invoice when booking is confirmed
- Include:
  - Booking details and service tier
  - Calculated price
  - Payment terms (due date based on client tier)
  - Invoice number and company details
- Auto-send invoice email to client
- Track payment status (unpaid → paid → reconciled)
- Auto-send payment reminders (7 days before due, 3 days overdue, 10 days overdue)
- Flag for manual review if:
  - Invoice amount ≠ expected (error)
  - Payment not received by due date (overdue)

**Deliverable:**
- Invoice auto-generation from booking
- Payment tracking system
- Automated payment reminders
- Finance dashboard showing cashflow status
- Monthly reconciliation report

---

## Risk Assessment & Mitigation Strategy

### Risk Matrix

| Risk | Likelihood | Impact | Mitigation | Trigger |
|------|------------|--------|-----------|---------|
| Auto-assignment creates poor matches | Medium | Medium | Operator override always available; track mismatch rate | >5% mismatch rate |
| Data quality issues → automation errors | High | High | Enforce required fields and validation before automation | Missing required data |
| Over-notification → staff frustration | High | Medium | Batch digests; configurable per-role; throttling | Complaint feedback |
| Wrong lead routing → lost sales | Medium | High | A/B test routing rules; measure conversion impact | <2% conversion improvement |
| Booking auto-creation is premature | Medium | High | Start in "draft" mode requiring manager approval | Manual approval >50% |
| Finance automation loses transaction data | Low | Critical | Audit trail logging; reconciliation checks; staging period | Any payment mismatch |

### Mitigation Strategy by Phase

**Phase 1 (Weeks 1–4):**
- All automation starts in **"suggested"** or **"draft"** mode (requires human approval)
- 2-week shadow mode (create but don't execute)
- Daily audit logs reviewed by manager
- Rollback plan: immediately revert any feature if accuracy <95%

**Phase 2 (Weeks 5–8):**
- Booking auto-creation starts in **draft status** (manager must approve before confirming)
- Maid matching shows **top 3 candidates** (operator chooses)
- Run 2-week A/B test before enabling auto-confirmation
- Track key metrics: rework rate, customer satisfaction, revenue impact

**Phase 3 (Weeks 9–12):**
- Lead routing tested in **shadow mode** for 1 week first
- Training reminders are **informational** (no action required immediately)
- Finance automation includes **manual reconciliation step** before marking "paid"

---

## Success Metrics & KPIs

### Track These Metrics Weekly

| Module | Metric | Baseline | Target (Week 12) | Measurement |
|--------|--------|----------|------------------|-------------|
| **Tickets** | Avg response time | 4 hours | 30 minutes | From creation to first assignment |
| **Tickets** | SLA breach rate | 15% | <5% | % of tickets breaching deadline |
| **CRM** | Lead-to-client conversion time | 14 days | 7 days | From lead creation to booking |
| **CRM** | Lead conversion rate | 10% | 15% | % of leads that convert |
| **Bookings** | Booking creation time | 20 minutes | 5 minutes | From booking form to confirmed |
| **Bookings** | Rework rate | 8% | <3% | Cancellations + reschedules / total bookings |
| **Training** | Training attendance | 85% | 95% | % of scheduled trainings attended |
| **Finance** | Invoice-to-payment time | 18 days | 10 days | From invoice creation to payment |
| **Finance** | Overdue invoice rate | 20% | <10% | % of invoices unpaid after due date |

### Reporting

- **Weekly dashboard** showing all KPIs vs. targets
- **Monthly automation accuracy report** (% of automated decisions that were correct)
- **Quarterly ROI analysis** (time saved × hourly rate)

---

## Implementation Guidelines

### Developer Checklist for Each Feature

For **every** automation feature, developers must:

- [ ] Add comprehensive logging (input → decision → action)
- [ ] Include audit trail showing when automation triggered and why
- [ ] Provide manual override/exception mechanism
- [ ] Add configuration controls in CRM Settings or admin panel
- [ ] Create (or update) tests ensuring 95%+ accuracy before production
- [ ] Add a "shadow mode" flag to test without executing actual changes
- [ ] Document expected behavior and edge cases

### Rollout Checklist per Phase

Before deploying each phase:

- [ ] All tests pass (unit + feature)
- [ ] Code reviewed by at least one other developer
- [ ] Pint formatting applied (`vendor/bin/pint --dirty`)
- [ ] Runbook created for rollback
- [ ] Stakeholder (manager) briefed on what's changing and why
- [ ] Audit trail logging is enabled and monitored
- [ ] Shadow mode period (1-2 weeks) completed successfully
- [ ] Accuracy threshold (95%+) met before auto-execution enabled

---

## Technology & Tools

### Queue & Scheduling
- **Laravel Queue** for async notifications and batch processing
- **Laravel Scheduler** for recurring tasks (reminders, checks, reports)
- Use Redis or database queues (not sync)

### Notifications
- **Laravel Mail** for email notifications
- **SMS** via Twilio (if implemented)
- In-app notifications via Livewire toast/modal

### Data Validation & Integrity
- **Laravel FormRequest** for input validation
- **Database transactions** for multi-step operations
- **Audit logging** using custom middleware or spatie/laravel-activitylog

### Testing
- **Pest Framework** for all tests (unit, feature, browser)
- **Database factories** for test data
- **Mocking** for external services (email, SMS)

---

## Budget & Resource Estimate

### Development Effort
- **Phase 1:** ~20 hours
- **Phase 2:** ~35 hours
- **Phase 3:** ~30 hours
- **Testing & QA:** ~15 hours
- **Documentation:** ~5 hours
- **Total:** ~105 hours (~3 developer-weeks at 40h/week)

### Dependencies & Costs
- No additional third-party tools required (using existing Laravel stack)
- SMS notifications (Twilio): $0.0075/SMS (opt-in, if implemented in Phase 2+)
- Enhanced logging: included in Laravel/Pest

---

## Go-Live Plan

### Week 13–14: Stabilization & Monitoring
- Monitor all Phase 3 features in production
- Collect feedback from team
- Adjust automation rules based on real-world performance
- Document lessons learned

### Ongoing (Post-Launch)
- Weekly KPI review
- Monthly accuracy audit
- Quarterly optimization (fine-tune thresholds, add new rules)
- User feedback surveys every 2 weeks

---

## Document Sign-Off & Approval

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Product Manager / Manager | ____________ | ____ | __________ |
| Lead Developer | ____________ | ____ | __________ |
| QA Lead | ____________ | ____ | __________ |

---

## Appendix

### A. Feature Toggles & Configuration
All automations will be controlled via `crm_settings` database table:

```php
// Example: Enable/disable per-phase features
auto_ticket_assignment = true/false
auto_booking_creation = true/false
maid_matching_enabled = true/false
finance_auto_invoice = true/false
```

### B. Rollback Procedure

If any feature fails accuracy threshold:

1. Set feature flag to `false` in settings
2. Revert database migrations (if any)
3. Restore from backup (if needed)
4. Notify manager + team
5. Post-mortem analysis
6. Retest and re-deploy

### C. Contact & Escalation

- **Questions/Blockers:** Developer team lead
- **Bug Reports:** Create ticket in system
- **Performance Issues:** Alert manager immediately
- **Data Loss:** Escalate to CTO/Admin

---

**Document Version:** 1.0  
**Created:** February 4, 2026  
**Last Updated:** February 4, 2026  
**Status:** Draft → Ready for Implementation  
