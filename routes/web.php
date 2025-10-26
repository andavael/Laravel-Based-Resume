<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;

// Default launch → public resume view
Route::get('/', [ResumeController::class, 'showPublic']);

// Public resume with unique URL and id parameter (WITHOUT .php)
Route::get('/public_resume', [ResumeController::class, 'showPublic'])->name('resume.public');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected resume edit page (all users edit the same resume)
Route::middleware('auth')->group(function () {
    Route::get('/resume/edit', [ResumeController::class, 'edit'])->name('resume.edit');
    Route::post('/resume/update', [ResumeController::class, 'update'])->name('resume.update');
});