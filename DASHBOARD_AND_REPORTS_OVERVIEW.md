# Royal Maids - Admin Dashboard & Comprehensive Reports

## 📊 Overview

A complete analytics and reporting system for admins to monitor and analyze all aspects of the Royal Maids management system.

---

## 🎯 Admin Dashboard (`/admin`)

### Key Performance Indicators (KPIs)

**Primary Metrics** - Displayed in prominent cards:
- **Total Users** - With verified user count
- **Total Maids** - With available maid count
- **Total Bookings** - With active booking count
- **Total Evaluations** - With average rating

### Interactive Charts

#### 1. **Maid Status Distribution** (Doughnut Chart)
- Available
- In Training
- Booked
- Deployed
- Absconded
- Terminated
- On Leave

#### 2. **Booking Status Distribution** (Pie Chart)
- Pending
- Active
- Completed
- Cancelled

#### 3. **Evaluation Trends** (Line Chart - 6 Months)
- Evaluation count over time
- Average rating trends over time
- Dual Y-axis for count and rating

#### 4. **Maid Role Distribution** (Bar Chart)
- Housekeeper
- House Manager
- Nanny
- Chef
- Elderly Caretaker
- Nakawere Caretaker

### Detailed Statistics Panels

#### Client Statistics
- Total Clients
- Active (green badge)
- Pending (yellow badge)
- Expired (red badge)

#### Training Statistics
- Total Programs
- Active Programs (blue badge)
- Completed Programs (green badge)
- Total Trainers

#### Evaluation Statistics
- Total Evaluations
- Approved (green badge)
- Pending (yellow badge)
- Average Rating (blue badge)

### Performance Metrics

#### Top Rated Maids
- Top 5 performers based on average evaluation ratings
- Minimum 2 evaluations required
- Color-coded badges:
  - Green: ≥4.5 (Excellent)
  - Blue: ≥4.0 (Good)
  - Yellow: <4.0 (Average)

#### Recent Evaluations
- Last 5 evaluations with trainer and date
- Rating badges
- Quick links to detailed views

#### Recent Bookings Table
- Client name
- Maid name
- Start date
- Status (color-coded)
- Amount (UGX)

---

## 📈 Comprehensive Reports Page (`/reports`)

### Features

#### Date Range Filtering
- Customizable "From Date" and "To Date"
- Default: Last 30 days
- Real-time data refresh

#### Executive Summary (6 Key Metrics)
- Total Users
- Total Maids
- Total Clients
- Total Bookings
- Evaluations
- Programs

### Evaluation Analytics

#### Charts:
1. **Evaluations by Status** (Doughnut)
   - Approved
   - Pending
   - Rejected

2. **Evaluations by Rating** (Bar Chart)
   - Distribution by star rating (1-5)
   - Average rating displayed

3. **Monthly Evaluation Trends** (Line Chart)
   - Count of evaluations
   - Average rating
   - Dual Y-axis visualization

### Booking Analytics

#### Revenue Card
- Total revenue in UGX
- Large, prominent display
- Icon with gradient background

#### Bookings by Status (Pie Chart)
- Visual breakdown of booking statuses

### Maid Analytics

#### Two Charts:
1. **Maids by Status** (Doughnut)
2. **Maids by Role** (Bar Chart)

### Training Analytics

#### Two Charts:
1. **Programs by Status** (Pie Chart)
2. **Programs by Type** (Bar Chart)

### Top Performers

**Top 10 Rated Maids** (Selected Period)
- Grid display with cards
- Name, rating, and evaluation count
- Color-coded performance badges
- Minimum 2 evaluations required

### Recent Activity

#### Two Panels:
1. **Recent Evaluations** (8 items)
   - Maid name
   - Trainer name
   - Evaluation date
   - Rating badge

2. **Recent Bookings** (8 items)
   - Client and maid names
   - Start date
   - Status badge
   - Amount

---

## 📄 PDF Export Features

### Admin Dashboard
- Quick access to detailed reports
- "View Detailed Reports" button

### Reports Page - PDF Export

**Export Button Features:**
- Real-time generation status
- Loading indicator: "Generating..."
- Respects current date filters

**PDF Report Includes:**
1. **Header Section**
   - Company name and branding
   - Report type and period
   - Generation timestamp

2. **Executive Summary Box**
   - 4 key metrics in grid layout
   - Green highlight for emphasis

3. **Key Performance Metrics**
   - 6 stat cards with values
   - Total clients, programs, revenue, etc.

4. **Evaluation Analytics Tables**
   - By Status (with percentages)
   - By Rating distribution

5. **Top Rated Maids Table**
   - Rank, name, evaluation count
   - Average rating and performance badge

6. **Maid Distribution Tables**
   - By Status
   - By Role

7. **Booking Analytics Table**
   - Status breakdown with percentages

8. **Recent Evaluations Table**
   - Date, maid, trainer, rating, status
   - Up to 15 most recent

9. **Professional Footer**
   - Company information
   - Confidentiality notice
   - Generation timestamp

---

## 🎨 Design Features

### Color Scheme
- **Blue** (#2563eb): Primary actions, users
- **Green** (#22c55e): Success, available, approved
- **Purple** (#a855f7): Bookings, special highlights
- **Amber** (#f59e0b): Evaluations, warnings
- **Red** (#ef4444): Cancelled, rejected, errors
- **Gray**: Neutral, archived items

### Charts
- **Chart.js 4.4.0** - Modern, responsive charts
- Consistent color palette across all visualizations
- Interactive tooltips and legends
- Responsive design (mobile-friendly)

### Cards & Panels
- Gradient backgrounds for KPI cards
- Shadow and border styling
- Dark mode support throughout
- Icon integration (Flux icons)

---

## 🔐 Security & Permissions

### Access Control
- **Admin Dashboard**: Only `admin` role
- **Reports Page**: Only `admin` role
- Policy-based authorization checks

### Data Scope
- Admins see all data
- Trainers see only their own evaluations (in their dashboard)
- Date range filtering maintains security

---

## 📱 Responsive Design

### Desktop (lg and xl)
- Multi-column grid layouts (2-6 columns)
- Side-by-side chart displays
- Expanded tables

### Tablet (md)
- 2-column layouts
- Stacked charts
- Compressed tables

### Mobile (sm and xs)
- Single column layout
- Stacked cards
- Simplified navigation

---

## 🚀 Performance Optimizations

### Database Queries
- Eager loading relationships: `with(['maid', 'trainer.user', 'client', 'program'])`
- Efficient aggregations: `COUNT()`, `AVG()`, `SUM()`
- Limited result sets: `take(5)`, `take(10)`
- Indexed queries on status and date fields

### Frontend
- CDN-hosted Chart.js (fast delivery)
- Lazy loading for charts (DOMContentLoaded)
- Optimized image/icon rendering
- Minimal JavaScript payload

---

## 📊 Data Analytics Capabilities

### Metrics Tracked
1. **User Metrics**: Total, verified, by role
2. **Maid Metrics**: Total, by status, by role, recent additions
3. **Client Metrics**: Total, by subscription status
4. **Booking Metrics**: Total, by status, revenue
5. **Evaluation Metrics**: Total, by status, by rating, average
6. **Training Metrics**: Programs, trainers, by type/status

### Trends & Insights
- 6-month evaluation trends
- Monthly aggregations
- Performance rankings
- Status distributions

---

## 🔧 Technical Stack

- **Backend**: Laravel 12.35, Livewire 3
- **Frontend**: Flux UI Components, Tailwind CSS
- **Charts**: Chart.js 4.4.0
- **PDF**: Laravel DomPDF (barryvdh/laravel-dompdf)
- **Database**: MySQL (with DATE_FORMAT aggregations)

---

## 📋 Routes

```php
Route::get('/admin', AdminDashboard::class)
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('dashboard.admin');

Route::get('/reports', Reports\Index::class)
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('reports');
```

---

## 🎯 Usage

### For Admins

1. **Dashboard Access**: 
   - Login as admin
   - Automatically redirected to `/admin`
   - View all KPIs and charts

2. **Reports Access**:
   - Click "View Detailed Reports" button on dashboard
   - Or navigate to `/reports`
   - Adjust date range as needed
   - Click "Export PDF Report" to download

3. **PDF Reports**:
   - Customize date range first
   - Click export button
   - PDF downloads automatically
   - Filename format: `report-overview-YYYY-MM-DD-HHMMSS.pdf`

---

## 📈 Future Enhancements

Potential additions:
- Email scheduled reports
- Custom report builder
- Data export to Excel/CSV
- Comparative analytics (YoY, MoM)
- Predictive analytics
- Custom dashboard widgets
- Real-time notifications
- Drill-down capabilities

---

## ✅ Completed Features

✅ Comprehensive Admin Dashboard with KPIs  
✅ Interactive charts (7 different visualizations)  
✅ Top performers tracking  
✅ Recent activity feeds  
✅ Dedicated Reports page  
✅ Date range filtering  
✅ PDF export functionality  
✅ Evaluation analytics  
✅ Booking analytics  
✅ Maid analytics  
✅ Training analytics  
✅ Responsive design  
✅ Dark mode support  
✅ Professional PDF templates  
✅ Route integration  

---

**Generated**: October 23, 2025  
**System**: Royal Maids v5.0  
**Documentation**: Dashboard & Reports Module

