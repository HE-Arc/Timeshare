<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\TimetableController;
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

Route::get('/dashboard', function () {          // /dashboard
    return Inertia::render('Dashboard');        // Dashboard
})->middleware(['auth', 'verified'])->name('dashboard');    // dashboard


Route::resource('/timetables', TimetableController::class);
Route::get('managements/timetablesManagers', [ManagementController::class, 'timetablesManagers'])->name("managements.timetablesManagers");
Route::resource('/managements', ManagementController::class);
Route::get('events/timetablesEvents', [EventController::class, 'timetablesEvents'])->name("events.timetablesEvents");
Route::resource('/events', EventController::class);

require __DIR__.'/auth.php';
