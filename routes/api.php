<?php

use App\Http\Controllers\API\AuthController;
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

Route::middleware('auth:sanctum')->group(function () {
    // protected routes

});

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/forgot', 'forget');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('users', \App\Http\Controllers\API\Admin\UserController::class);
Route::resource('vendors', \App\Http\Controllers\API\Admin\VendorController::class);
Route::resource('types', \App\Http\Controllers\API\Admin\TypeController::class);
Route::resource('vehicles', \App\Http\Controllers\API\Admin\VehicleController::class);
Route::resource('rentals', \App\Http\Controllers\API\Admin\RentalController::class);
Route::resource('reviews', \App\Http\Controllers\API\Admin\ReviewController::class);
