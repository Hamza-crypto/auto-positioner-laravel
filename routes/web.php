<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

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

Route::get('/test', function () {
    DiscordAlert::message("test message");
});

Route::redirect('/', '/dashboard');

Route::get('/reset', function () {
});

Route::get('/reset-all', function () {

    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh');
    \Illuminate\Support\Facades\Artisan::call('db:seed');
});

Route::group(['middleware' => ['securit_key']], function () {
    //
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::resource('users', UsersController::class);
Route::resource('employees', EmployeesController::class);
Route::resource('positions', PositionController::class);


Route::post('/employee/schedule', [DashboardController::class, 'displayEmployeeSchedule'])->name('employeeSchedule');
