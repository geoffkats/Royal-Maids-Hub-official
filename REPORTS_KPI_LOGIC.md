# Royal Maids Hub Reports & KPI Calculation Logic

This document explains the logic used to calculate the analytics, KPIs, and progress metrics in the `reports.php` dashboard.

## Data Source
- All calculations are based on the latest 20 records from the `maid_evaluations` table, joined with the `maids` table for additional maid details.
- The PHP variable `$evaluations` contains these records as associative arrays.

## KPI Fields
- The following fields are considered as KPIs: `confidence`, `self_awareness`, `emotional_stability`, `growth_mindset`, `punctuality`, `respect`, `ownership`, `conduct`, `cleaning`, `laundry`, `meals`, `childcare`.

## KPI Averages
- For each KPI field, the average is calculated as:
  - Sum of all non-null values for that field across all evaluations, divided by the count of non-null values.
  - Example: `confidence_avg = (sum of all confidence scores) / (number of confidence scores)`
- The result is stored in the `$kpiAverages` array.

## Key Performance Indicators Table

### 1. Average Training Completion Rate
- **Definition:** Percentage of evaluations with status `approved` out of all evaluations.
- **Formula:**
  - `completionRate = (number of approved evaluations / total evaluations) * 100`
- **Progress Bar:** Width is set to `completionRate%` (minimum 2% for visibility).
- **Target:** 90% (can be changed in code).
- **Trend:** Shows the difference between current and target.
- **Status:**
  - `Above Target` if current >= target
  - `Near Target` otherwise

### 2. Average KPI Score
- **Definition:** The average of all KPI fields, normalized to a percentage.
- **Formula:**
  - `avgKpi = (sum of all KPI averages) / (number of KPI fields with data)`
  - `avgKpiPercent = (avgKpi / 5) * 100` (since each KPI is out of 5)
- **Progress Bar:** Width is set to `avgKpiPercent%` (minimum 2% for visibility).
- **Target:** 85% (can be changed in code).
- **Trend:** Shows the difference between current and target.
- **Status:**
  - `Above Target` if current >= target
  - `Near Target` otherwise

### 3. Trainer Efficiency
- **Definition:** For demo, set as `completionRate - 5`. (You can adjust this logic to match your business rule.)
- **Progress Bar:** Width is set to `trainerEfficiency%` (minimum 2% for visibility).
- **Target:** 85% (can be changed in code).
- **Trend:** Shows the difference between current and target.
- **Status:**
  - `Above Target` if current >= target
  - `Near Target` otherwise

## Training Progress Table
- Groups evaluations by `facilitator` (trainer).
- For each trainer:
  - **Total Maids:** Number of evaluations for that trainer.
  - **Completed:** Number of evaluations with status `approved`.
  - **In Progress:** Number of evaluations not `approved`.
  - **Success Rate:** `(completed / total) * 100`.
  - **Avg. KPI Score:** Average of all KPIs for that trainer (out of 5).
  - **Status:**
    - `On Track` if success rate >= 85%
    - `At Risk` if 70% <= success rate < 85%
    - `Behind` if success rate < 70%

## Training Details Table
- Lists each evaluation with:
  - Trainee details (name, code, nationality, avatar)
  - Facilitator
  - Date
  - Status (approved, pending, etc.)
  - KPI average for that evaluation
  - Link to view full evaluation profile

## Charts
- **Completion Chart:** Bar chart of average scores for each KPI field (out of 5).
- **Performance Chart:** Doughnut chart showing distribution of `confidence` scores:
  - Excellent: 4.5–5
  - Good: 3.5–4.49
  - Average: 2.5–3.49
  - Below Average: <2.5

## Notes
- All calculations are performed in PHP using the latest data from the database.
- Targets and thresholds can be adjusted in the PHP code as needed.
- Progress bars always show at least 2% width for visibility, even if the value is 0.

---
For any changes to the calculation logic, edit the relevant PHP code blocks in `reports.php`.
