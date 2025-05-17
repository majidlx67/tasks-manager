<?php

use App\Http\Controllers\Tasks\SubTaskController;
use App\Http\Controllers\Tasks\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    //return Inertia::render('Welcome');
    return auth()->check() ? to_route('tasks.index') : to_route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::resource('tasks', TaskController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);
Route::post('tasks/{task}/status', [TaskController::class, 'change_status'])
    ->middleware(['auth', 'verified'])
    ->name('tasks.status');

Route::post('tasks/{task}/subtasks/{subtask}/status', [SubTaskController::class, 'change_status'])
    ->middleware(['auth', 'verified'])
    ->name('subtasks.status');
Route::resource('tasks/{task}/subtasks', SubTaskController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified'])
    ->names('subtasks');
