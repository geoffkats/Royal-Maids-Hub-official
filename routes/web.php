<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Dashboard\{AdminDashboard, TrainerDashboard, ClientDashboard};
use Illuminate\Support\Facades\Route as RouteFacade;

Route::get('/', function () {
    $packages = \App\Models\Package::active()->take(3)->get();
    return view('home.index', compact('packages'));
})->name('home');

// Public routes (no authentication required)
Route::get('/packages/public', function () {
    $packages = \App\Models\Package::active()->get();
    return view('packages.public', compact('packages'));
})->name('packages.public');

// Public maids showcase page
Route::get('/maids/public', function () {
    $availableMaids = \App\Models\Maid::query()
        ->where('status', 'available')
        ->latest('date_of_arrival')
        ->take(12)
        ->get();

    $inTrainingMaids = \App\Models\Maid::query()
        ->where('status', 'in-training')
        ->latest('date_of_arrival')
        ->take(12)
        ->get();

    return view('home.maids', [
        'availableMaids' => $availableMaids,
        'inTrainingMaids' => $inTrainingMaids,
        'availableCount' => \App\Models\Maid::where('status', 'available')->count(),
        'inTrainingCount' => \App\Models\Maid::where('status', 'in-training')->count(),
    ]);
})->name('maids.public');

// Public training programs (simple page)
Route::view('/training', 'home.training')->name('training.public');

// Public quality assurance (simple page)
Route::view('/quality-assurance', 'home.quality')->name('quality.public');

// Public contact page (re-uses the homepage contact Livewire form)
Route::view('/contact', 'home.contact')->name('contact.public');

// Public privacy policy page
Route::view('/privacy-policy', 'home.privacy')->name('privacy.public');

// Public about page
Route::view('/about', 'home.about')->name('about.public');

// Public booking page
Route::get('/booking', \App\Livewire\PublicBooking::class)->name('booking.public');
Route::post('/booking', [\App\Http\Controllers\PublicBookingController::class, 'store'])->name('booking.submit');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();
        return match ($user?->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'trainer' => redirect()->route('dashboard.trainer'),
            default => redirect()->route('dashboard.client'),
        };
    })->name('dashboard');

    Route::get('admin', AdminDashboard::class)
        ->middleware(['role:admin'])
        ->name('dashboard.admin');

    Route::get('trainer', TrainerDashboard::class)
        ->middleware(['role:trainer'])
        ->name('dashboard.trainer');

    Route::get('client', ClientDashboard::class)
        ->middleware(['role:client'])
        ->name('dashboard.client');
});

// Temporary placeholder routes for navigation items until features are implemented
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Maids routes
    Route::get('/maids', \App\Livewire\Maids\Index::class)->name('maids.index');
    Route::get('/maids/create', \App\Livewire\Maids\Create::class)->name('maids.create');
    Route::get('/maids/{maid}', \App\Livewire\Maids\Show::class)->name('maids.show');
    Route::get('/maids/{maid}/edit', \App\Livewire\Maids\Edit::class)->name('maids.edit');
    Route::get('/maids/export/pdf', [\App\Http\Controllers\MaidController::class, 'exportPdf'])->name('maids.export.pdf');
    
    // Trainers routes
    Route::get('/trainers', \App\Livewire\Trainers\Index::class)->name('trainers.index');
    Route::get('/trainers/create', \App\Livewire\Trainers\Create::class)->name('trainers.create');
    Route::get('/trainers/{trainer}', \App\Livewire\Trainers\Show::class)->name('trainers.show');
    Route::get('/trainers/{trainer}/edit', \App\Livewire\Trainers\Edit::class)->name('trainers.edit');
    
    // Clients routes
    Route::get('/clients', \App\Livewire\Clients\Index::class)->name('clients.index');
    Route::get('/clients/create', \App\Livewire\Clients\Create::class)->name('clients.create');
    Route::get('/clients/{client}', \App\Livewire\Clients\Show::class)->name('clients.show');
    Route::get('/clients/{client}/edit', \App\Livewire\Clients\Edit::class)->name('clients.edit');
    
    // Bookings routes (moved to authenticated group below)
    
    // Reports routes (moved to admin,trainer group below)
    
    // Packages routes (admin & client can view)
    Route::get('/packages', \App\Livewire\Packages\Index::class)->name('packages.index');
    Route::get('/packages/create', \App\Livewire\Packages\Create::class)->name('packages.create');
    Route::get('/packages/{package}/edit', \App\Livewire\Packages\Edit::class)->name('packages.edit');
    
});

// Tickets routes (admin and trainer access)
Route::middleware(['auth', 'verified', 'role:admin,trainer'])->group(function () {
    Route::get('/tickets', \App\Livewire\Tickets\Index::class)->name('tickets.index');
    Route::get('/tickets/create', \App\Livewire\Tickets\Create::class)->name('tickets.create');
    Route::get('/tickets/analytics', \App\Livewire\Tickets\Analytics::class)->name('tickets.analytics');
    Route::get('/tickets/inbox', \App\Livewire\Tickets\Inbox::class)->name('tickets.inbox');
    Route::get('/tickets/{ticket}', \App\Livewire\Tickets\Show::class)->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', \App\Livewire\Tickets\Edit::class)->name('tickets.edit');
});

// Bookings: available to authenticated users; component policies handle permissions/visibility
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bookings', \App\Livewire\Bookings\Index::class)->name('bookings.index');
    Route::get('/bookings/create', \App\Livewire\Bookings\CreateWizard::class)->name('bookings.create'); // Wizard form
    Route::get('/bookings/{booking}', \App\Livewire\Bookings\Show::class)->name('bookings.show');
    Route::get('/bookings/{booking}/edit', \App\Livewire\Bookings\Edit::class)->name('bookings.edit'); // Edit booking
});

Route::middleware(['auth', 'verified', 'role:admin,trainer'])->group(function () {
    Route::get('/trainees', \App\Livewire\Maids\Index::class)->name('trainees.index');
    
    // Weekly Task Board routes (trainer only)
    Route::middleware(['role:trainer'])->group(function () {
        Route::get('/weekly-board', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'index'])->name('weekly-board.index');
        Route::post('/weekly-board/tasks', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'storeTask'])->name('weekly-board.tasks.store');
        Route::put('/weekly-board/tasks/{task}', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'updateTask'])->name('weekly-board.tasks.update');
        Route::delete('/weekly-board/tasks/{task}', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'destroyTask'])->name('weekly-board.tasks.destroy');
        Route::post('/weekly-board/submit', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'submitForReview'])->name('weekly-board.submit');
        Route::post('/weekly-board/start-next-week', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'startNextWeek'])->name('weekly-board.start-next-week');
    });
    
    // Reports routes - accessible to admin and trainers
    Route::get('/reports', \App\Livewire\Reports\Index::class)->name('reports');
    Route::get('/reports/kpi-dashboard', \App\Livewire\Reports\KpiDashboard::class)->name('reports.kpi-dashboard');
    
    // Deployments routes
    Route::get('/deployments', \App\Livewire\Deployments\Index::class)->name('deployments.index');
    Route::get('/deployments/{deployment}', \App\Livewire\Deployments\Show::class)->name('deployments.show');
    
    // Training Programs routes
    Route::get('/programs', \App\Livewire\Programs\Index::class)->name('programs.index');
    Route::get('/programs/create', \App\Livewire\Programs\Create::class)->name('programs.create');
    Route::get('/programs/{program}', \App\Livewire\Programs\Show::class)->name('programs.show');
    Route::get('/programs/{program}/edit', \App\Livewire\Programs\Edit::class)->name('programs.edit');
    
    // Evaluations routes
    Route::get('/evaluations', \App\Livewire\Evaluations\Index::class)->name('evaluations.index');
    Route::get('/evaluations/create', \App\Livewire\Evaluations\Create::class)->name('evaluations.create');
    Route::get('/evaluations/{evaluation}', \App\Livewire\Evaluations\Show::class)->name('evaluations.show');
    Route::get('/evaluations/{evaluation}/edit', \App\Livewire\Evaluations\Edit::class)->name('evaluations.edit');
    
    Route::get('/schedule', \App\Livewire\Schedule\Index::class)->name('schedule.index');
    Route::get('/reports/trainer', \App\Livewire\Reports\TrainerReports::class)->name('reports.trainer');

    // CRM Reports (admin and trainer)
    Route::prefix('crm/reports')->name('crm.reports.')->group(function () {
        Route::get('/funnel', \App\Livewire\CRM\Reports\LeadFunnel::class)->name('funnel');
        Route::get('/sales-performance', \App\Livewire\CRM\Reports\SalesPerformance::class)->name('sales-performance');
        Route::get('/activity-metrics', \App\Livewire\CRM\Reports\ActivityMetrics::class)->name('activity-metrics');
        Route::get('/revenue-forecasting', \App\Livewire\CRM\Reports\RevenueForecasting::class)->name('revenue-forecasting');
    });
});

Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::view('/my-bookings', 'wip')->name('client.bookings.index');
    Route::view('/browse-maids', 'wip')->name('maids.browse');
    Route::view('/subscriptions', 'wip')->name('subscriptions.index');
    Route::view('/favorites', 'wip')->name('favorites.index');
    Route::view('/support', 'wip')->name('support.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('settings', \App\Livewire\Settings\Index::class)->name('settings.index');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// CRM Routes (admin only)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Pipeline Board (Kanban)
    Route::get('/crm/pipeline/{pipelineId?}', \App\Livewire\CRM\Pipeline\Board::class)->name('crm.pipeline');

    // Leads (place export before wildcard)
    Route::get('/crm/leads', \App\Livewire\CRM\Leads\Index::class)->name('crm.leads.index');
    Route::get('/crm/leads/create', \App\Livewire\CRM\Leads\Create::class)->name('crm.leads.create');
    Route::get('/crm/leads/export', [\App\Http\Controllers\CRM\DataTransferController::class, 'exportLeads'])->name('crm.leads.export');
    Route::get('/crm/leads/{lead}', \App\Livewire\CRM\Leads\Show::class)->name('crm.leads.show');
    Route::get('/crm/leads/{lead}/edit', \App\Livewire\CRM\Leads\Edit::class)->name('crm.leads.edit');
    Route::post('/crm/leads/import', [\App\Http\Controllers\CRM\DataTransferController::class, 'importLeads'])->name('crm.leads.import');
    
    // Opportunities (place export before wildcard)
    Route::get('/crm/opportunities', \App\Livewire\CRM\Opportunities\Index::class)->name('crm.opportunities.index');
    Route::get('/crm/opportunities/create', \App\Livewire\CRM\Opportunities\Create::class)->name('crm.opportunities.create');
    Route::get('/crm/opportunities/export', [\App\Http\Controllers\CRM\DataTransferController::class, 'exportOpportunities'])->name('crm.opportunities.export');
    Route::get('/crm/opportunities/{opportunity}', \App\Livewire\CRM\Opportunities\Show::class)->name('crm.opportunities.show');
    Route::get('/crm/opportunities/{opportunity}/edit', \App\Livewire\CRM\Opportunities\Edit::class)->name('crm.opportunities.edit');
    Route::post('/crm/opportunities/import', [\App\Http\Controllers\CRM\DataTransferController::class, 'importOpportunities'])->name('crm.opportunities.import');
    
    // Activities (define export before wildcard routes to avoid matching "export" as an {activity})
    Route::get('/crm/activities', \App\Livewire\CRM\Activities\Index::class)->name('crm.activities.index');
    Route::get('/crm/activities/create', \App\Livewire\CRM\Activities\Create::class)->name('crm.activities.create');
    Route::get('/crm/activities/export', [\App\Http\Controllers\CRM\DataTransferController::class, 'exportActivities'])->name('crm.activities.export');
    Route::get('/crm/activities/{activity}', \App\Livewire\CRM\Activities\Show::class)->name('crm.activities.show');
    Route::get('/crm/activities/{activity}/edit', \App\Livewire\CRM\Activities\Edit::class)->name('crm.activities.edit');
    
    // Tags
    Route::get('/crm/tags', \App\Livewire\CRM\Tags\Index::class)->name('crm.tags.index');
    Route::get('/crm/tags/create', \App\Livewire\CRM\Tags\Create::class)->name('crm.tags.create');
    Route::get('/crm/tags/{tag}/edit', \App\Livewire\CRM\Tags\Edit::class)->name('crm.tags.edit');
    
    Route::get('/crm/settings', \App\Livewire\CRM\Settings\Index::class)->name('crm.settings.index');
    
    // Company Settings
    Route::get('/settings/company', \App\Livewire\Settings\CompanySettings::class)->name('settings.company');
    
    // Contact Form Submissions
    Route::get('/contact-submissions', \App\Livewire\ContactSubmissions::class)->name('contact-submissions.index');

    // Weekly Boards Review (Admin)
    Route::get('/weekly-boards', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'adminIndex'])->name('admin.weekly-boards.index');
    Route::get('/weekly-boards/{board}', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'adminShow'])->name('admin.weekly-boards.show');
    Route::post('/weekly-boards/{board}/review', [\App\Http\Controllers\WeeklyTaskBoardController::class, 'adminMarkReviewed'])->name('admin.weekly-boards.review');
});
