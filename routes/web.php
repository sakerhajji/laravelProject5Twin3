<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Front\HomeController::class, 'index']);

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
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

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])->name('dashboard');
        // Objectives CRUD
        Route::get('/objectifs', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'index'])->name('objectives.index');
        Route::get('/objectifs/create', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'create'])->name('objectives.create');
        Route::post('/objectifs', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'store'])->name('objectives.store');
        Route::get('/objectifs/{objective}/edit', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'edit'])->name('objectives.edit');
        Route::put('/objectifs/{objective}', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'update'])->name('objectives.update');
        Route::delete('/objectifs/{objective}', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'destroy'])->name('objectives.destroy');
        // Assignments
        Route::get('/users/objectifs', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'assignments'])->name('objectives.assignments');
        Route::post('/users/objectifs', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'assign'])->name('objectives.assign');
        Route::delete('/users/objectifs/{link}', [App\Http\Controllers\Backoffice\ObjectiveController::class, 'unassign'])->name('objectives.unassign');
        
        // Progress admin listing/export/delete (front data managed in backoffice)
        Route::get('/progress', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'index'])->name('progress.index');
        Route::get('/progress/export', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'export'])->name('progress.export');
        Route::delete('/progress/{progress}', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'destroy'])->name('progress.destroy');

        // User drilldown
        Route::get('/users/{user}', [App\Http\Controllers\Backoffice\UserAdminController::class, 'show'])->name('users.show');
        // add more admin routes here
    });

    // Client routes
    Route::middleware(['client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])->name('dashboard');
        // add more client routes here
    });

    // Goals management (backoffice - accessible to any authenticated user)
    Route::prefix('back')->name('back.')->group(function () {
        Route::get('/goals', [App\Http\Controllers\Backoffice\GoalController::class, 'index'])->name('goals.index');
        Route::get('/goals/create', [App\Http\Controllers\Backoffice\GoalController::class, 'create'])->name('goals.create');
        Route::post('/goals', [App\Http\Controllers\Backoffice\GoalController::class, 'store'])->name('goals.store');
        Route::get('/goals/{goal}', [App\Http\Controllers\Backoffice\GoalController::class, 'show'])->name('goals.show');
        Route::get('/goals/{goal}/edit', [App\Http\Controllers\Backoffice\GoalController::class, 'edit'])->name('goals.edit');
        Route::put('/goals/{goal}', [App\Http\Controllers\Backoffice\GoalController::class, 'update'])->name('goals.update');
        Route::delete('/goals/{goal}', [App\Http\Controllers\Backoffice\GoalController::class, 'destroy'])->name('goals.destroy');

        Route::post('/goals/{goal}/entries', [App\Http\Controllers\Backoffice\GoalEntryController::class, 'store'])->name('goal-entries.store');
        Route::delete('/goals/{goal}/entries/{entry}', [App\Http\Controllers\Backoffice\GoalEntryController::class, 'destroy'])->name('goal-entries.destroy');
    });

    // Front user: browse objectives and record progress
    Route::get('/objectifs', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'index'])->name('front.objectives.index');
    Route::get('/objectifs/{objective}', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'show'])->name('front.objectives.show');
    Route::post('/objectifs/{objective}/activate', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'activate'])->name('front.objectives.activate');
    Route::get('/progres', [App\Http\Controllers\Front\ProgressController::class, 'index'])->name('front.progress.index');
    Route::post('/progres', [App\Http\Controllers\Front\ProgressController::class, 'store'])->name('front.progress.store');
    
    // Import CSV routes
    Route::get('/progres/import', [App\Http\Controllers\Front\ProgressImportController::class, 'index'])->name('front.progress.import.index');
    Route::post('/progres/import', [App\Http\Controllers\Front\ProgressImportController::class, 'store'])->name('front.progress.import.store');
    Route::get('/progres/import/template', [App\Http\Controllers\Front\ProgressImportController::class, 'downloadTemplate'])->name('front.progress.import.template');

    // Demo workout editor UI
    Route::get('/workout/editor', function () { return view('front.workout.editor'); })->name('front.workout.editor');
});
