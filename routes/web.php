<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Models\Resume;


Route::get('/', function () {
    return redirect()->route('resume.public', ['id' => 1]);
});


Route::get('/public_resume', [ResumeController::class, 'showPublic'])->name('resume.public');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('web');

Route::middleware('auth')->group(function () {
    Route::get('/resume/edit', [ResumeController::class, 'edit'])->name('resume.edit');
    Route::post('/resume/update', [ResumeController::class, 'update'])->name('resume.update');
});
