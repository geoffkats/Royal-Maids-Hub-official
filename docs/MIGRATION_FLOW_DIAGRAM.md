# Old Database Migration - Flow Diagram

## Overall Migration Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    OLD ROYAL MAIDS DATABASE                      │
│                                                                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   bookings   │  │    maids     │  │ evaluations  │          │
│  │  (28 records)│  │ (15 records) │  │ (17 records) │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ├─── Read SQL Files
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              MIGRATION COMMAND (MigrateOldDatabase)              │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  1. Parse SQL INSERT statements                          │  │
│  │  2. Map old fields to new fields                         │  │
│  │  3. Transform data (status, scores, names)               │  │
│  │  4. Validate and clean data                              │  │
│  │  5. Create records in new database                       │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ├─── Insert Data
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                 NEW ROYAL MAIDS HUB V5.0 DATABASE                │
│                                                                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   bookings   │  │    maids     │  │ evaluations  │          │
│  │  (28 records)│  │ (15 records) │  │ (17 records) │          │
│  └──────┬───────┘  └──────────────┘  └──────┬───────┘          │
│         │                                     │                  │
│         │          ┌──────────────┐          │                  │
│         └─────────▶│  crm_leads   │          │                  │
│                    │  (28 records)│          │                  │
│                    └──────────────┘          │                  │
│                                               │                  │
│                    ┌──────────────┐          │                  │
│                    │   clients    │◀─────────┘                  │
│                    │  (if exists) │                             │
│                    └──────────────┘                             │
└─────────────────────────────────────────────────────────────────┘
```

## Detailed Migration Phases

### Phase 1: Maids Migration

```
OLD maids TABLE
┌─────────────────────────────────────────┐
│ maid_id: 40                             │
│ maid_code: "M005"                       │
│ first_name: "Semmy"                     │
│ last_name: "Adong"                      │
│ status: "deployed"                      │
│ secondary_status: "deployed"            │
│ nin_number: "CF02115100EX4K"            │
│ profile_image: "uploads/maid_40.jpeg"   │
└─────────────────────────────────────────┘
            │
            │ Map & Transform
            ▼
NEW maids TABLE
┌─────────────────────────────────────────┐
│ id: 1 (auto-generated)                  │
│ first_name: "Semmy"                     │
│ last_name: "Adong"                      │
│ status: "deployed"                      │
│ profile_picture: "uploads/maid_40.jpeg" │
│ documents: {                            │
│   "maid_code": "M005",                  │
│   "nin_number": "CF02115100EX4K"        │
│ }                                       │
└─────────────────────────────────────────┘
```

### Phase 2: Bookings Migration (with Lead Creation)

```
OLD bookings TABLE
┌─────────────────────────────────────────┐
│ id: 4                                   │
│ status: "approved"                      │
│ full_name: "kato Geoffrey"              │
│ phone: "0751801685"                     │
│ email: "katogeoffreyg@gmail.com"        │
│ service_tier: "Silver"                  │
│ home_type: "Bungalow"                   │
│ bedrooms: 2                             │
│ bathrooms: 2                            │
└─────────────────────────────────────────┘
            │
            │ Split Name & Map Fields
            ▼
┌─────────────────────────────────────────┐
│ first_name: "kato"                      │
│ last_name: "Geoffrey"                   │
│ phone: "0751801685"                     │
│ email: "katogeoffreyg@gmail.com"        │
│ package_id: 1 (Silver lookup)           │
│ property_type: "house"                  │
│ status: "confirmed"                     │
└─────────────────────────────────────────┘
            │
            │ Create Lead via BookingToLeadService
            ▼
NEW crm_leads TABLE
┌─────────────────────────────────────────┐
│ id: 1 (auto-generated)                  │
│ first_name: "kato"                      │
│ last_name: "Geoffrey"                   │
│ phone: "0751801685"                     │
│ email: "katogeoffreyg@gmail.com"        │
│ status: "new"                           │
│ source: "old_system_migration"          │
└─────────────────────────────────────────┘
            │
            │ Link Booking to Lead
            ▼
NEW bookings TABLE
┌─────────────────────────────────────────┐
│ id: 1 (auto-generated)                  │
│ lead_id: 1 ◀──────────────┐             │
│ client_id: NULL            │             │
│ first_name: "kato"         │             │
│ last_name: "Geoffrey"      │             │
│ phone: "0751801685"        │             │
│ email: "katogeoffreyg@gmail.com"        │
│ package_id: 1              │             │
│ property_type: "house"     │             │
│ bedrooms: 2                │             │
│ bathrooms: 2               │             │
│ status: "confirmed"        │             │
└─────────────────────────────────────────┘
```

### Phase 3: Evaluations Migration

```
OLD maid_evaluations TABLE
┌─────────────────────────────────────────┐
│ id: 42                                  │
│ trainee_name: "Simmie Adong"            │
│ observation_date: "2025-08-13"          │
│ confidence: 4 (1-5 scale)               │
│ punctuality: 2 (1-5 scale)              │
│ respect: 4 (1-5 scale)                  │
│ cleaning: 3 (1-5 scale)                 │
│ facilitator: "Harriet Patience..."      │
└─────────────────────────────────────────┘
            │
            │ 1. Lookup Maid by Name
            │ 2. Convert Scores (×20)
            │ 3. Map Fields
            ▼
NEW evaluations TABLE
┌─────────────────────────────────────────┐
│ id: 1 (auto-generated)                  │
│ maid_id: 1 (matched "Semmy Adong")      │
│ trainer_id: NULL                        │
│ evaluation_date: "2025-08-13"           │
│ punctuality: 40 (2×20)                  │
│ professionalism: 80 (4×20)              │
│ quality_of_work: 60 (3×20)              │
│ communication: 80 (4×20)                │
│ reliability: 60                         │
│ initiative: 60                          │
│ attention_to_detail: 60                 │
│ time_management: 40                     │
│ notes: "Migrated from old system..."    │
└─────────────────────────────────────────┘
```

## Data Transformation Examples

### Status Mapping

```
┌──────────────┐         ┌──────────────┐
│ OLD STATUS   │   ───▶  │  NEW STATUS  │
├──────────────┤         ├──────────────┤
│ approved     │   ───▶  │ confirmed    │
│ rejected     │   ───▶  │ cancelled    │
│ pending      │   ───▶  │ pending      │
│ in-training  │   ───▶  │ training     │
│ deployed     │   ───▶  │ deployed     │
│ on-leave     │   ───▶  │ on_leave     │
│ absconded    │   ───▶  │ terminated   │
│ terminated   │   ───▶  │ terminated   │
└──────────────┘         └──────────────┘
```

### Score Conversion

```
┌──────────────┐         ┌──────────────┐
│ OLD (1-5)    │   ───▶  │  NEW (0-100) │
├──────────────┤         ├──────────────┤
│      5       │   ───▶  │     100      │
│      4       │   ───▶  │      80      │
│      3       │   ───▶  │      60      │
│      2       │   ───▶  │      40      │
│      1       │   ───▶  │      20      │
│      0       │   ───▶  │       0      │
└──────────────┘         └──────────────┘
         Formula: old_score × 20
```

### Name Splitting

```
┌─────────────────────┐         ┌──────────────────────┐
│ OLD (full_name)     │   ───▶  │  NEW (split)         │
├─────────────────────┤         ├──────────────────────┤
│ "kato Geoffrey"     │   ───▶  │ first: "kato"        │
│                     │         │ last: "Geoffrey"     │
├─────────────────────┤         ├──────────────────────┤
│ "Victoria"          │   ───▶  │ first: "Victoria"    │
│                     │         │ last: "User"         │
├─────────────────────┤         ├──────────────────────┤
│ "Josephine Nalugo"  │   ───▶  │ first: "Josephine"   │
│                     │         │ last: "Nalugo"       │
└─────────────────────┘         └──────────────────────┘
     Split on first space, default last name if none
```

## Command Execution Flow

```
┌────────────────────────────────────────────────────────────┐
│  php artisan migrate:old-database [options]                │
└────────────────────────────────────────────────────────────┘
                        │
                        ▼
┌────────────────────────────────────────────────────────────┐
│  Check Options:                                            │
│  • --dry-run: Test mode (no inserts)                       │
│  • --maids: Only migrate maids                             │
│  • --bookings: Only migrate bookings                       │
│  • --evaluations: Only migrate evaluations                 │
│  • (none): Migrate all                                     │
└────────────────────────────────────────────────────────────┘
                        │
                        ▼
┌────────────────────────────────────────────────────────────┐
│  FOR EACH ENTITY:                                          │
│                                                             │
│  1. Read SQL file                                          │
│     ├─ Parse INSERT statements                             │
│     └─ Extract individual records                          │
│                                                             │
│  2. Process each record                                    │
│     ├─ Map old fields to new fields                        │
│     ├─ Transform data (status, scores, etc.)               │
│     ├─ Validate data                                       │
│     └─ Clean values                                        │
│                                                             │
│  3. Create database records                                │
│     ├─ Insert into new table                               │
│     ├─ Create related records (leads for bookings)         │
│     └─ Update statistics                                   │
│                                                             │
│  4. Handle errors                                          │
│     ├─ Log failures                                        │
│     ├─ Continue with next record                           │
│     └─ Track success/failure counts                        │
│                                                             │
│  5. Show progress                                          │
│     └─ Progress bar with current count                     │
└────────────────────────────────────────────────────────────┘
                        │
                        ▼
┌────────────────────────────────────────────────────────────┐
│  Display Summary:                                          │
│                                                             │
│  ┌──────────────┬───────┬─────────┬────────┐              │
│  │ Entity       │ Total │ Success │ Failed │              │
│  ├──────────────┼───────┼─────────┼────────┤              │
│  │ Maids        │  15   │   15    │   0    │              │
│  │ Bookings     │  28   │   28    │   0    │              │
│  │ Evaluations  │  17   │   17    │   0    │              │
│  └──────────────┴───────┴─────────┴────────┘              │
│                                                             │
│  ✅ Migration completed!                                   │
└────────────────────────────────────────────────────────────┘
```

## Relationship Diagram (After Migration)

```
┌──────────────┐
│   packages   │
│ (3 records)  │
│ • Silver     │
│ • Gold       │
│ • Platinum   │
└──────┬───────┘
       │
       │ package_id
       │
       ▼
┌──────────────┐         ┌──────────────┐
│   bookings   │────────▶│  crm_leads   │
│ (28 records) │ lead_id │ (28 records) │
└──────┬───────┘         └──────┬───────┘
       │                        │
       │ client_id              │ converted_to_client_id
       │ (if converted)         │ (if converted)
       ▼                        ▼
┌──────────────┐         ┌──────────────┐
│   clients    │◀────────│  crm_leads   │
│ (if exists)  │         │  (converted) │
└──────────────┘         └──────────────┘


┌──────────────┐         ┌──────────────┐
│ evaluations  │────────▶│    maids     │
│ (17 records) │ maid_id │ (15 records) │
└──────────────┘         └──────────────┘
       │
       │ trainer_id (nullable)
       ▼
┌──────────────┐
│   trainers   │
│ (if exists)  │
└──────────────┘
```

## Success Flow

```
START
  │
  ├─ Backup Database ✓
  │
  ├─ Verify SQL Files ✓
  │
  ├─ Run Dry-Run ✓
  │   └─ Review Output
  │
  ├─ Execute Migration ✓
  │   ├─ Migrate Maids (15/15) ✓
  │   ├─ Migrate Bookings (28/28) ✓
  │   │   └─ Create Leads (28/28) ✓
  │   └─ Migrate Evaluations (17/17) ✓
  │
  ├─ Verify Data ✓
  │   ├─ Count Records ✓
  │   ├─ Check Relationships ✓
  │   └─ Spot-Check Data ✓
  │
  ├─ Post-Migration Tasks ✓
  │   ├─ Copy Profile Images ✓
  │   ├─ Assign Trainers ✓
  │   └─ Update Packages ✓
  │
  └─ SUCCESS! ✓
```

---

**Diagram Version**: 1.0  
**Last Updated**: 2024  
**Purpose**: Visual guide for old database migration
