# Old Database Migration - Checklist

## Pre-Migration Checklist

### Environment Preparation
- [ ] Development environment is ready
- [ ] Database connection is working
- [ ] All dependencies installed (`composer install`)
- [ ] Laravel application is running
- [ ] Storage permissions are correct

### Backup & Safety
- [ ] Current database backed up
  ```bash
  mysqldump -u root -p royalmaids_v5 > backup_$(date +%Y%m%d_%H%M%S).sql
  ```
- [ ] Backup file verified (can be opened)
- [ ] Backup stored in safe location
- [ ] Rollback procedure documented

### Source Data Verification
- [ ] SQL files located in `old database/` folder
- [ ] `bookings.sql` file exists (15.7 KB)
- [ ] `maids (1).sql` file exists (18.2 KB)
- [ ] `maid_evaluations (1).sql` file exists (49.4 KB)
- [ ] SQL files can be opened and read
- [ ] SQL files contain INSERT statements

### System Requirements
- [ ] Packages exist in database:
  ```bash
  php artisan tinker
  >>> Package::all()->pluck('name');
  # Should show: Silver, Gold, Platinum
  ```
- [ ] Migration command exists:
  ```bash
  php artisan list | grep migrate:old-database
  ```
- [ ] BookingToLeadService is available
- [ ] DuplicateDetectionService is available

---

## Migration Execution Checklist

### Step 1: Dry Run Test
- [ ] Run dry-run command:
  ```bash
  php artisan migrate:old-database --dry-run
  ```
- [ ] Review output for errors
- [ ] Verify expected counts:
  - [ ] Maids: 15 total
  - [ ] Bookings: 28 total
  - [ ] Evaluations: 17 total
- [ ] Check for warnings or issues
- [ ] Confirm no data was inserted (dry-run mode)

### Step 2: Migrate Maids
- [ ] Run maids migration:
  ```bash
  php artisan migrate:old-database --maids
  ```
- [ ] Progress bar completed successfully
- [ ] Check success count: 15/15
- [ ] Verify in database:
  ```sql
  SELECT COUNT(*) FROM maids;  -- Should be 15
  ```
- [ ] Spot-check a few maid records
- [ ] Verify status values are correct
- [ ] Check profile_picture paths

### Step 3: Migrate Bookings
- [ ] Run bookings migration:
  ```bash
  php artisan migrate:old-database --bookings
  ```
- [ ] Progress bar completed successfully
- [ ] Check success count: 28/28
- [ ] Verify in database:
  ```sql
  SELECT COUNT(*) FROM bookings;  -- Should be 28
  SELECT COUNT(*) FROM crm_leads;  -- Should be 28
  ```
- [ ] Check bookings are linked to leads:
  ```sql
  SELECT COUNT(*) FROM bookings WHERE lead_id IS NOT NULL;  -- Should be 28
  ```
- [ ] Verify package assignments
- [ ] Check status values (confirmed/cancelled/pending)

### Step 4: Migrate Evaluations
- [ ] Run evaluations migration:
  ```bash
  php artisan migrate:old-database --evaluations
  ```
- [ ] Progress bar completed successfully
- [ ] Check success count (may be less than 17 if some maids not found)
- [ ] Review warnings for unmatched trainees
- [ ] Verify in database:
  ```sql
  SELECT COUNT(*) FROM evaluations;
  ```
- [ ] Check evaluations are linked to maids:
  ```sql
  SELECT COUNT(*) FROM evaluations WHERE maid_id IS NOT NULL;
  ```
- [ ] Verify scores are in 0-100 range
- [ ] Check evaluation dates

---

## Post-Migration Verification

### Data Integrity Checks

#### Record Counts
- [ ] Maids count matches expected (15)
- [ ] Bookings count matches expected (28)
- [ ] Evaluations count matches expected (~17)
- [ ] Leads created automatically (28)

#### Relationship Verification
- [ ] All bookings have lead_id:
  ```sql
  SELECT COUNT(*) FROM bookings WHERE lead_id IS NULL;  -- Should be 0
  ```
- [ ] All evaluations have maid_id:
  ```sql
  SELECT COUNT(*) FROM evaluations WHERE maid_id IS NULL;  -- Should be 0
  ```
- [ ] Leads have correct source:
  ```sql
  SELECT COUNT(*) FROM crm_leads WHERE source = 'old_system_migration';  -- Should be 28
  ```

#### Data Quality Checks
- [ ] Maid statuses are valid:
  ```sql
  SELECT status, COUNT(*) FROM maids GROUP BY status;
  ```
- [ ] Booking statuses are valid:
  ```sql
  SELECT status, COUNT(*) FROM bookings GROUP BY status;
  ```
- [ ] Evaluation scores are in range (0-100):
  ```sql
  SELECT MIN(punctuality), MAX(punctuality) FROM evaluations;
  ```
- [ ] No NULL values in required fields:
  ```sql
  SELECT COUNT(*) FROM maids WHERE first_name IS NULL OR last_name IS NULL;  -- Should be 0
  SELECT COUNT(*) FROM bookings WHERE phone IS NULL OR email IS NULL;  -- Should be 0
  ```

#### Spot Checks
- [ ] Check specific maid record (e.g., "Semmy Adong"):
  ```sql
  SELECT * FROM maids WHERE first_name LIKE '%Semmy%';
  ```
- [ ] Check specific booking (e.g., "kato Geoffrey"):
  ```sql
  SELECT * FROM bookings WHERE first_name LIKE '%kato%';
  ```
- [ ] Check specific evaluation:
  ```sql
  SELECT * FROM evaluations LIMIT 1;
  ```
- [ ] Verify lead-booking linkage:
  ```sql
  SELECT b.id, b.first_name, b.last_name, l.id as lead_id, l.first_name, l.last_name
  FROM bookings b
  INNER JOIN crm_leads l ON b.lead_id = l.id
  LIMIT 5;
  ```

---

## Post-Migration Tasks

### File Management
- [ ] Copy profile images from old system:
  ```bash
  cp -r /path/to/old/uploads/* storage/app/public/
  ```
- [ ] Verify storage link exists:
  ```bash
  php artisan storage:link
  ```
- [ ] Test profile image URLs in browser
- [ ] Check image file permissions

### Data Cleanup
- [ ] Assign trainers to evaluations (if needed):
  ```bash
  php artisan tinker
  >>> Evaluation::whereNull('trainer_id')->update(['trainer_id' => 1]);
  ```
- [ ] Update client packages (if needed):
  ```bash
  >>> Client::whereNull('package_id')->update(['package_id' => 1]);
  ```
- [ ] Review and fix any data anomalies

### Application Testing
- [ ] Test maid list page (`/maids`)
- [ ] Test maid detail page
- [ ] Test booking list page (`/bookings`)
- [ ] Test booking detail page
- [ ] Test lead list page (`/crm/leads`)
- [ ] Test lead detail page
- [ ] Test evaluation display
- [ ] Test search functionality
- [ ] Test filters and sorting

---

## Documentation & Cleanup

### Documentation
- [ ] Document any manual adjustments made
- [ ] Note any data issues encountered
- [ ] Record final statistics:
  - Maids migrated: ___
  - Bookings migrated: ___
  - Evaluations migrated: ___
  - Leads created: ___
- [ ] Update team on migration completion

### Cleanup
- [ ] Archive old SQL files (don't delete yet)
- [ ] Keep backup file for 30 days
- [ ] Remove dry-run test data (if any)
- [ ] Clear Laravel cache:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  ```

---

## Rollback Checklist (If Needed)

### When to Rollback
- [ ] Critical data loss detected
- [ ] Major relationship errors
- [ ] Application not functioning
- [ ] Data corruption identified

### Rollback Steps
1. [ ] Stop application
2. [ ] Restore database backup:
   ```bash
   mysql -u root -p royalmaids_v5 < backup_YYYYMMDD_HHMMSS.sql
   ```
3. [ ] Verify restoration:
   ```sql
   SELECT COUNT(*) FROM maids;
   SELECT COUNT(*) FROM bookings;
   SELECT COUNT(*) FROM evaluations;
   ```
4. [ ] Review error logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```
5. [ ] Identify and fix issues
6. [ ] Re-test with dry-run
7. [ ] Re-execute migration

---

## Success Criteria

### All Checks Passed
- [ ] All 15 maids imported successfully
- [ ] All 28 bookings imported successfully
- [ ] All evaluations imported (with acceptable match rate)
- [ ] 28 leads created automatically
- [ ] All relationships intact (no orphaned records)
- [ ] Status values correctly mapped
- [ ] Scores converted to 0-100 scale
- [ ] Packages correctly assigned
- [ ] Profile images accessible
- [ ] Application functioning normally
- [ ] No critical errors in logs
- [ ] Team notified and trained

### Final Sign-Off
- [ ] Migration reviewed by: _______________
- [ ] Date completed: _______________
- [ ] Issues encountered: _______________
- [ ] Resolution notes: _______________
- [ ] Approved by: _______________

---

## Quick Reference Commands

```bash
# Backup database
mysqldump -u root -p royalmaids_v5 > backup_$(date +%Y%m%d_%H%M%S).sql

# Dry run (test)
php artisan migrate:old-database --dry-run

# Migrate all
php artisan migrate:old-database

# Migrate incrementally
php artisan migrate:old-database --maids
php artisan migrate:old-database --bookings
php artisan migrate:old-database --evaluations

# Verify counts
php artisan tinker
>>> Maid::count();
>>> Booking::count();
>>> Evaluation::count();
>>> CrmLead::count();

# Check relationships
>>> Booking::whereNull('lead_id')->count();  // Should be 0
>>> Evaluation::whereNull('maid_id')->count();  // Should be 0

# Restore backup (if needed)
mysql -u root -p royalmaids_v5 < backup_YYYYMMDD_HHMMSS.sql
```

---

**Checklist Version**: 1.0  
**Last Updated**: 2024  
**Estimated Time**: 30-60 minutes  
**Difficulty**: Medium
