<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Dashboard\{AdminDashboard, TrainerDashboard, ClientDashboard};
use Illuminate\Support\Facades\Route as RouteFacade;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
    
    // Tickets routes (admin only for now)
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
    Route::get('/bookings/create', \App\Livewire\Bookings\CreateWizard::class)->name('bookings.create'); // Multi-step wizard
    Route::get('/bookings/{booking}', \App\Livewire\Bookings\Show::class)->name('bookings.show');
    Route::get('/bookings/{booking}/edit', \App\Livewire\Bookings\CreateWizard::class)->name('bookings.edit'); // Reuse wizard for editing
});

Route::middleware(['auth', 'verified', 'role:admin,trainer'])->group(function () {
    Route::view('/trainees', 'wip')->name('trainees.index');
    
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
    
    Route::view('/schedule', 'wip')->name('schedule.index');
});

Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::view('/my-bookings', 'wip')->name('client.bookings.index');
    Route::view('/browse-maids', 'wip')->name('maids.browse');
    Route::view('/subscriptions', 'wip')->name('subscriptions.index');
    Route::view('/favorites', 'wip')->name('favorites.index');
    Route::view('/support', 'wip')->name('support.index');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

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
