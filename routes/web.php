<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Front\HomeController::class, 'index'])->name('front.home');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/blank-page', [App\Http\Controllers\HomeController::class, 'blank'])->name('blank');

    Route::get('/hakakses', [App\Http\Controllers\HakaksesController::class, 'index'])->name('hakakses.index')->middleware('superadmin');
    Route::get('/hakakses/edit/{id}', [App\Http\Controllers\HakaksesController::class, 'edit'])->name('hakakses.edit')->middleware('superadmin');
    Route::put('/hakakses/update/{id}', [App\Http\Controllers\HakaksesController::class, 'update'])->name('hakakses.update')->middleware('superadmin');
    Route::delete('/hakakses/delete/{id}', [App\Http\Controllers\HakaksesController::class, 'destroy'])->name('hakakses.delete')->middleware('superadmin');

    Route::get('/table-example', [App\Http\Controllers\ExampleController::class, 'table'])->name('table.example');
    Route::get('/clock-example', [App\Http\Controllers\ExampleController::class, 'clock'])->name('clock.example');
    Route::get('/chart-example', [App\Http\Controllers\ExampleController::class, 'chart'])->name('chart.example');
    Route::get('/form-example', [App\Http\Controllers\ExampleController::class, 'form'])->name('form.example');
    Route::get('/map-example', [App\Http\Controllers\ExampleController::class, 'map'])->name('map.example');
    Route::get('/calendar-example', [App\Http\Controllers\ExampleController::class, 'calendar'])->name('calendar.example');
    Route::get('/gallery-example', [App\Http\Controllers\ExampleController::class, 'gallery'])->name('gallery.example');
    Route::get('/todo-example', [App\Http\Controllers\ExampleController::class, 'todo'])->name('todo.example');
    Route::get('/contact-example', [App\Http\Controllers\ExampleController::class, 'contact'])->name('contact.example');
    Route::get('/faq-example', [App\Http\Controllers\ExampleController::class, 'faq'])->name('faq.example');
    Route::get('/news-example', [App\Http\Controllers\ExampleController::class, 'news'])->name('news.example');
    Route::get('/about-example', [App\Http\Controllers\ExampleController::class, 'about'])->name('about.example');

    // ========================
    // ADMIN ROUTES (Backoffice)
    // ========================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])->name('dashboard');

        // Profile admin
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
        Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

        // Objectives CRUD
        Route::resource('/objectifs', App\Http\Controllers\Backoffice\ObjectiveController::class)->except(['show']);

        // Assignments
        Route::get('/users/objectifs', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'assignments'])->name('objectives.assignments');
        Route::post('/users/objectifs', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'assign'])->name('objectives.assign');
        Route::delete('/users/objectifs/{link}', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'unassign'])->name('objectives.unassign');

        // Partners CRUD
        Route::middleware(['partner.management', 'partner.log'])->group(function () {
            Route::resource('/partenaires', App\Http\Controllers\Backoffice\PartnerController::class);
            Route::patch('/partenaires/{partner}/toggle-status', [App\Http\Controllers\Backoffice\PartnerController::class, 'toggleStatus'])->name('partners.toggle-status');
        });

        // Progress
        Route::get('/progress', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'index'])->name('progress.index');
        Route::get('/progress/export', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'export'])->name('progress.export');
        Route::delete('/progress/{progress}', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'destroy'])->name('progress.destroy');

        // User drilldown
        Route::get('/users/{user}', [App\Http\Controllers\Backoffice\UserAdminController::class, 'show'])->name('users.show');

        // Goals CRUD
        Route::resource('/goals', App\Http\Controllers\Backoffice\GoalController::class);
        Route::post('/goals/{goal}/entries', [App\Http\Controllers\Backoffice\GoalEntryController::class, 'store'])->name('goal-entries.store');
        Route::delete('/goals/{goal}/entries/{entry}', [App\Http\Controllers\Backoffice\GoalEntryController::class, 'destroy'])->name('goal-entries.destroy');
    });

    // ========================
    // USER ROUTES (Frontend)
    // ========================
    Route::middleware(['user'])->group(function () {
        // Smart Dashboard
        Route::get('/smart-dashboard', [App\Http\Controllers\Front\SmartDashboardController::class, 'index'])->name('front.smart-dashboard.index');
        Route::get('/smart-dashboard/recommendations', [App\Http\Controllers\Front\SmartDashboardController::class, 'getRecommendations'])->name('front.smart-dashboard.recommendations');
        Route::get('/smart-dashboard/insights', [App\Http\Controllers\Front\SmartDashboardController::class, 'getInsights'])->name('front.smart-dashboard.insights');
        Route::get('/smart-dashboard/predictions', [App\Http\Controllers\Front\SmartDashboardController::class, 'getPredictions'])->name('front.smart-dashboard.predictions');

        // Profile frontend
        Route::prefix('profile')->name('front.profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\Front\ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [App\Http\Controllers\Front\ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [App\Http\Controllers\Front\ProfileController::class, 'update'])->name('update');
            Route::get('/change-password', [App\Http\Controllers\Front\ProfileController::class, 'changePasswordForm'])->name('change-password');
            Route::put('/password', [App\Http\Controllers\Front\ProfileController::class, 'updatePassword'])->name('update-password');
        });

        // Objectives browsing
        Route::resource('/objectifs', App\Http\Controllers\Front\ObjectiveBrowseController::class)->only(['index', 'show']);
        Route::post('/objectifs/{objective}/activate', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'activate'])->name('front.objectives.activate');

        // Partners frontend
        Route::prefix('partenaires')->name('front.partners.')->group(function () {
            Route::get('/', [App\Http\Controllers\Front\PartnerController::class, 'index'])->name('index');
            Route::get('/search', [App\Http\Controllers\Front\PartnerController::class, 'search'])->name('search');
            Route::get('/type/{type}', [App\Http\Controllers\Front\PartnerController::class, 'byType'])->name('by-type');
            Route::get('/mes-favoris', [App\Http\Controllers\Front\PartnerController::class, 'favorites'])->name('favorites');
            Route::get('/{partner}', [App\Http\Controllers\Front\PartnerController::class, 'show'])->name('show')->middleware('partner.status:active');
            Route::post('/{partner}/toggle-favorite', [App\Http\Controllers\Front\PartnerController::class, 'toggleFavorite'])->name('toggle-favorite');
        });

        // Progress frontend
        Route::get('/progres', [App\Http\Controllers\Front\ProgressController::class, 'index'])->name('front.progress.index');
        Route::post('/progres', [App\Http\Controllers\Front\ProgressController::class, 'store'])->name('front.progress.store');

        // Import CSV
        Route::prefix('progres/import')->name('front.progress.import.')->group(function () {
            Route::get('/', [App\Http\Controllers\Front\ProgressImportController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Front\ProgressImportController::class, 'store'])->name('store');
            Route::get('/template', [App\Http\Controllers\Front\ProgressImportController::class, 'downloadTemplate'])->name('template');
        });

        // Demo workout editor
        Route::get('/workout/editor', fn() => view('front.workout.editor'))->name('front.workout.editor');
    });

    // ========================
    // SUPERADMIN ROUTES
    // ========================
    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('activities', App\Http\Controllers\ActivityController::class);
    });

    // ========================
    // ADMIN ROUTES (shared with activities)
    // ========================
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('activities', App\Http\Controllers\ActivityController::class);
    });
});
