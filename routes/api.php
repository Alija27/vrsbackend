<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FrontController;
use App\Http\Controllers\API\Vendor\FrontVendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/forgot', 'forget');
    Route::post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

// Admin Dashboard
Route::middleware(['auth:sanctum'])->prefix('/admin')->group(function () {
    Route::resource('users', \App\Http\Controllers\API\Admin\UserController::class);
    Route::resource('vendors', \App\Http\Controllers\API\Admin\VendorController::class);
    Route::resource('types', \App\Http\Controllers\API\Admin\TypeController::class);
    Route::resource('vehicles', \App\Http\Controllers\API\Admin\VehicleController::class);
    Route::resource('rentals', \App\Http\Controllers\API\Admin\RentalController::class);
    Route::resource('reviews', \App\Http\Controllers\API\Admin\ReviewController::class);
    Route::resource('locations', \App\Http\Controllers\API\Admin\LocationController::class);
});

// Route::middleware('auth:sactum', 'vendor')->group(function () {
//     Route::resource('vendors', \App\Http\Controllers\API\Admin\VendorController::class);
// });
Route::middleware('auth:sanctum')->controller(FrontVendorController::class)->group(function () {
    //Route::get('/addVehicle', 'addVehicle');
    Route::post('/addvehicle', 'addVehicle');
    Route::post('/updatevehicle', 'updateVehicle');
    Route::get('/locations', 'locations');
    Route::get('/registeredvehicles', 'revehicles');
    Route::post('/vehiclestatus/{vehicle}', 'vehicleStatus');
    Route::get('/getRequestList', 'getRequestList');
    Route::put('/changeRentalStatus/{rental}', 'vehicleApproved');
});

Route::controller(FrontController::class)->group(function () {
    Route::get('/types', 'types');
    Route::get('/availablevehicles', 'availablevehicles');
    Route::post('/vehicles', 'vehicles');
    Route::get('/locations', 'locations');
    Route::post('/users', 'users');
    Route::get('/vehicle/{vehicle}', 'showVehicle');
    Route::post('/checkvehicle/{vehicle}', 'checkVehicle');
    Route::post('/requestVehicle', 'requestVehicle');
    Route::get('/vehicleReview', 'VehicleReview');

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/updateProfile', 'updateProfile');
        Route::get('/getMyBookings', 'myBookedVehicles');
        Route::post('/checkUserDetails', 'checkUserDetails');
        Route::get('/bookConfirmed', 'BookConfirmed');
        Route::post('/vendor-register', 'VendorRegister');
    });
});
