<?php

use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\ClassroomController;
use App\Http\Controllers\Teacher\HomeController;
use App\Http\Controllers\Teacher\LessonController;
use App\Http\Controllers\Teacher\Profile\PasswordController;
use App\Http\Controllers\Teacher\ProfileController;
use App\Http\Controllers\Teacher\StudentLessonController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'redirect_to_dashboard',
    'role:teacher',
    'localization',
    'set_selected_organization',
    'redirect_to_dashboard'
])->group(function () {

    Route::name('teacher.')->prefix('teacher')->group(function () {

        //home
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        //classroom routes
        Route::get('/classrooms/data', [ClassroomController::class, 'data'])->name('classrooms.data');
        Route::resource('classrooms', ClassroomController::class)->only(['index', 'show']);

        //lesson routes
        Route::get('/lessons/data', [LessonController::class, 'data'])->name('lessons.data');
        Route::get('/lessons/{lesson}/download_report', [LessonController::class, 'downloadReport'])->name('lessons.download_report');
        Route::get('/lessons/{lesson}/time_elapsed/edit', [LessonController::class, 'editTimeElapsed'])->name('lessons.edit_time_elapsed');
        Route::put('/lessons/{lesson}/time_elapsed', [LessonController::class, 'updateTimeElapsed'])->name('lessons.update_time_elapsed');
        Route::resource('lessons', LessonController::class)->only(['index', 'show', 'create', 'store']);

        //student lesson routes
        Route::get('/student_lessons/{studentLesson}/edit', [StudentLessonController::class, 'edit'])->name('student_lessons.edit');
        Route::put('/student_lessons/{studentLesson}', [StudentLessonController::class, 'update'])->name('student_lessons.update');
        Route::post('/student_lessons/{studentLesson}/select_evaluation_item', [StudentLessonController::class, 'selectEvaluationItem'])->name('student_lessons.select_evaluation_item');
        Route::post('/student_lessons/{studentLesson}/deselect_evaluation_item', [StudentLessonController::class, 'deselectEvaluationItem'])->name('student_lessons.deselect_evaluation_item');

        //assessment routes
        Route::get('/assessments/data', [AssessmentController::class, 'data'])->name('assessments.data');
        Route::get('/assessments/{assessment}/start', [AssessmentController::class, 'start'])->name('assessments.start');
        Route::get('/assessments/{assessment}/resume', [AssessmentController::class, 'resume'])->name('assessments.resume');
        Route::post('/assessments/{assessment}/deductions', [AssessmentController::class, 'storeDeductions'])->name('assessments.deductions.store');
        Route::resource('assessments', AssessmentController::class)->except(['edit', 'update']);

        //profile routes
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/switch_language', [ProfileController::class, 'switchLanguage'])->name('profile.switch_language');
        Route::get('/profile/toggle_dark_mode', [ProfileController::class, 'toggleDarkMode'])->name('profile.toggle_dark_mode');
        Route::get('/profile/toggle_menu_collapsed', [ProfileController::class, 'toggleMenuCollapsed'])->name('profile.toggle_menu_collapsed');
        Route::get('/profile/switch_organization/{organization}', [ProfileController::class, 'switchOrganization'])->name('profile.switch_organization');
        Route::get('/profile/switch_branch/{branch}', [ProfileController::class, 'switchBranch'])->name('profile.switch_branch');
        Route::get('/profile/leave_impersonate', [ProfileController::class, 'leaveImpersonate'])->name('profile.leave_impersonate');

        Route::name('profile.')->group(function () {

            //password routes
            Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

        });

    });

});
