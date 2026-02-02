# Old Database Migration Guide

## Quick Start

```bash
# 1. Dry run to test (no data inserted)
php artisan migrate:old-database --dry-run

# 2. Migrate only maids
php artisan migrate:old-database --maids

# 3. Migrate only bookings
php artisan migrate:old-database --bookings

# 4. Migrate only evaluations
php artisan migrate:old-database --evaluations

# 5. Migrate everything
php artisan migrate:old-database
```

## Pre-Migration Checklist

- [ ] Backup current database
- [ ] Verify SQL files are in `old database/` folder
- [ ] Ensure packages exist (Silver, Gold, Platinum)
- [ ] Test on development environment first
- [ ] Review field mappings in migration plan

## Data Mapping Details

### Maids Migration

**Source File**: `old database/maids (1).sql`  
**Target Table**: `maids`  
**Records**: ~15 maids

**Field Mappings:**
| Old Field | New Field | Transformation |
|-----------|-----------|----------------|
| maid_id | (reference only) | Stored in mapping array |
| maid_code | documents JSON | Stored for reference |
| first_name | first_name | Direct |
| last_name | last_name | Direct |
| nationality | nationality | Direct |
| date_of_birth | date_of_birth | Null if '0000-00-00' |
| email | email | Direct, nullable |
| phone | phone | Direct |
| profile_image | profile_picture | Direct |
| status | status | Mapped (see below) |
| secondary_status | status | Used if more specific |
| nin_number | documents JSON | Stored in JSON |
| date_of_arrival | hire_date | Null if '0000-00-00' |

**Status Mapping:**
```
available → available
in-training → training
booked → available (with note)
deployed → deployed
on-leave → on_leave
absconded → terminated
terminated → terminated
```

**Additional Data Stored in JSON:**
- maid_code
- nin_number
- passport_number
- tribe
- district
- role

### Bookings Migration

**Source File**: `old database/bookings.sql`  
**Target Table**: `bookings`  
**Records**: ~28 bookings

**Field Mappings:**
| Old Field | New Field | Transformation |
|-----------|-----------|----------------|
| full_name | first_name + last_name | Split on first space |
| phone | phone | Direct |
| email | email | Direct |
| country | country | Default 'Uganda' if empty |
| city | city | Direct |
| division | state | Direct |
| parish | address | Direct |
| home_type | property_type | Mapped (see below) |
| bedrooms | bedrooms | Integer |
| bathrooms | bathrooms | Integer |
| status | status | Mapped (see below) |
| service_tier | package_id | Lookup by name |
| anything_else | special_instructions | Direct |
| created_at | created_at | Direct |

**Status Mapping:**
```
approved → confirmed
rejected → cancelled
pending → pending
```

**Property Type Mapping:**
```
Apartment → apartment
Bungalow → house
Maisonette → villa
(default) → house
```

**Automatic Lead Creation:**
- Each booking automatically creates a lead using `BookingToLeadService`
- Duplicate detection prevents duplicate leads
- Booking is linked to lead via `lead_id`
- If lead already converted to client, also links to `client_id`

### Evaluations Migration

**Source File**: `old database/maid_evaluations (1).sql`  
**Target Table**: `evaluations`  
**Records**: ~17 evaluations

**Field Mappings:**
| Old Field | New Field | Transformation |
|-----------|-----------|----------------|
| trainee_name | maid_id | Lookup by name |
| trainer_id | trainer_id | Set to null (manual mapping needed) |
| observation_date | evaluation_date | Direct |
| confidence | communication | Scale 1-5 to 0-100 |
| punctuality | punctuality | Scale 1-5 to 0-100 |
| respect | professionalism | Scale 1-5 to 0-100 |
| ownership | reliability | Scale 1-5 to 0-100 |
| growth_mindset | initiative | Scale 1-5 to 0-100 |
| cleaning | quality_of_work | Scale 1-5 to 0-100 |
| cleaning | attention_to_detail | Scale 1-5 to 0-100 |
| punctuality | time_management | Scale 1-5 to 0-100 |
| facilitator | notes | Stored in notes |

**Score Conversion:**
```
Old Scale: 1-5
New Scale: 0-100
Formula: old_score × 20
```

**Trainee Name Matching:**
- Searches for maid by first_name or last_name
- Uses LIKE query for partial matches
- Skips evaluation if no match found
- Logs warning for unmatched evaluations

## Migration Process

### Step 1: Preparation

```bash
# Backup database
mysqldump -u root -p royalmaids_v5 > backup_before_migration.sql

# Verify SQL files exist
ls "old database/"
# Should show:
# - bookings.sql
# - maids (1).sql
# - maid_evaluations (1).sql
```

### Step 2: Test Migration (Dry Run)

```bash
# Test without inserting data
php artisan migrate:old-database --dry-run
```

**Expected Output:**
```
🚀 Starting Old Database Migration...
⚠️  DRY RUN MODE - No data will be inserted

📋 Migrating Maids...
 15/15 [============================] 100%

📋 Migrating Bookings...
 28/28 [============================] 100%

📋 Migrating Evaluations...
 17/17 [============================] 100%

📊 Migration Summary:
+-------------+-------+---------+--------+
| Entity      | Total | Success | Failed |
+-------------+-------+---------+--------+
| Maids       | 15    | 15      | 0      |
| Bookings    | 28    | 28      | 0      |
| Evaluations | 17    | 17      | 0      |
+-------------+-------+---------+--------+

⚠️  This was a DRY RUN - no data was actually inserted
```

### Step 3: Migrate Maids First

```bash
# Migrate maids (they have no dependencies)
php artisan migrate:old-database --maids
```

**What Happens:**
1. Reads `old database/maids (1).sql`
2. Parses INSERT statements
3. Maps old fields to new fields
4. Converts status values
5. Stores additional data in JSON
6. Creates maid records
7. Stores old_id → new_id mapping

### Step 4: Migrate Bookings

```bash
# Migrate bookings (creates leads automatically)
php artisan migrate:old-database --bookings
```

**What Happens:**
1. Reads `old database/bookings.sql`
2. Splits full_name into first_name + last_name
3. Looks up package by service_tier
4. Creates lead using BookingToLeadService
5. Links booking to lead
6. Handles duplicate detection
7. Creates booking records

### Step 5: Migrate Evaluations

```bash
# Migrate evaluations (requires maids to exist)
php artisan migrate:old-database --evaluations
```

**What Happens:**
1. Reads `old database/maid_evaluations (1).sql`
2. Matches trainee_name to maid
3. Converts 1-5 scores to 0-100 scale
4. Maps evaluation fields
5. Creates evaluation records
6. Logs warnings for unmatched trainees

### Step 6: Verification

```sql
-- Count records
SELECT 'maids' as table_name, COUNT(*) as count FROM maids
UNION ALL
SELECT 'bookings', COUNT(*) FROM bookings
UNION ALL
SELECT 'evaluations', COUNT(*) FROM evaluations
UNION ALL
SELECT 'crm_leads', COUNT(*) FROM crm_leads;

-- Check maid statuses
SELECT status, COUNT(*) FROM maids GROUP BY status;

-- Check booking statuses
SELECT status, COUNT(*) FROM bookings GROUP BY status;

-- Check lead-booking linkage
SELECT COUNT(*) FROM bookings WHERE lead_id IS NOT NULL;

-- Check evaluations with maids
SELECT COUNT(*) FROM evaluations 
INNER JOIN maids ON evaluations.maid_id = maids.id;
```

## Troubleshooting

### Issue: "File not found"

**Problem**: SQL files not in correct location

**Solution**:
```bash
# Ensure files are in project root
ls "old database/"

# If not, move them
mv /path/to/old/files/* "old database/"
```

### Issue: "Could not parse SQL file"

**Problem**: SQL file format not recognized

**Solution**:
- Verify SQL file is valid
- Check for syntax errors
- Ensure it's a phpMyAdmin export
- Try re-exporting from phpMyAdmin

### Issue: "Package not found"

**Problem**: service_tier doesn't match any package

**Solution**:
```bash
# Create missing packages
php artisan tinker

>>> Package::create(['name' => 'Silver', 'slug' => 'silver', 'base_price' => 15000]);
>>> Package::create(['name' => 'Gold', 'slug' => 'gold', 'base_price' => 25000]);
>>> Package::create(['name' => 'Platinum', 'slug' => 'platinum', 'base_price' => 40000]);
```

### Issue: "Could not find maid for evaluation"

**Problem**: Trainee name doesn't match any maid

**Solution**:
- Review warning messages
- Manually create maid if needed
- Or skip evaluation (it will be logged)

### Issue: "Duplicate entry"

**Problem**: Record already exists

**Solution**:
```bash
# Clear tables and re-run
php artisan tinker

>>> DB::table('evaluations')->truncate();
>>> DB::table('bookings')->truncate();
>>> DB::table('maids')->truncate();
>>> DB::table('crm_leads')->truncate();
```

## Post-Migration Tasks

### 1. Verify Data Integrity

```bash
# Check for orphaned records
php artisan tinker

>>> Booking::whereNull('lead_id')->count();  // Should be 0
>>> Evaluation::whereNull('maid_id')->count();  // Should be 0
```

### 2. Update Package Assignments

```bash
# Assign packages to clients if needed
php artisan tinker

>>> Client::whereNull('package_id')->update(['package_id' => 1]);  // Default to Silver
```

### 3. Review Maid Statuses

```bash
# Check maid status distribution
php artisan tinker

>>> Maid::select('status', DB::raw('count(*) as count'))
       ->groupBy('status')
       ->get();
```

### 4. Link Trainers to Evaluations

```bash
# Manually assign trainers if needed
php artisan tinker

>>> Evaluation::whereNull('trainer_id')
             ->update(['trainer_id' => 1]);  // Assign to default trainer
```

### 5. Copy Profile Images

```bash
# Copy maid profile images from old system
cp -r /path/to/old/uploads/* storage/app/public/
php artisan storage:link
```

## Rollback Instructions

If migration fails or data is incorrect:

```bash
# 1. Restore database backup
mysql -u root -p royalmaids_v5 < backup_before_migration.sql

# 2. Review error logs
tail -f storage/logs/laravel.log

# 3. Fix issues in migration command

# 4. Re-run migration
php artisan migrate:old-database --dry-run  # Test first
php artisan migrate:old-database  # Then run for real
```

## Best Practices

1. **Always test first**: Use `--dry-run` before actual migration
2. **Backup everything**: Database and files
3. **Migrate incrementally**: Use `--maids`, `--bookings`, `--evaluations` flags
4. **Verify each step**: Check counts and data after each migration
5. **Keep old data**: Don't delete old database until verified
6. **Document changes**: Note any manual adjustments made
7. **Test thoroughly**: Verify relationships and data integrity

## Success Criteria

Migration is successful when:

- [ ] All maid records imported (15 expected)
- [ ] All booking records imported (28 expected)
- [ ] All evaluation records imported (17 expected)
- [ ] All bookings linked to leads
- [ ] All evaluations linked to maids
- [ ] No orphaned records
- [ ] Status values correctly mapped
- [ ] Scores correctly scaled (0-100)
- [ ] Profile images accessible
- [ ] Packages correctly assigned

## Support

If you encounter issues:

1. Check error logs: `storage/logs/laravel.log`
2. Review migration command: `app/Console/Commands/MigrateOldDatabase.php`
3. Verify SQL file format
4. Check database permissions
5. Ensure all dependencies installed

---

**Migration Created**: 2024  
**Last Updated**: 2024  
**Version**: 1.0
