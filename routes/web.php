<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VehicleController;
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

    \Illuminate\Support\Facades\Artisan::call('migrate:fresh');
    \Illuminate\Support\Facades\Artisan::call('db:seed');

});

Route::group(['middleware' => ['auth']], function () {

    Route::group([
        'prefix' => 'profile',
    ], function () {
        Route::get('/{tab?}', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('account', [ProfileController::class, 'account'])->name('profile.account');

    });

    //    Route::post('images', [ImageController::class, 'store'])->name('images.store');
    //    Route::post('images/feedback', [ImageController::class, 'store_feedback'])->name('images.store_feedback');

    Route::get('api/v1/vehicles', [DatatableController::class, 'vehicles'])->name('vehicles.ajax');
    Route::get('api/v1/vehicles/sold', [DatatableController::class, 'vehicles_sold'])->name('vehicles.sold.ajax');
    //Render modal for vehicle details
    Route::get('vehicles/{vehicle}/html', [DatatableController::class, 'getVehicleDetails'])->name('vehicle.detail.html');

});
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('dashboard/table', [DashboardController::class, 'get_table'])->name('dashboard.table');
Route::resource('users', UsersController::class);
Route::resource('employees', EmployeesController::class);
Route::resource('positions', PositionController::class);

Route::get('next_vehicle_id', [DatatableController::class, 'next_vehicle_id']);
Route::get('delete_unsaved_vehicles', [VehicleController::class, 'delete_unsaved_vehicles']);
