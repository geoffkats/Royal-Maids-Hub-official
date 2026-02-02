# Old Database Migration Plan

## Overview

This document outlines the strategy for migrating data from the old Royal Maids system database to the new v5.0 system.

## Source Data Analysis

### 1. Bookings Table (Old System)
- **Records**: ~28 bookings
- **Key Fields**: full_name, phone, email, status, service_tier, service_type, home details
- **Status Values**: pending, approved, rejected

### 2. Maids Table (Old System)
- **Records**: ~15 maids
- **Key Fields**: maid_code, first_name, last_name, nationality, phone, email, status, role
- **Status Values**: available, in-training, booked, deployed
- **Secondary Status**: booked, available, deployed, on-leave, absconded, terminated

### 3. Maid Evaluations Table (Old System)
- **Records**: ~17 evaluations
- **Key Fields**: trainer_id, trainee_name, observation_date, multiple skill scores
- **Evaluation Categories**: confidence, self_awareness, emotional_stability, punctuality, respect, cleaning, laundry, meals, childcare

## Field Mapping Strategy

### Bookings Migration

**Old → New Mapping:**
```
full_name → split to first_name + last_name
phone → phone
email → email
status (approved/rejected/pending) → status (confirmed/cancelled/pending)
service_tier (Silver/Gold/Platinum) → package_id (lookup)
service_type → service_type
home_type → property_type
bedrooms → bedrooms
bathrooms → bathrooms
city → city
division → state
parish → address (combined)
created_at → created_at
```

**Additional Processing:**
- Create lead for each booking using BookingToLeadService
- Map service_tier to package_id
- Handle approved → confirmed, rejected → cancelled

### Maids Migration

**Old → New Mapping:**
```
maid_id → (reference only)
maid_code → (store in notes)
first_name → first_name
last_name → last_name
nationality → nationality
date_of_birth → date_of_birth
email → email
phone → phone
profile_image → profile_picture
status → status (map values)
secondary_status → use as primary if more specific
nin_number → national_id
tribe → (store in additional_info JSON)
district → (store in additional_info JSON)
role → (store in notes)
created_at → created_at
```

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

### Evaluations Migration

**Old → New Mapping:**
```
trainer_id → trainer_id (if exists, else null)
trainee_name → lookup maid by name
observation_date → evaluation_date
confidence → (custom KPI or notes)
punctuality → punctuality (0-100 scale)
respect → professionalism
cleaning scores → quality_of_work
childcare scores → (average to single score)
```

**KPI Calculation:**
- Map old 1-5 scale to new 0-100 scale (multiply by 20)
- Average multiple sub-scores into single KPI scores

## Migration Steps

### Phase 1: Preparation
1. Backup new database
2. Create mapping tables for reference
3. Verify package IDs in new system
4. Create test migration script

### Phase 2: Maids Migration
1. Import maids first (no dependencies)
2. Map old maid_id to new id for reference
3. Handle profile images (copy files)
4. Set appropriate status values

### Phase 3: Bookings Migration
1. Import bookings
2. Create leads automatically using BookingToLeadService
3. Link bookings to leads
4. Map to packages where applicable

### Phase 4: Evaluations Migration
1. Match trainee names to maid IDs
2. Convert scores to new scale
3. Create evaluation records
4. Handle missing trainers (set to null)

### Phase 5: Verification
1. Count records (old vs new)
2. Spot-check data accuracy
3. Verify relationships
4. Test queries

## Implementation Files

### 1. Migration Command
**File**: `app/Console/Commands/MigrateOldDatabase.php`
- Reads SQL files
- Processes data
- Inserts into new tables
- Logs progress

### 2. Seeder (Alternative)
**File**: `database/seeders/OldDataSeeder.php`
- Parses SQL INSERT statements
- Maps fields
- Seeds data

### 3. Mapping Service
**File**: `app/Services/OldDataMappingService.php`
- Field mapping logic
- Status conversions
- Data transformations

## Data Challenges & Solutions

### Challenge 1: Name Splitting
**Problem**: Old bookings have full_name, new system has first_name + last_name
**Solution**: Split on first space, handle edge cases

### Challenge 2: Status Values
**Problem**: Different status enums between systems
**Solution**: Create mapping array, handle unknowns as 'pending'

### Challenge 3: Package Mapping
**Problem**: Old service_tier text, new package_id integer
**Solution**: Lookup package by name, create if missing

### Challenge 4: Trainer Matching
**Problem**: Old trainer_id may not exist in new system
**Solution**: Set to null, create manual mapping if needed

### Challenge 5: Evaluation Scores
**Problem**: Different scoring scales (1-5 vs 0-100)
**Solution**: Multiply by 20, handle nulls

## Rollback Plan

If migration fails:
1. Restore database backup
2. Review error logs
3. Fix mapping issues
4. Re-run migration

## Next Steps

1. Review this plan
2. Create migration command
3. Test on development database
4. Run on production with backup
5. Verify data integrity
