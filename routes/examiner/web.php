<?php

use App\Http\Controllers\Examiner\AssessmentController;
use App\Http\Controllers\Examiner\HomeController;
use App\Http\Controllers\Examiner\Profile\PasswordController;
use App\Http\Controllers\Examiner\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'redirect_to_dashboard',
    'role:examiner',
    'localization',
    'set_selected_organization',
    'redirect_to_dashboard'
])->group(function () {

    Route::name('examiner.')->prefix('examiner')->group(function () {

        //home
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        //assessment routes
        Route::get('/assessments/data', [AssessmentController::class, 'data'])->name('assessments.data');
        Route::get('/assessments/{assessment}/start', [AssessmentController::class, 'start'])->name('assessments.start');
        Route::get('/assessments/{assessment}/resume', [AssessmentController::class, 'resume'])->name('assessments.resume');
        Route::post('/assessments/{assessment}/deductions', [AssessmentController::class, 'storeDeductions'])->name('assessments.deductions.store');
        Route::resource('assessments', AssessmentController::class)->only(['index', 'show']);

        //profile routes
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/switch_language', [ProfileController::class, 'switchLanguage'])->name('profile.switch_language');
        Route::get('/profile/toggle_dark_mode', [ProfileController::class, 'toggleDarkMode'])->name('profile.toggle_dark_mode');
        Route::get('/profile/toggle_menu_collapsed', [ProfileController::class, 'toggleMenuCollapsed'])->name('profile.toggle_menu_collapsed');
        Route::get('/profile/switch_organization/{organization}', [ProfileController::class, 'switchOrganization'])->name('profile.switch_organization');
        Route::get('/profile/switch_branch/{branch}', [ProfileController::class, 'switchBranch'])->name('profile.switch_branch');

        Route::name('profile.')->group(function () {

            //password routes
            Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

        });

    });

});
