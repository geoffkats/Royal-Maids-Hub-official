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
    Route::get('/maids', \App\Livewire\Maids\Index::class)->name('maids.index');
    Route::view('/trainers', 'wip')->name('trainers.index');
    Route::view('/clients', 'wip')->name('clients.index');
    Route::view('/bookings', 'wip')->name('bookings.index');
    Route::view('/reports', 'wip')->name('reports.index');
    Route::view('/packages', 'wip')->name('packages.index');
});

Route::middleware(['auth', 'verified', 'role:trainer'])->group(function () {
    Route::view('/trainees', 'wip')->name('trainees.index');
    Route::view('/programs', 'wip')->name('programs.index');
    Route::view('/schedule', 'wip')->name('schedule.index');
    Route::view('/evaluations', 'wip')->name('evaluations.index');
});

Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::view('/bookings/create', 'wip')->name('bookings.create');
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
