# Old Database Migration - Summary

## Overview

Complete migration solution for importing data from old Royal Maids system into new v5.0 system.

## Files Created

### 1. Migration Plan
**File**: `docs/OLD_DATABASE_MIGRATION_PLAN.md`
- Data analysis and field mappings
- Migration strategy and phases
- Challenges and solutions
- Rollback plan

### 2. Migration Command
**File**: `app/Console/Commands/MigrateOldDatabase.php`
- Automated migration script
- Parses SQL files
- Maps and transforms data
- Progress tracking and error handling

### 3. Migration Guide
**File**: `docs/OLD_DATABASE_MIGRATION_GUIDE.md`
- Step-by-step instructions
- Detailed field mappings
- Troubleshooting guide
- Verification queries

## Quick Start

```bash
# 1. Test migration (no data inserted)
php artisan migrate:old-database --dry-run

# 2. Run full migration
php artisan migrate:old-database

# 3. Or migrate incrementally
php artisan migrate:old-database --maids
php artisan migrate:old-database --bookings
php artisan migrate:old-database --evaluations
```

## Data to Migrate

### Maids (15 records)
- Basic information (name, phone, email)
- Status and availability
- Profile pictures
- Documents (NIN, passport)
- Hire dates

### Bookings (28 records)
- Customer information
- Service details
- Property information
- Status (approved/rejected/pending)
- Package tier (Silver/Gold/Platinum)
- **Automatically creates leads**

### Evaluations (17 records)
- Trainer assessments
- Multiple skill scores
- Observation dates
- Performance metrics
- **Converts 1-5 scale to 0-100**

## Key Features

### 1. Automatic Lead Creation
- Each booking creates a lead via `BookingToLeadService`
- Duplicate detection prevents duplicate leads
- Bookings automatically linked to leads
- Maintains data integrity

### 2. Smart Field Mapping
- Splits full names into first/last
- Maps old status values to new enums
- Converts property types
- Looks up packages by name

### 3. Score Conversion
- Old evaluations use 1-5 scale
- New system uses 0-100 scale
- Automatic conversion (multiply by 20)
- Handles null values

### 4. Status Mapping
**Maids:**
- available → available
- in-training → training
- deployed → deployed
- on-leave → on_leave
- absconded/terminated → terminated

**Bookings:**
- approved → confirmed
- rejected → cancelled
- pending → pending

### 5. Progress Tracking
- Progress bars for each entity
- Success/failure counts
- Error logging
- Summary report

## Migration Process

### Phase 1: Preparation
1. Backup database
2. Verify SQL files in `old database/` folder
3. Ensure packages exist (Silver, Gold, Platinum)
4. Test on development first

### Phase 2: Dry Run
```bash
php artisan migrate:old-database --dry-run
```
- Tests parsing and mapping
- No data inserted
- Shows what would happen
- Identifies issues early

### Phase 3: Execute
```bash
# Option A: All at once
php artisan migrate:old-database

# Option B: Incremental
php artisan migrate:old-database --maids
php artisan migrate:old-database --bookings
php artisan migrate:old-database --evaluations
```

### Phase 4: Verification
```sql
-- Count records
SELECT 'maids', COUNT(*) FROM maids
UNION ALL SELECT 'bookings', COUNT(*) FROM bookings
UNION ALL SELECT 'evaluations', COUNT(*) FROM evaluations
UNION ALL SELECT 'leads', COUNT(*) FROM crm_leads;

-- Check linkages
SELECT COUNT(*) FROM bookings WHERE lead_id IS NOT NULL;
SELECT COUNT(*) FROM evaluations WHERE maid_id IS NOT NULL;
```

## Expected Results

After successful migration:

| Entity | Expected Count | Creates |
|--------|---------------|---------|
| Maids | 15 | 15 maid records |
| Bookings | 28 | 28 bookings + 28 leads |
| Evaluations | 17 | 17 evaluation records |
| **Total** | **60** | **88 records** |

## Data Transformations

### Name Splitting
```
"kato Geoffrey" → first_name: "kato", last_name: "Geoffrey"
"Victoria" → first_name: "Victoria", last_name: "User"
```

### Status Mapping
```
Old: "approved" → New: "confirmed"
Old: "in-training" → New: "training"
Old: "absconded" → New: "terminated"
```

### Score Scaling
```
Old: 5 (excellent) → New: 100
Old: 4 (good) → New: 80
Old: 3 (average) → New: 60
Old: 2 (poor) → New: 40
Old: 1 (very poor) → New: 20
```

### Package Lookup
```
"Silver" → package_id: 1
"Gold" → package_id: 2
"Platinum" → package_id: 3
```

## Troubleshooting

### Common Issues

**1. File Not Found**
```bash
# Ensure files are in correct location
ls "old database/"
```

**2. Package Not Found**
```bash
# Create missing packages
php artisan tinker
>>> Package::create(['name' => 'Silver', 'slug' => 'silver', 'base_price' => 15000]);
```

**3. Duplicate Entry**
```bash
# Clear tables and re-run
php artisan tinker
>>> DB::table('maids')->truncate();
>>> DB::table('bookings')->truncate();
>>> DB::table('evaluations')->truncate();
```

**4. Maid Not Found for Evaluation**
- Check warning messages
- Manually create maid if needed
- Or skip evaluation (logged)

## Post-Migration Tasks

### 1. Copy Profile Images
```bash
cp -r /path/to/old/uploads/* storage/app/public/
php artisan storage:link
```

### 2. Assign Trainers to Evaluations
```bash
php artisan tinker
>>> Evaluation::whereNull('trainer_id')->update(['trainer_id' => 1]);
```

### 3. Update Client Packages
```bash
php artisan tinker
>>> Client::whereNull('package_id')->update(['package_id' => 1]);
```

### 4. Verify Relationships
```bash
# Check for orphaned records
php artisan tinker
>>> Booking::whereNull('lead_id')->count();  // Should be 0
>>> Evaluation::whereNull('maid_id')->count();  // Should be 0
```

## Rollback

If migration fails:

```bash
# 1. Restore backup
mysql -u root -p royalmaids_v5 < backup_before_migration.sql

# 2. Fix issues

# 3. Re-run
php artisan migrate:old-database --dry-run
php artisan migrate:old-database
```

## Success Checklist

- [ ] All 15 maids imported
- [ ] All 28 bookings imported
- [ ] All 17 evaluations imported
- [ ] 28 leads created automatically
- [ ] All bookings linked to leads
- [ ] All evaluations linked to maids
- [ ] Status values correctly mapped
- [ ] Scores converted to 0-100 scale
- [ ] Packages correctly assigned
- [ ] No orphaned records
- [ ] Profile images accessible
- [ ] Data verified with queries

## Benefits

1. **Automated**: Single command migrates everything
2. **Safe**: Dry-run mode tests before inserting
3. **Flexible**: Migrate all or incrementally
4. **Traceable**: Progress bars and error logging
5. **Intelligent**: Automatic lead creation and duplicate detection
6. **Reversible**: Backup and rollback procedures
7. **Documented**: Comprehensive guides and mappings

## Next Steps

1. **Review** migration plan and guide
2. **Backup** current database
3. **Test** with dry-run
4. **Execute** migration
5. **Verify** data integrity
6. **Complete** post-migration tasks
7. **Test** application functionality

---

**Created**: 2024  
**Status**: Ready for execution  
**Estimated Time**: 5-10 minutes  
**Risk Level**: Low (with backup)
