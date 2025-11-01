<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Models\Resume;

// Default launch → always redirect to resume id=1
Route::get('/', function () {
    return redirect()->route('resume.public', ['id' => 1]);
});

// Public resume with unique URL and id parameter
Route::get('/public_resume', [ResumeController::class, 'showPublic'])->name('resume.public');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout → redirect to most recently updated resume
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('web');

// Protected resume edit page (each user edits their own resume)
Route::middleware('auth')->group(function () {
    Route::get('/resume/edit', [ResumeController::class, 'edit'])->name('resume.edit');
    Route::post('/resume/update', [ResumeController::class, 'update'])->name('resume.update');
});
