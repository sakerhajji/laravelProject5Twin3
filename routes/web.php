<?php

use App\Http\Controllers\AsymptomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MaladieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontCategoryController;
use App\Http\Controllers\Front\FrontActivityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backoffice\AlimentController;
use App\Http\Controllers\Backoffice\RepasController;
use App\Http\Controllers\Backoffice\UserManagementController;
use App\Http\Controllers\Front\RepasController as FrontRepasController;
use App\Http\Controllers\Front\SmartDashboardController;

Route::post('/chatbot/send', [SmartDashboardController::class, 'chatbotMessage'])->name('chatbot.send');

// Route frontend protégée contre l'accès admin
Route::get('/', [App\Http\Controllers\Front\HomeController::class, 'index'])
    ->name('front.home')
    ->middleware('no.admin.frontend');

Auth::routes();

Route::middleware(['auth'])->group(function () {
  

    // Activities
Route::get('/activities', [FrontActivityController::class, 'index'])->name('front.activities.index');
Route::get('/activites/category/{category}', [FrontActivityController::class, 'byCategory'])->name('front.activities.byCategory');
    Route::get('/categories', [App\Http\Controllers\Front\FrontCategoryController::class, 'index'])->name('front.categories.index');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/blank-page', [App\Http\Controllers\HomeController::class, 'blank'])->name('blank');
    // Maladie diagnosis routes (front office)
    Route::get('/maladie/diagnose', [App\Http\Controllers\Front\MaladieDiagnoseController::class, 'showForm'])->name('front.maladie.diagnose');
    Route::post('/maladie/match', [App\Http\Controllers\Front\MaladieDiagnoseController::class, 'matchMaladies'])->name('front.maladie.match');
    Route::post('/maladie/api-match', [App\Http\Controllers\Front\MaladieDiagnoseController::class, 'apiMatch'])->name('front.maladie.apiMatch');
    Route::post('/maladie/save', [App\Http\Controllers\Front\MaladieDiagnoseController::class, 'saveMaladie'])->name('front.maladie.save');
    Route::get('/maladie/history', [App\Http\Controllers\Front\MaladieDiagnoseController::class, 'history'])->name('front.maladie.history');

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

    // Dans le groupe Route::middleware(['auth'])
Route::post('/objectives/schedule', [SmartDashboardController::class, 'saveSchedule'])->name('front.objectives.schedule');
Route::get('/objectives/get-schedule', [SmartDashboardController::class, 'getSchedule'])->name('front.objectives.get-schedule');
    // Admin routes - BACKOFFICE UNIQUEMENT
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
                 // Route::get('/create-meet', [MeetingController::class, 'create'])->name('create.meet');
                     // Show the form
        Route::get('/create-meet', [MeetingController::class, 'showForm'])->name('create.meet');
        Route::post('/create-meet', [MeetingController::class, 'start'])->name('start.meet');

                 
        Route::get('/dashboard', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])->name('dashboard');
        
        // Categories CRUD - avec validation de données
        Route::middleware(['category.management', 'category.data'])->group(function () {
            Route::resource('categories', App\Http\Controllers\CategoryController::class);
        });

        // Activities CRUD - avec validation de données
        Route::middleware(['activity.management', 'activity.data'])->group(function () {
            Route::resource('activities', App\Http\Controllers\ActivityController::class);
        });

        // Profile admin dans le backoffice
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
        Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
        
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
        // Partners CRUD - avec middlewares de validation et logging
        Route::middleware(['partner.management', 'partner.log'])->group(function () {
            Route::get('/partenaires', [App\Http\Controllers\Backoffice\PartnerController::class, 'index'])->name('partners.index');
            Route::get('/partenaires/create', [App\Http\Controllers\Backoffice\PartnerController::class, 'create'])->name('partners.create');
            Route::post('/partenaires', [App\Http\Controllers\Backoffice\PartnerController::class, 'store'])->name('partners.store')->middleware('partner.data');

            Route::middleware(['partner.validate'])->group(function () {
                Route::get('/partenaires/{partner}', [App\Http\Controllers\Backoffice\PartnerController::class, 'show'])->name('partners.show');
                Route::get('/partenaires/{partner}/edit', [App\Http\Controllers\Backoffice\PartnerController::class, 'edit'])->name('partners.edit');
                Route::put('/partenaires/{partner}', [App\Http\Controllers\Backoffice\PartnerController::class, 'update'])->name('partners.update')->middleware('partner.data');
                Route::delete('/partenaires/{partner}', [App\Http\Controllers\Backoffice\PartnerController::class, 'destroy'])->name('partners.destroy');
                Route::patch('/partenaires/{partner}/toggle-status', [App\Http\Controllers\Backoffice\PartnerController::class, 'toggleStatus'])->name('partners.toggle-status');
            });
        });


        // Progress admin listing/export/delete (front data managed in backoffice)
        Route::get('/progress', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'index'])->name('progress.index');
        Route::get('/progress/export', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'export'])->name('progress.export');
        Route::delete('/progress/{progress}', [App\Http\Controllers\Backoffice\ProgressAdminController::class, 'destroy'])->name('progress.destroy');

        //aliment + repas
        Route::resource('aliments', AlimentController::class);
        Route::resource('repas', RepasController::class);
        
        // User Management - Gestion complète des utilisateurs
        Route::resource('users', App\Http\Controllers\Backoffice\UserManagementController::class);
        Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Backoffice\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        // User drilldown
        Route::get('/users/{user}', [App\Http\Controllers\Backoffice\UserAdminController::class, 'show'])->name('users.show');

        // add more admin routes here
    });

        // Goals management (backoffice)
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

    // User routes - FRONTEND UNIQUEMENT (Protection contre accès admin)
    Route::middleware(['user', 'no.admin.frontend'])->group(function () {
            // Check Exercise Routes
    Route::get('/checkexercice', [ActivityController::class, 'checkExercisePage'])->name('checkexercice');
    Route::post('/checkexercice', [ActivityController::class, 'checkExercise'])->name('checkexercice.post');



        // Smart Dashboard
        Route::get('/smart-dashboard', [App\Http\Controllers\Front\SmartDashboardController::class, 'index'])->name('front.smart-dashboard.index');
        Route::get('/smart-dashboard/recommendations', [App\Http\Controllers\Front\SmartDashboardController::class, 'getRecommendations'])->name('front.smart-dashboard.recommendations');
        Route::get('/smart-dashboard/insights', [App\Http\Controllers\Front\SmartDashboardController::class, 'getInsights'])->name('front.smart-dashboard.insights');
        Route::get('/smart-dashboard/predictions', [App\Http\Controllers\Front\SmartDashboardController::class, 'getPredictions'])->name('front.smart-dashboard.predictions');


        // Profile utilisateur frontend
        Route::prefix('profile')->name('front.profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\Front\ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [App\Http\Controllers\Front\ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [App\Http\Controllers\Front\ProfileController::class, 'update'])->name('update');
            Route::get('/change-password', [App\Http\Controllers\Front\ProfileController::class, 'changePasswordForm'])->name('change-password');
            Route::put('/password', [App\Http\Controllers\Front\ProfileController::class, 'updatePassword'])->name('update-password');
        });
        
        // Front user: browse objectives and record progress
        Route::get('/objectifs', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'index'])->name('front.objectives.index');
        Route::get('/objectifs/{objective}', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'show'])->name('front.objectives.show');
        Route::post('/objectifs/{objective}/activate', [App\Http\Controllers\Front\ObjectiveBrowseController::class, 'activate'])->name('front.objectives.activate');
        
        // Partners frontend routes - avec validation
        Route::prefix('partenaires')->name('front.partners.')->group(function () {
            Route::get('/', [App\Http\Controllers\Front\PartnerController::class, 'index'])->name('index');
            Route::get('/search', [App\Http\Controllers\Front\PartnerController::class, 'search'])->name('search'); // Route AJAX
            Route::get('/recommendations', [App\Http\Controllers\Front\PartnerController::class, 'recommendations'])->name('recommendations')->middleware('auth');
            Route::post('/intelligent-search', [App\Http\Controllers\Front\PartnerController::class, 'intelligentSearch'])->name('intelligent-search');
            Route::get('/type/{type}', [App\Http\Controllers\Front\PartnerController::class, 'byType'])->name('by-type');
            Route::get('/mes-favoris', [App\Http\Controllers\Front\PartnerController::class, 'favorites'])->name('favorites');
            
            Route::middleware(['partner.validate'])->group(function () {
                Route::get('/{partner}', [App\Http\Controllers\Front\PartnerController::class, 'show'])->name('show')->middleware('partner.status:active');
                Route::post('/{partner}/toggle-favorite', [App\Http\Controllers\Front\PartnerController::class, 'toggleFavorite'])->name('toggle-favorite');
                // Ratings
                Route::post('/{partner}/rating', [App\Http\Controllers\Front\PartnerController::class, 'rate'])->name('rate')->middleware('auth');
            });
        });
        
        Route::get('/progres', [App\Http\Controllers\Front\ProgressController::class, 'index'])->name('front.progress.index');
        Route::post('/progres', [App\Http\Controllers\Front\ProgressController::class, 'store'])->name('front.progress.store');
        
        // Import CSV routes
        Route::get('/progres/import', [App\Http\Controllers\Front\ProgressImportController::class, 'index'])->name('front.progress.import.index');
        Route::post('/progres/import', [App\Http\Controllers\Front\ProgressImportController::class, 'store'])->name('front.progress.import.store');
        Route::get('/progres/import/template', [App\Http\Controllers\Front\ProgressImportController::class, 'downloadTemplate'])->name('front.progress.import.template');

        //front repas
        Route::get('/repas', [FrontRepasController::class, 'index'])->name('repas.index');
        Route::post('/repas/analyze', [FrontRepasController::class, 'analyzeImage'])->name('repas.analyze.image');    // Demo workout editor UI
    Route::get('/workout/editor', function () { return view('front.workout.editor'); })->name('front.workout.editor');


  




});



// Maladie routes - ADMIN SEULEMENT
Route::middleware(['auth', 'maladie.check'])->prefix('maladies')->name('maladies.')->group(function () {

    Route::get('/', [MaladieController::class, 'index'])
        ->name('index');

    Route::get('/create', [MaladieController::class, 'create'])
        ->name('create');

    Route::post('/', [MaladieController::class, 'store'])
        ->name('store');

    Route::get('/{maladie}', [MaladieController::class, 'show'])
        ->name('show');

    Route::get('/{maladie}/edit', [MaladieController::class, 'edit'])
        ->name('edit');

    Route::put('/{maladie}', [MaladieController::class, 'update'])
        ->name('update');

    Route::delete('/{maladie}', [MaladieController::class, 'destroy'])
        ->name('destroy');

});

// Asymptome routes - ADMIN SEULEMENT
Route::middleware(['auth', 'asymptome.check'])->prefix('asymptomes')->name('asymptomes.')->group(function () {

    Route::get('/', [AsymptomeController::class, 'index'])
        ->name('index');

    Route::get('/create', [AsymptomeController::class, 'create'])
        ->name('create');

    Route::post('/', [AsymptomeController::class, 'store'])
        ->name('store');

    Route::get('/{asymptome}', [AsymptomeController::class, 'show'])
        ->name('show');

    Route::get('/{asymptome}/edit', [AsymptomeController::class, 'edit'])
        ->name('edit');

    Route::put('/{asymptome}', [AsymptomeController::class, 'update'])
        ->name('update');

    Route::delete('/{asymptome}', [AsymptomeController::class, 'destroy'])
        ->name('destroy');

});


Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/message', [ChatController::class, 'sendMessage'])->name('chat.send');