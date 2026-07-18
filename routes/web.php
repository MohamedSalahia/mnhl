<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['localization'])
    ->group(function () {

        Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

        Route::view('/cert/test', 'editor');

        Route::post(
            '/save-certificate',
            [WelcomeController::class, 'save']
        );
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // Student registration routes
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');

        // Teacher registration routes
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');

        require __DIR__ . '/auth.php';

    });

