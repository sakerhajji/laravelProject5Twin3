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
        Route::get('/goals/{goal}/edit', [App\Http\Controllers\Backoffice\GoalController::class, 'edit'])->name('goals.edit');
        Route::put('/goals/{goal}', [App\Http\Controllers\Backoffice\GoalController::class, 'update'])->name('goals.update');
        Route::delete('/goals/{goal}', [App\Http\Controllers\Backoffice\GoalController::class, 'destroy'])->name('goals.destroy');

        Route::post('/goals/{goal}/entries', [App\Http\Controllers\Backoffice\GoalEntryController::class, 'store'])->name('goal-entries.store');
        Route::delete('/goals/{goal}/entries/{entry}', [App\Http\Controllers\Backoffice\GoalEntryController::class, 'destroy'])->name('goal-entries.destroy');
    });
});
