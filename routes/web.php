<?php

use App\Http\Controllers\Api\TimeLogApi;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\TimeLog;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::get('/timelog/{id}', [TimeLog::class, 'show'])->name('timelog.show');
    Route::get('/timelog/{id}/history', [TimeLog::class, 'showLogs'])->name('timelog.showLogs');
    Route::get('/timelog/{id}/history/{logId}', [TimeLog::class, 'showLog'])->name('timelog.showSingelLog');
    Route::post('/timelog/{id}/history/{logId}', [TimeLog::class, 'edit'])->name('timelog.edit');
    Route::delete('/timelog/{id}/history/{logId}', [TimeLog::class, 'delete'])->name('timelog.delete');

    // move api endpoints to api routes
    Route::post('/timelog/{id}', [TimeLogApi::class, 'create']);
    Route::post('/timelog/{id}/stop', [TimeLogApi::class, 'stop']);
    Route::post('/timelog/{id}/manual', [TimeLogApi::class, 'setManualLog']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
